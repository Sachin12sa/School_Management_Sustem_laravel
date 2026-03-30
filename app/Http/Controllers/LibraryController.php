<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\BookIssue;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class LibraryController extends Controller
{
    // ═══════════════════════════════════════════════════════════════
    //  PRIVATE HELPERS
    // ═══════════════════════════════════════════════════════════════

    /**
     * Always returns YYYY-MM-DD string.
     * Handles Carbon objects, "2026-03-11 00:00:00", "2026-03-11".
     */
    private function ds($date): string
    {
        if ($date instanceof Carbon) return $date->format('Y-m-d');
        return substr((string) $date, 0, 10);
    }

    /**
     * Days late: how many days after $dueStr was $returnStr.
     * Returns 0 if on time or early.
     */
    protected function daysLate($dueDate, $returnDate)
{
    $due    = \Carbon\Carbon::parse($dueDate)->startOfDay();
    $return = \Carbon\Carbon::parse($returnDate)->startOfDay();

    if ($return->lessThanOrEqualTo($due)) {
        return 0;
    }

    return $due->diffInDays($return);
}

    /** Check if damage columns exist (migration may not have run yet) */
    private function hasDamageColumns(): bool
    {
        return Schema::hasColumn('book_issues', 'book_condition');
    }

    // ═══════════════════════════════════════════════════════════════
    //  BOOKS — CRUD (admin)
    // ═══════════════════════════════════════════════════════════════

    public function bookList(Request $request)
    {
        $data['getRecord']     = Book::getRecord($request->only(['title','author','category','status']));
        $data['getCategories'] = Book::getCategories();
        $data['summary']       = Book::getSummary();
        $data['header_title']  = 'Library — Books';
        return view('admin.library.book.list', $data);
    }

    public function bookAdd()
    {
        $data['getCategories'] = Book::getCategories();
        $data['header_title']  = 'Add New Book';
        return view('admin.library.book.add', $data);
    }

    public function bookInsert(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'author'      => 'required|string|max:255',
            'isbn'        => 'nullable|string|max:20|unique:books,isbn',
            'quantity'    => 'required|integer|min:1',
            'status'      => 'required|in:0,1',
            'cover_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $book               = new Book;
        $book->title        = trim($request->title);
        $book->author       = trim($request->author);
        $book->isbn         = trim($request->isbn);
        $book->publisher    = trim($request->publisher);
        $book->edition      = trim($request->edition);
        $book->publish_year = $request->publish_year;
        $book->category     = trim($request->category);
        $book->rack_number  = trim($request->rack_number);
        $book->quantity     = $request->quantity;
        $book->available    = $request->quantity;
        $book->description  = $request->description;
        $book->status       = $request->status;
        $book->created_by   = Auth::id();

        if ($request->hasFile('cover_image')) {
            $file              = $request->file('cover_image');
            $book->cover_image = $file->storeAs(
                'library/covers',
                Str::slug($request->title).'-'.time().'.'.$file->getClientOriginalExtension(),
                'public'
            );
        }

        $book->save();
        return redirect('admin/library/book/list')->with('success','Book added successfully.');
    }

    public function bookEdit($id)
    {
        $data['getRecord'] = Book::getSingle($id);
        if (empty($data['getRecord'])) abort(404);
        $data['getCategories'] = Book::getCategories();
        $data['header_title']  = 'Edit Book';
        return view('admin.library.book.edit', $data);
    }

    public function bookUpdate($id, Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'author'      => 'required|string|max:255',
            'isbn'        => 'nullable|string|max:20|unique:books,isbn,'.$id,
            'quantity'    => 'required|integer|min:1',
            'status'      => 'required|in:0,1',
            'cover_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $book     = Book::getSingle($id);
        $issued   = $book->quantity - $book->available;
        $newAvail = max(0, $request->quantity - $issued);

        $book->title        = trim($request->title);
        $book->author       = trim($request->author);
        $book->isbn         = trim($request->isbn);
        $book->publisher    = trim($request->publisher);
        $book->edition      = trim($request->edition);
        $book->publish_year = $request->publish_year;
        $book->category     = trim($request->category);
        $book->rack_number  = trim($request->rack_number);
        $book->quantity     = $request->quantity;
        $book->available    = $newAvail;
        $book->description  = $request->description;
        $book->status       = $request->status;

        if ($request->hasFile('cover_image')) {
            if (!empty($book->cover_image) && file_exists(storage_path('app/public/'.$book->cover_image))) {
                unlink(storage_path('app/public/'.$book->cover_image));
            }
            $file              = $request->file('cover_image');
            $book->cover_image = $file->storeAs(
                'library/covers',
                Str::slug($request->title).'-'.time().'.'.$file->getClientOriginalExtension(),
                'public'
            );
        }

        $book->save();
        return redirect('admin/library/book/list')->with('success','Book updated successfully.');
    }

    public function bookDelete($id)
    {
        $book            = Book::getSingle($id);
        $book->is_delete = 1;
        $book->save();
        return redirect('admin/library/book/list')->with('success','Book deleted.');
    }

    // ═══════════════════════════════════════════════════════════════
    //  BOOK ISSUES (admin)
    // ═══════════════════════════════════════════════════════════════

    public function issueList(Request $request)
    {
        // Auto-mark issued → overdue. NEVER touches 'returned'.
        DB::table('book_issues')
            ->where('status',   'issued')
            ->where('due_date', '<', now()->toDateString())
            ->where('is_delete', 0)
            ->update(['status' => 'overdue', 'updated_at' => now()]);

        $data['getRecord']    = BookIssue::getRecord($request->only(['member_name','book_id','status']));
        
        $data['getBooks']     = Book::where('is_delete',0)->orderBy('title')->get();
        $data['summary']      = BookIssue::getSummary();
        $data['header_title'] = 'Library — Issue Records';
        return view('admin.library.issue.list', $data);
    }

    public function issueAdd()
    {
        $data['getBooks']   = Book::getAvailable();
        $data['getMembers'] = User::whereIn('user_type',[2,3])
            ->where('is_delete',0)->where('status',0)
            ->orderBy('user_type')->orderBy('name')
            ->get(['id','name','last_name','user_type','admission_number']);
        $data['header_title'] = 'Issue Book';
        return view('admin.library.issue.add', $data);
    }

    public function issueInsert(Request $request)
    {
        $request->validate([
            'book_id'      => 'required|exists:books,id',
            'member_id'    => 'required|exists:users,id',
            'issue_date'   => 'required|date',
            'due_date'     => 'required|date|after_or_equal:issue_date',
            'fine_per_day' => 'nullable|integer|min:0',
        ]);

        $book = Book::findOrFail($request->book_id);
        if ($book->available <= 0)
            return back()->withInput()->withErrors(['book_id' => 'No copies available.']);

        if (BookIssue::where('book_id',$request->book_id)
            ->where('member_id',$request->member_id)
            ->whereIn('status',['issued','overdue'])
            ->where('is_delete',0)->exists())
            return back()->withInput()->withErrors(['member_id' => 'This member already has this book.']);

        $createData = [
            'book_id'      => $request->book_id,
            'member_id'    => $request->member_id,
            'issue_date'   => $request->issue_date,
            'due_date'     => $request->due_date,
            'fine_per_day' => $request->fine_per_day ?? 0,
            'fine_status'  => 'none',
            'status'       => 'issued',
            'note'         => $request->note,
            'created_by'   => Auth::id(),
        ];

        // Include damage columns only if they exist
        if ($this->hasDamageColumns()) {
            $createData['book_condition'] = 'good';
            $createData['damage_charge']  = 0;
        }

        BookIssue::create($createData);
        $book->decrement('available');
        return redirect('admin/library/issue/list')->with('success','Book issued successfully.');
    }

    public function issueEdit($id)
    {
        $data['getRecord'] = BookIssue::with(['book', 'member'])->findOrFail($id);
        $data['header_title'] = 'Edit Issue Record';
        $data['hasDamageCols'] = $this->hasDamageColumns(); // Reusing your helper
        
        return view('admin.library.issue.edit', $data);
    }

    public function issueUpdate(Request $request, $id)
    {
        $issue = BookIssue::findOrFail($id);

        // Update core issue details
        $issue->issue_date   = $request->issue_date;
        $issue->due_date     = $request->due_date;
        $issue->fine_per_day = $request->fine_per_day;
        $issue->note         = $request->note;

        // If the book is already returned, allow updating return-specific data
        if ($issue->status === 'returned') {
            $issue->return_date = $request->return_date;
            $issue->fine_amount = $request->fine_amount;
            $issue->fine_status = $request->fine_status;
            
            if ($this->hasDamageColumns()) {
                // NOTE: Changing condition here does NOT automatically trigger stock changes. 
                // You would need to write specific delta logic (e.g. if old condition was 'lost' and new is 'good', increment stock).
                $issue->book_condition = $request->book_condition;
                $issue->damage_charge  = $request->damage_charge;
                $issue->damage_note    = $request->damage_note;
            }
        }

        $issue->save();

        return redirect('admin/library/issue/list')->with('success', 'Issue record updated successfully.');
    }

    public function issueDelete($id)
    {
        $issue = BookIssue::findOrFail($id);
        if ($issue->status !== 'returned')
            return back()->with('error','Cannot delete an active issue. Return the book first.');
        $issue->is_delete = 1;
        $issue->save();
        return redirect('admin/library/issue/list')->with('success','Record deleted.');
    }

    public function returnForm($id)
    {
        $data['getRecord']      = BookIssue::with(['book','member'])->findOrFail($id);
        $data['hasDamageCols']  = $this->hasDamageColumns();
        $data['header_title']   = 'Return Book';
        return view('admin.library.issue.return', $data);
    }

    // ── RETURN BOOK ───────────────────────────────────────────────
    //
    // FLOW:
    //  1. Staff processes physical return here.
    //  2. System calculates late fine (days × rate).
    //  3. Staff selects book condition: good / damaged / torn / lost.
    //     - damaged  → damage charge added on top of late fine
    //     - torn     → full replacement cost charged; book removed from stock
    //     - lost     → same as torn
    //     - good     → only late fine (if any)
    //  4. fine_status = 'unpaid' if any charge > 0, else 'none'.
    //  5. Book returns to available stock ONLY if condition = good or damaged.
    //     Torn / lost → quantity decremented (book gone permanently).
    //  6. Fine payment is a SEPARATE step in Library → Fines.
    //
    public function returnBook($id, Request $request)
{
    $issue = BookIssue::findOrFail($id);

    if ($issue->status === 'returned') {
        return back()->with('error','This book has already been returned.');
    }

    $returnDate = $request->return_date ?? now()->toDateString();
    $dueStr     = $this->ds($issue->due_date);

    // ✅ FIXED: no negative late
    $late     = max(0, $this->daysLate($dueStr, $returnDate));
    $lateFine = $late * (int) $issue->fine_per_day;

    $hasDmg = $this->hasDamageColumns();

    $condition    = 'good';
    $damageCharge = 0;
    $damageNote   = null;

    if ($hasDmg) {
        $condition    = $request->book_condition ?? 'good';
        $damageCharge = max(0, (float) ($request->damage_charge ?? 0));
        $damageNote   = $request->damage_note;
    }

    $totalFine = $lateFine + $damageCharge;

    $fineStatus = ($late > 0 || $damageCharge > 0) ? 'unpaid' : 'none';

    // ✅ NEW: return type
    $returnType = $late > 0 ? 'late' : 'on_time';

    $update = [
        'return_date' => $returnDate,
        'fine_amount' => $totalFine,
        'fine_status' => $fineStatus,
        'return_type' => $returnType,
        'status'      => 'returned',
        'returned_by' => Auth::id(),
        'note'        => $request->note ?? $issue->note,
        'updated_at'  => now(),
    ];

    if ($hasDmg) {
        $update['book_condition'] = $condition;
        $update['damage_charge']  = $damageCharge;
        $update['damage_note']    = $damageNote;
    }

    DB::table('book_issues')->where('id',$id)->update($update);

    // Stock handling
    if (in_array($condition, ['torn','lost'])) {
        Book::where('id',$issue->book_id)->decrement('quantity');
    } else {
        Book::where('id',$issue->book_id)->increment('available');
    }

    // Message
    $msg = 'Book returned successfully.';

    if ($returnType === 'late') {
        $msg .= ' Returned late ('.$late.' days).';
    }

    if ($lateFine > 0) {
        $msg .= ' Late fine: Rs.'.number_format($lateFine,2).'.';
    }

    if ($damageCharge > 0) {
        $msg .= ' Damage charge: Rs.'.number_format($damageCharge,2).'.';
    }

    if ($totalFine > 0) {
        $msg .= ' Total Rs.'.number_format($totalFine,2).' unpaid.';
    }

    return redirect('admin/library/issue/list')->with('success',$msg);
}

    // ═══════════════════════════════════════════════════════════════
    //  RETURN POLICY (informational page)
    // ═══════════════════════════════════════════════════════════════

    public function returnPolicy()
    {
        $data['header_title'] = 'Library Return Policy';
        return view('admin.library.return_policy', $data);
    }
    public function returnPolicyLibrarian()
    {
        $data['header_title'] = 'Library Return Policy';
        return view('librarian.library.return_policy', $data);
    }

    // ═══════════════════════════════════════════════════════════════
    //  LIBRARY DASHBOARD
    // ═══════════════════════════════════════════════════════════════

    public function dashboard()
    {
        DB::table('book_issues')
            ->where('status','issued')
            ->where('due_date','<',now()->toDateString())
            ->where('is_delete',0)
            ->update(['status' => 'overdue','updated_at' => now()]);

        $summary = [
            'total_books'      => Book::where('is_delete',0)->count(),
            'total_copies'     => Book::where('is_delete',0)->sum('quantity'),
            'available_copies' => Book::where('is_delete',0)->sum('available'),
            'total_issued'     => BookIssue::whereIn('status',['issued','overdue'])->where('is_delete',0)->count(),
            'overdue'          => BookIssue::where('status','overdue')->where('is_delete',0)->count(),
        ];

        $overdueBooks = BookIssue::with(['book','member'])
            ->where('status','overdue')->where('is_delete',0)
            ->orderBy('due_date')->limit(8)->get();

        $recentIssues = BookIssue::with(['book','member'])
            ->where('is_delete',0)->orderByDesc('created_at')->limit(8)->get();

        $popularBooks = Book::withCount('issues')
            ->where('is_delete',0)->orderByDesc('issues_count')->limit(8)->get();

        $byCategory = Book::where('is_delete',0)->whereNotNull('category')->where('category','!=','')
            ->selectRaw('category, COUNT(*) as total')->groupBy('category')->orderByDesc('total')->get();

        $data                 = compact('summary','overdueBooks','recentIssues','popularBooks','byCategory');
        $data['header_title'] = 'Library Dashboard';
        return view('librarian.dashboard', $data);
    }

    // ═══════════════════════════════════════════════════════════════
    //  FINES
    // ═══════════════════════════════════════════════════════════════

    public function fineList(Request $request)
    {
        // Auto-mark issued → overdue. NEVER touches 'returned'.
        DB::table('book_issues')
            ->where('status','issued')
            ->where('due_date','<',now()->toDateString())
            ->where('is_delete',0)
            ->update(['status' => 'overdue','updated_at' => now()]);

        $tab        = $request->get('tab','unpaid');
        $memberName = $request->get('member_name');
        $memberType = $request->get('member_type');
        $hasDmg     = $this->hasDamageColumns();

        // Build select list — only include damage cols if migration has run
        $cols = [
            'book_issues.id',
            'book_issues.status',
            'book_issues.fine_status',
            'book_issues.fine_amount',
            'book_issues.fine_per_day',
            'book_issues.fine_payment_method',
            'book_issues.fine_paid_at',
            'book_issues.fine_note',
            'book_issues.due_date',
            'book_issues.return_date',
            'book_issues.issue_date',
            // Calculate days late at DB level so blade can display it
            DB::raw("CASE
                WHEN book_issues.status = 'returned' AND book_issues.return_date IS NOT NULL
                THEN GREATEST(0, DATEDIFF(DATE(book_issues.return_date), DATE(book_issues.due_date)))
                WHEN book_issues.status = 'overdue'
                THEN GREATEST(0, DATEDIFF(CURDATE(), DATE(book_issues.due_date)))
                ELSE 0
            END as days_late"),
            'b.title  as book_title',
            'm.name      as member_name',
            'm.last_name as member_last_name',
            'm.user_type as member_type',
            'm.admission_number',
            DB::raw("TRIM(CONCAT(COALESCE(fc.name,''),' ',COALESCE(fc.last_name,''))) as collector_name"),
        ];

        if ($hasDmg) {
            $cols[] = 'book_issues.book_condition';
            $cols[] = 'book_issues.damage_charge';
            $cols[] = 'book_issues.damage_note';
        }

        $query = DB::table('book_issues')
            ->join('books as b', 'b.id','=','book_issues.book_id')
            ->join('users as m', 'm.id','=','book_issues.member_id')
            ->leftJoin('users as fc','fc.id','=','book_issues.fine_collected_by')
            ->select($cols)
            ->where('book_issues.is_delete', 0);

        // ── TAB FILTERS ───────────────────────────────────────────
        // unpaid  = RETURNED book marked unpaid (late OR damaged).
        //           fine_amount can be 0 when fine_per_day = 0 but still shows so admin can act.
        // overdue = book STILL OUT past due date (accruing, not yet returned)
        // paid    = fine has been collected
        // waived  = fine was waived
        if ($tab === 'unpaid') {
            $query->where('book_issues.status',     'returned')
                  ->where('book_issues.fine_status', 'unpaid');
            // Intentionally NO fine_amount > 0 filter here

        } elseif ($tab === 'overdue') {
            $query->where('book_issues.status', 'overdue')
                  ->whereNotIn('book_issues.fine_status', ['waived','paid']);

        } elseif ($tab === 'paid') {
            $query->where('book_issues.fine_status', 'paid');

        } elseif ($tab === 'waived') {
            $query->where('book_issues.fine_status', 'waived');
        }

        if ($memberName) {
            $query->where(function ($q) use ($memberName) {
                $q->where('m.name',       'like','%'.$memberName.'%')
                  ->orWhere('m.last_name','like','%'.$memberName.'%');
            });
        }
        if ($memberType) {
            $query->where('m.user_type', $memberType);
        }

        $data['getRecord']    = $query->orderByDesc('book_issues.due_date')->paginate(15);
        $data['fineSummary']  = BookIssue::getFineSummary();
        $data['tab']          = $tab;
        $data['hasDamageCols'] = $hasDmg;
        $data['header_title'] = 'Library Fines';
        $prefix = Auth::user()->user_type == 6 ? 'librarian' : 'admin';
        return view($prefix.'.library.fine.list', $data);
    }

    public function fineCollect($id)
    {
        $issue    = BookIssue::with(['book','member'])->findOrFail($id);
        $dueStr   = $this->ds($issue->due_date);
        $daysLate = 0;
        $liveFine = (float) $issue->fine_amount;

        if ($issue->status === 'overdue') {
            $daysLate = $this->daysLate($dueStr, now()->toDateString());
            $liveFine = $daysLate * (int) $issue->fine_per_day;
        } elseif ($issue->status === 'returned' && $issue->return_date) {
            $daysLate = $this->daysLate($dueStr, $this->ds($issue->return_date));
            $liveFine = (float) $issue->fine_amount; // locked at return time (includes damage)
        }

        $data['getRecord']    = $issue;
        $data['liveFine']     = $liveFine;
        $data['daysLate']     = $daysLate;
        $data['dueStr']       = $dueStr;
        $data['header_title'] = 'Collect Fine Payment';
        $prefix = Auth::user()->user_type == 6 ? 'librarian' : 'admin';
        return view($prefix.'.library.fine.collect', $data);
    }

    public function fineCollectSubmit($id, Request $request)
    {
        $request->validate([
            'fine_payment_method' => 'required|in:cash,bank,online',
            'fine_paid_at'        => 'required|date',
        ]);

        $issue = BookIssue::findOrFail($id);
        if ($issue->fine_status === 'paid')
            return back()->with('error','This fine has already been paid.');

        $fineAmount = (float) $issue->fine_amount;
        // For overdue-still-out books, lock in today's accrued fine
        if ($issue->status === 'overdue') {
            $fineAmount = $this->daysLate($this->ds($issue->due_date), now()->toDateString())
                          * (int) $issue->fine_per_day;
        }

        DB::table('book_issues')->where('id',$id)->update([
            'fine_amount'         => $fineAmount,
            'fine_status'         => 'paid',
            'fine_payment_method' => $request->fine_payment_method,
            'fine_paid_at'        => $request->fine_paid_at,
            'fine_collected_by'   => Auth::id(),
            'fine_note'           => $request->fine_note,
            'updated_at'          => now(),
        ]);
        $prefix = Auth::user()->user_type == '6' ? 'librarian' : 'admin';

        return redirect($prefix.'/library/fine/list?tab=paid')
            ->with('success','Fine of Rs.'.number_format($fineAmount,2).' collected successfully.');
    }

    public function fineWaive($id, Request $request)
    {
        $issue = BookIssue::findOrFail($id);
        if ($issue->fine_status === 'paid')
            return back()->with('error','Fine is already paid — cannot waive.');

        DB::table('book_issues')->where('id',$id)->update([
            'fine_status'       => 'waived',
            'fine_note'         => $request->reason ?? 'Waived by admin',
            'fine_collected_by' => Auth::id(),
            'updated_at'        => now(),
        ]);
        $prefix = Auth::user()->user_type == '6' ? 'librarian' : 'admin';

        return redirect($prefix.'/library/fine/list?tab=waived')
            ->with('success','Fine waived successfully.');
    }

    // ── FINE REPORT ───────────────────────────────────────────────
    // Uses DATE(return_date) to handle datetime columns correctly.
    // All variables passed to view explicitly — no compact() ambiguity.
    public function fineReport(Request $request)
    {
        $dateFrom = $request->get('date_from', now()->startOfMonth()->toDateString());
        $dateTo   = $request->get('date_to',   now()->toDateString());
        $hasDmg   = $this->hasDamageColumns();

        // Base: returned books in date range
        $base = DB::table('book_issues')
            ->join('books as b','b.id','=','book_issues.book_id')
            ->join('users as m','m.id','=','book_issues.member_id')
            ->where('book_issues.is_delete', 0)
            ->where('book_issues.status',    'returned')
            // DATE() cast handles "2026-03-17 00:00:00" correctly
            ->whereRaw('DATE(book_issues.return_date) BETWEEN ? AND ?', [$dateFrom, $dateTo]);

        // Fine-only subset
        $fineBase = (clone $base)->where('book_issues.fine_amount', '>', 0);

        $total         = (clone $fineBase)->count();
        $totalReturns  = (clone $base)->count();
        $collected     = (float)(clone $fineBase)->where('book_issues.fine_status','paid')  ->sum('book_issues.fine_amount');
        $unpaid        = (float)(clone $fineBase)->where('book_issues.fine_status','unpaid')->sum('book_issues.fine_amount');
        $waived        = (float)(clone $fineBase)->where('book_issues.fine_status','waived')->sum('book_issues.fine_amount');

        // Damage stats (only if columns exist)
        $damageTotal    = 0;
        $conditionStats = collect();
        if ($hasDmg) {
            $damageTotal    = (float)(clone $base)->where('book_issues.damage_charge','>',0)->sum('book_issues.damage_charge');
            $conditionStats = (clone $base)
                ->select('book_issues.book_condition', DB::raw('COUNT(*) as cnt'))
                ->groupBy('book_issues.book_condition')
                ->get();
        }

        // By payment method
        $byMethod = (clone $fineBase)
            ->where('book_issues.fine_status','paid')
            ->select('book_issues.fine_payment_method',
                DB::raw('COUNT(*) as cnt'),
                DB::raw('SUM(book_issues.fine_amount) as total'))
            ->groupBy('book_issues.fine_payment_method')
            ->get();

        // Top members
        $topMembers = (clone $fineBase)
            ->select(
                'm.name as member_name','m.last_name as member_last_name','m.user_type as member_type',
                DB::raw('COUNT(*) as fine_count'),
                DB::raw('SUM(book_issues.fine_amount) as total_fine'),
                DB::raw('SUM(CASE WHEN book_issues.fine_status="paid"   THEN book_issues.fine_amount ELSE 0 END) as paid_fine'),
                DB::raw('SUM(CASE WHEN book_issues.fine_status="unpaid" THEN book_issues.fine_amount ELSE 0 END) as unpaid_fine')
            )
            ->groupBy('book_issues.member_id','m.name','m.last_name','m.user_type')
            ->orderByDesc('total_fine')->limit(10)->get();

        // Transactions (includes damage columns if available)
        $txCols = [
            'book_issues.id','book_issues.fine_amount','book_issues.fine_status',
            'book_issues.fine_payment_method','book_issues.fine_paid_at',
            'book_issues.due_date','book_issues.return_date',
            'b.title as book_title',
            'm.name as member_name','m.last_name as member_last_name','m.user_type as member_type',
        ];
        if ($hasDmg) {
            $txCols[] = 'book_issues.book_condition';
            $txCols[] = 'book_issues.damage_charge';
        }

        $transactions = (clone $base)->select($txCols)->orderByDesc('book_issues.return_date')->paginate(20);

        // Pass everything explicitly — no compact() that could cause $dateFrom undefined
        $data = [
            'dateFrom'       => $dateFrom,
            'dateTo'         => $dateTo,
            'hasDamageCols'  => $hasDmg,
            'transactions'   => $transactions,
            'header_title'   => 'Fine & Damage Report',
            'report' => [
                'total'          => $total,
                'totalReturns'   => $totalReturns,
                'collected'      => $collected,
                'unpaid'         => $unpaid,
                'waived'         => $waived,
                'damageTotal'    => $damageTotal,
                'conditionStats' => $conditionStats,
                'byMethod'       => $byMethod,
                'topMembers'     => $topMembers,
            ],
        ];
        $prefix = Auth::user()->user_type == '6' ? 'librarian' : 'admin';

        return view($prefix.'.library.fine.report', $data);
    }

    // ═══════════════════════════════════════════════════════════════
    //  STUDENT & TEACHER
    // ═══════════════════════════════════════════════════════════════

    public function myBooks()
    {
        $member_id = Auth::id();
        DB::table('book_issues')
            ->where('member_id',$member_id)->where('status','issued')
            ->where('due_date','<',now()->toDateString())->where('is_delete',0)
            ->update(['status' => 'overdue','updated_at' => now()]);

        $data['getRecord']    = BookIssue::getMemberBooks($member_id);
        $data['header_title'] = 'My Books';
        return view(Auth::user()->user_type == 2 ? 'teacher.my_book' : 'student.my_book', $data);
    }

    public function myFines()
    {
        $member_id = Auth::id();
        DB::table('book_issues')
            ->where('member_id',$member_id)->where('status','issued')
            ->where('due_date','<',now()->toDateString())->where('is_delete',0)
            ->update(['status' => 'overdue','updated_at' => now()]);

        $records = BookIssue::getMemberFines($member_id);

        $totalUnpaid   = $records->where('fine_status','unpaid')->sum('fine_amount');
        $totalAccruing = 0;
        foreach ($records->where('status','overdue') as $r) {
            $totalAccruing += $this->daysLate($this->ds($r->due_date), now()->toDateString())
                              * (int) $r->fine_per_day;
        }

        $data = [
            'getRecord'     => $records,
            'totalUnpaid'   => $totalUnpaid,
            'totalAccruing' => $totalAccruing,
            'header_title'  => 'My Library Fines',
        ];

        return view(
            Auth::user()->user_type == 2 ? 'teacher.my_library_fine' : 'student.my_library_fine',
            $data
        );
    }

    // ═══════════════════════════════════════════════════════════════
    //  LIBRARIAN — same logic, different views / redirects
    // ═══════════════════════════════════════════════════════════════

    public function bookListLibrarian(Request $request)
    {
        $data['getRecord']     = Book::getRecord($request->only(['title','author','category','status']));
        $data['getCategories'] = Book::getCategories();
        $data['summary']       = Book::getSummary();
        $data['header_title']  = 'Library — Books';
        return view('librarian.library.book.list', $data);
    }

    public function bookAddLibrarian()
    {
        $data['getCategories'] = Book::getCategories();
        $data['header_title']  = 'Add New Book';
        return view('librarian.library.book.add', $data);
    }

    public function bookInsertLibrarian(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255', 'author' => 'required|string|max:255',
            'isbn'  => 'nullable|string|max:20|unique:books,isbn',
            'quantity' => 'required|integer|min:1', 'status' => 'required|in:0,1',
            'cover_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $book = new Book;
        $book->title = trim($request->title); $book->author = trim($request->author);
        $book->isbn = trim($request->isbn); $book->publisher = trim($request->publisher);
        $book->edition = trim($request->edition); $book->publish_year = $request->publish_year;
        $book->category = trim($request->category); $book->rack_number = trim($request->rack_number);
        $book->quantity = $request->quantity; $book->available = $request->quantity;
        $book->description = $request->description; $book->status = $request->status;
        $book->created_by = Auth::id();

        if ($request->hasFile('cover_image')) {
            $file = $request->file('cover_image');
            $book->cover_image = $file->storeAs('library/covers',
                Str::slug($request->title).'-'.time().'.'.$file->getClientOriginalExtension(),'public');
        }
        $book->save();
        return redirect('librarian/library/book/list')->with('success','Book added successfully.');
    }

    public function bookEditLibrarian($id)
    {
        $data['getRecord'] = Book::getSingle($id);
        if (empty($data['getRecord'])) abort(404);
        $data['getCategories'] = Book::getCategories();
        $data['header_title']  = 'Edit Book';
        return view('librarian.library.book.edit', $data);
    }

    public function bookUpdateLibrarian($id, Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255','author' => 'required|string|max:255',
            'isbn'  => 'nullable|string|max:20|unique:books,isbn,'.$id,
            'quantity' => 'required|integer|min:1','status' => 'required|in:0,1',
            'cover_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);
        $book = Book::getSingle($id);
        $issued = $book->quantity - $book->available;
        $book->title = trim($request->title); $book->author = trim($request->author);
        $book->isbn = trim($request->isbn); $book->publisher = trim($request->publisher);
        $book->edition = trim($request->edition); $book->publish_year = $request->publish_year;
        $book->category = trim($request->category); $book->rack_number = trim($request->rack_number);
        $book->quantity = $request->quantity; $book->available = max(0,$request->quantity - $issued);
        $book->description = $request->description; $book->status = $request->status;

        if ($request->hasFile('cover_image')) {
            if (!empty($book->cover_image) && file_exists(storage_path('app/public/'.$book->cover_image)))
                unlink(storage_path('app/public/'.$book->cover_image));
            $file = $request->file('cover_image');
            $book->cover_image = $file->storeAs('library/covers',
                Str::slug($request->title).'-'.time().'.'.$file->getClientOriginalExtension(),'public');
        }
        $book->save();
        return redirect('librarian/library/book/list')->with('success','Book updated successfully.');
    }

    public function bookDeleteLibrarian($id)
    {
        $book = Book::getSingle($id); $book->is_delete = 1; $book->save();
        return redirect('librarian/library/book/list')->with('success','Book deleted.');
    }

    public function issueListLibrarian(Request $request)
    {
        DB::table('book_issues')->where('status','issued')
            ->where('due_date','<',now()->toDateString())->where('is_delete',0)
            ->update(['status' => 'overdue','updated_at' => now()]);
        $data['getRecord']    = BookIssue::getRecord($request->only(['member_name','book_id','status']));
        $data['getBooks']     = Book::where('is_delete',0)->orderBy('title')->get();
        $data['summary']      = BookIssue::getSummary();
        $data['header_title'] = 'Library — Issue Records';
        return view('librarian.library.issue.list', $data);
    }

    public function issueAddLibrarian()
    {
        $data['getBooks']   = Book::getAvailable();
        $data['getMembers'] = User::whereIn('user_type',[2,3])->where('is_delete',0)->where('status',0)
            ->orderBy('user_type')->orderBy('name')->get(['id','name','last_name','user_type','admission_number']);
        $data['header_title'] = 'Issue Book';
        return view('librarian.library.issue.add', $data);
    }

    public function issueInsertLibrarian(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id','member_id' => 'required|exists:users,id',
            'issue_date' => 'required|date','due_date' => 'required|date|after_or_equal:issue_date',
            'fine_per_day' => 'nullable|integer|min:0',
        ]);
        $book = Book::findOrFail($request->book_id);
        if ($book->available <= 0)
            return back()->withInput()->withErrors(['book_id' => 'No copies available.']);
        if (BookIssue::where('book_id',$request->book_id)->where('member_id',$request->member_id)
            ->whereIn('status',['issued','overdue'])->where('is_delete',0)->exists())
            return back()->withInput()->withErrors(['member_id' => 'This member already has this book.']);

        $createData = [
            'book_id' => $request->book_id,'member_id' => $request->member_id,
            'issue_date' => $request->issue_date,'due_date' => $request->due_date,
            'fine_per_day' => $request->fine_per_day ?? 0,'fine_status' => 'none',
            'status' => 'issued','note' => $request->note,'created_by' => Auth::id(),
        ];
        if ($this->hasDamageColumns()) {
            $createData['book_condition'] = 'good';
            $createData['damage_charge']  = 0;
        }
        BookIssue::create($createData);
        $book->decrement('available');
        return redirect('librarian/library/issue/list')->with('success','Book issued successfully.');
    }

    public function issueEditLibrarian($id)
    {
        $data['getRecord']    = BookIssue::with(['book','member'])->findOrFail($id);
        $data['hasDamageCols'] = $this->hasDamageColumns();
        $data['header_title'] = 'Edit Issue Record';
        return view('librarian.library.issue.edit', $data);
    }

    public function returnFormLibrarian($id)
    {
        $data['getRecord']     = BookIssue::with(['book','member'])->findOrFail($id);
        $data['hasDamageCols'] = $this->hasDamageColumns();
        $data['header_title']  = 'Return Book';
        return view('librarian.library.issue.return', $data);
    }

    public function returnBookLibrarian($id, Request $request)
    {
        $issue = BookIssue::findOrFail($id);
        if ($issue->status === 'returned')
            return back()->with('error','This book has already been returned.');

        $returnDate   = $request->return_date ?? now()->toDateString();
        $late         = $this->daysLate($this->ds($issue->due_date), $returnDate);
        $lateFine     = $late * (int) $issue->fine_per_day;
        $condition    = 'good';
        $damageCharge = 0;
        $hasDmg       = $this->hasDamageColumns();

        if ($hasDmg) {
            $condition    = $request->book_condition ?? 'good';
            $damageCharge = (float) ($request->damage_charge ?? 0);
        }

        $totalFine = $lateFine + $damageCharge;
        $update = [
            'return_date' => $returnDate,'fine_amount' => $totalFine,
            'fine_status' => $totalFine > 0 ? 'unpaid' : 'none',
            'status' => 'returned','returned_by' => Auth::id(),
            'note'   => $request->note ?? $issue->note,'updated_at' => now(),
        ];
        if ($hasDmg) {
            $update['book_condition'] = $condition;
            $update['damage_charge']  = $damageCharge;
            $update['damage_note']    = $request->damage_note;
        }
        DB::table('book_issues')->where('id',$id)->update($update);

        if (in_array($condition,['torn','lost'])) {
            Book::where('id',$issue->book_id)->decrement('quantity');
        } else {
            Book::where('id',$issue->book_id)->increment('available');
        }

        $msg = 'Book returned.';
        if ($totalFine > 0) $msg .= ' Rs.'.number_format($totalFine,2).' fine recorded as unpaid.';
        return redirect('librarian/library/issue/list')->with('success',$msg);
    }

    public function fineListLibrarian(Request $request)               { return $this->fineList($request); }
    public function fineCollectLibrarian($id)                         { return $this->fineCollect($id); }
    public function fineCollectSubmitLibrarian($id, Request $request)  { return $this->fineCollectSubmit($id,$request); }
    public function fineWaiveLibrarian($id, Request $request)          { return $this->fineWaive($id,$request); }
    public function fineReportLibrarian(Request $request)              { return $this->fineReport($request); }
    public function myFinesLibrarian()                                 { return $this->myFines(); }

    public function issueDeleteLibrarian($id)
    {
        $issue = BookIssue::findOrFail($id);
        if ($issue->status !== 'returned')
            return back()->with('error','Cannot delete an active issue. Return the book first.');
        $issue->is_delete = 1; $issue->save();
        return redirect('librarian/library/issue/list')->with('success','Record deleted.');
    }
}