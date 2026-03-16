<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\BookIssue;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class LibraryController extends Controller
{
    // ═══════════════════════════════════════════════════════════════
    //  BOOKS — CRUD
    // ═══════════════════════════════════════════════════════════════

    public function bookList(Request $request)
    {
        $filters = $request->only(['title', 'author', 'category', 'status']);
        $data['getRecord']     = Book::getRecord($filters);
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

        $book              = new Book;
        $book->title       = trim($request->title);
        $book->author      = trim($request->author);
        $book->isbn        = trim($request->isbn);
        $book->publisher   = trim($request->publisher);
        $book->edition     = trim($request->edition);
        $book->publish_year = $request->publish_year;
        $book->category    = trim($request->category);
        $book->rack_number = trim($request->rack_number);
        $book->quantity    = $request->quantity;
        $book->available   = $request->quantity;
        $book->description = $request->description;
        $book->status      = $request->status;
        $book->created_by  = Auth::id();

        if ($request->hasFile('cover_image')) {
            $file            = $request->file('cover_image');
            $slug            = Str::slug($request->title);
            $fileName        = $slug . '-' . time() . '.' . $file->getClientOriginalExtension();
            $book->cover_image = $file->storeAs('library/covers', $fileName, 'public');
        }

        $book->save();
        return redirect('admin/library/book/list')->with('success', 'Book added successfully.');
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
            'isbn'        => 'nullable|string|max:20|unique:books,isbn,' . $id,
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
            if (!empty($book->cover_image) && file_exists(storage_path('app/public/' . $book->cover_image))) {
                unlink(storage_path('app/public/' . $book->cover_image));
            }
            $file            = $request->file('cover_image');
            $slug            = Str::slug($request->title);
            $fileName        = $slug . '-' . time() . '.' . $file->getClientOriginalExtension();
            $book->cover_image = $file->storeAs('library/covers', $fileName, 'public');
        }

        $book->save();
        return redirect('admin/library/book/list')->with('success', 'Book updated successfully.');
    }

    public function bookDelete($id)
    {
        $book            = Book::getSingle($id);
        $book->is_delete = 1;
        $book->save();
        return redirect('admin/library/book/list')->with('success', 'Book deleted.');
    }

    // ═══════════════════════════════════════════════════════════════
    //  BOOK ISSUES
    // ═══════════════════════════════════════════════════════════════

    public function issueList(Request $request)
    {
        BookIssue::where('status', 'issued')
            ->where('due_date', '<', now()->toDateString())
            ->where('is_delete', 0)
            ->update(['status' => 'overdue']);

        $filters           = $request->only(['member_name', 'book_id', 'status']);
        $data['getRecord'] = BookIssue::getRecord($filters);
        $data['getBooks']  = Book::where('is_delete', 0)->orderBy('title')->get();
        $data['summary']   = Book::getSummary();
        $data['header_title'] = 'Library — Issue Records';
        return view('admin.library.issue.list', $data);
    }

    public function issueAdd()
    {
        $data['getBooks']   = Book::getAvailable();
        $data['getMembers'] = User::whereIn('user_type', [2, 3])
            ->where('is_delete', 0)
            ->where('status', 0)
            ->orderBy('user_type')
            ->orderBy('name')
            ->get(['id', 'name', 'last_name', 'user_type', 'admission_number']);
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
        if ($book->available <= 0) {
            return back()->withInput()->withErrors(['book_id' => 'No copies available.']);
        }

        $alreadyIssued = BookIssue::where('book_id', $request->book_id)
            ->where('member_id', $request->member_id)
            ->whereIn('status', ['issued', 'overdue'])
            ->where('is_delete', 0)
            ->exists();
        if ($alreadyIssued) {
            return back()->withInput()->withErrors(['member_id' => 'This member already has this book.']);
        }

        BookIssue::create([
            'book_id'      => $request->book_id,
            'member_id'    => $request->member_id,
            'issue_date'   => $request->issue_date,
            'due_date'     => $request->due_date,
            'fine_per_day' => $request->fine_per_day ?? 0,
            'fine_status'  => 'none',
            'status'       => 'issued',
            'note'         => $request->note,
            'created_by'   => Auth::id(),
        ]);

        $book->decrement('available');
        return redirect('admin/library/issue/list')->with('success', 'Book issued successfully.');
    }

    public function issueEdit($id)
    {
        $data['getRecord']    = BookIssue::with(['book', 'member'])->findOrFail($id);
        $data['header_title'] = 'Edit Issue Record';
        return view('admin.library.issue.edit', $data);
    }

    public function issueDelete($id)
    {
        $issue = BookIssue::findOrFail($id);
        if ($issue->status !== 'returned') {
            return back()->with('error', 'Cannot delete an active issue. Return the book first.');
        }
        $issue->is_delete = 1;
        $issue->save();
        return redirect('admin/library/issue/list')->with('success', 'Record deleted.');
    }

    public function returnForm($id)
    {
        $data['getRecord']    = BookIssue::with(['book', 'member'])->findOrFail($id);
        $data['header_title'] = 'Return Book';
        return view('admin.library.issue.return', $data);
    }

    // ── RETURN BOOK ───────────────────────────────────────────────
    // ONLY marks book as returned + calculates fine.
    // Fine payment is handled SEPARATELY from Library Fines page.
    private function parseDateSafe($date): string
    {
        if ($date instanceof Carbon) {
            return $date->format('Y-m-d');
        }
        return Carbon::parse($date)->format('Y-m-d');
    }

    public function returnBook($id, Request $request)
    {
        $issue = BookIssue::findOrFail($id);

        if ($issue->status === 'returned') {
            return back()->with('error', 'This book has already been returned.');
        }

        $returnDate = $request->return_date ?? now()->toDateString();
        $due        = Carbon::createFromFormat('Y-m-d', $this->parseDateSafe($issue->due_date))->startOfDay();
        $returned   = Carbon::createFromFormat('Y-m-d', $returnDate)->startOfDay();
        $daysLate   = $returned->gt($due) ? (int) $returned->diffInDays($due) : 0;
        $fineAmount = $daysLate * $issue->fine_per_day;

        $issue->return_date = $returnDate;
        $issue->fine_amount = $fineAmount;
        $issue->status      = 'returned';
        $issue->returned_by = Auth::id();
        $issue->note        = $request->note;
        $issue->fine_status = $fineAmount > 0 ? 'unpaid' : 'none';
        $issue->save();

        $issue->book->increment('available');

        $msg = 'Book returned successfully.';
        if ($fineAmount > 0) {
            $msg .= ' Fine of Rs. ' . number_format($fineAmount, 2) . ' is pending — collect from Library Fines.';
        }

        return redirect('admin/library/issue/list')->with('success', $msg);
    }

    // ═══════════════════════════════════════════════════════════════
    //  LIBRARY DASHBOARD
    // ═══════════════════════════════════════════════════════════════

    public function dashboard()
    {
        BookIssue::where('status', 'issued')
            ->where('due_date', '<', now()->toDateString())
            ->where('is_delete', 0)
            ->update(['status' => 'overdue']);

        $summary = [
            'total_books'      => Book::where('is_delete', 0)->count(),
            'total_copies'     => Book::where('is_delete', 0)->sum('quantity'),
            'available_copies' => Book::where('is_delete', 0)->sum('available'),
            'total_issued'     => BookIssue::whereIn('status', ['issued', 'overdue'])->where('is_delete', 0)->count(),
            'overdue'          => BookIssue::where('status', 'overdue')->where('is_delete', 0)->count(),
        ];

        $overdueBooks = BookIssue::with(['book', 'member'])
            ->where('status', 'overdue')
            ->where('is_delete', 0)
            ->orderBy('due_date')
            ->limit(8)
            ->get();

        $recentIssues = BookIssue::with(['book', 'member'])
            ->where('is_delete', 0)
            ->orderByDesc('created_at')
            ->limit(8)
            ->get();

        $popularBooks = Book::withCount('issues')
            ->where('is_delete', 0)
            ->orderByDesc('issues_count')
            ->limit(8)
            ->get();

        $byCategory = Book::where('is_delete', 0)
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->selectRaw('category, COUNT(*) as total')
            ->groupBy('category')
            ->orderByDesc('total')
            ->get();

        $data = compact('summary', 'overdueBooks', 'recentIssues', 'popularBooks', 'byCategory');
        $data['header_title'] = 'Library Dashboard';
        return view('admin.library.dashboard', $data);
    }

    // ═══════════════════════════════════════════════════════════════
    //  FINES
    // ═══════════════════════════════════════════════════════════════

    public function fineList(Request $request)
    {
        // Auto-mark overdue — ONLY 'issued', never touch 'returned'
        BookIssue::where('status', 'issued')
            ->where('due_date', '<', now()->toDateString())
            ->where('is_delete', 0)
            ->update(['status' => 'overdue']);

        $tab        = $request->get('tab', 'unpaid');
        $memberName = $request->get('member_name');
        $memberType = $request->get('member_type');

        $query = BookIssue::join('books as b', 'b.id', '=', 'book_issues.book_id')
            ->join('users as m', 'm.id', '=', 'book_issues.member_id')
            ->select(
                'book_issues.*',
                'b.title as book_title',
                'm.name as member_name',
                'm.last_name as member_last_name',
                'm.user_type as member_type',
                'm.admission_number'
            )
            ->where('book_issues.is_delete', 0);

        if ($tab === 'unpaid') {
            // Returned books whose fine hasn't been paid yet
            // Also catch NULL fine_status for records created before migration
            $query->where('book_issues.status', 'returned')
                  ->where('book_issues.fine_amount', '>', 0)
                  ->where(function ($q) {
                      $q->where('book_issues.fine_status', 'unpaid')
                        ->orWhereNull('book_issues.fine_status');
                  });

        } elseif ($tab === 'overdue') {
            // Books still out AND past due AND fine not yet settled
            $query->where('book_issues.status', 'overdue')
                  ->where('book_issues.fine_per_day', '>', 0)
                  ->where(function ($q) {
                      // NULL fine_status also belongs here (pre-migration records)
                      $q->whereNull('book_issues.fine_status')
                        ->orWhereNotIn('book_issues.fine_status', ['waived', 'paid']);
                  });

        } elseif ($tab === 'paid') {
            $query->where('book_issues.fine_status', 'paid');

        } elseif ($tab === 'waived') {
            $query->where('book_issues.fine_status', 'waived');
        }

        if ($memberName) {
            $query->where(function ($q) use ($memberName) {
                $q->where('m.name',       'like', '%' . $memberName . '%')
                  ->orWhere('m.last_name', 'like', '%' . $memberName . '%');
            });
        }
        if ($memberType) {
            $query->where('m.user_type', $memberType);
        }

        $data['getRecord']    = $query->with('fineCollector')
                                      ->orderByDesc('book_issues.due_date')
                                      ->paginate(15);
        $data['fineSummary']  = BookIssue::getFineSummary();
        $data['header_title'] = 'Library Fines';

        return view('admin.library.fine.list', $data);
    }

    public function fineCollect($id)
    {
        $data['getRecord']    = BookIssue::with(['book', 'member'])->findOrFail($id);
        $data['header_title'] = 'Collect Fine Payment';
        return view('admin.library.fine.collect', $data);
    }

    public function fineCollectSubmit($id, Request $request)
    {
        $request->validate([
            'fine_payment_method' => 'required|in:cash,bank,online',
            'fine_paid_at'        => 'required|date',
        ]);

        $issue = BookIssue::findOrFail($id);

        if ($issue->fine_status === 'paid') {
            return back()->with('error', 'This fine has already been paid.');
        }

        // For overdue books not yet returned — lock in today's accrued fine
        if ($issue->status === 'overdue') {
            $due  = Carbon::createFromFormat('Y-m-d', $this->parseDateSafe($issue->due_date))->startOfDay();
            $days = (int) $due->diffInDays(now()->startOfDay());
            $issue->fine_amount = $days * $issue->fine_per_day;
        }

        $issue->fine_status         = 'paid';
        $issue->fine_payment_method = $request->fine_payment_method;
        $issue->fine_paid_at        = $request->fine_paid_at;
        $issue->fine_collected_by   = Auth::id();
        $issue->fine_note           = $request->fine_note;
        $issue->save();

        return redirect('admin/library/fine/list?tab=paid')
            ->with('success', 'Fine of Rs. ' . number_format($issue->fine_amount, 2) . ' collected successfully.');
    }

    public function fineWaive($id, Request $request)
    {
        $issue = BookIssue::findOrFail($id);

        if ($issue->fine_status === 'paid') {
            return back()->with('error', 'Fine already paid — cannot waive.');
        }

        $issue->fine_status       = 'waived';
        $issue->fine_note         = $request->reason ?? 'Waived by admin';
        $issue->fine_collected_by = Auth::id();
        $issue->save();

        return redirect('admin/library/fine/list?tab=waived')
            ->with('success', 'Fine waived successfully.');
    }

    public function fineReport(Request $request)
    {
        $dateFrom = $request->get('date_from', now()->startOfMonth()->toDateString());
        $dateTo   = $request->get('date_to', now()->toDateString());

        $base = BookIssue::join('books as b', 'b.id', '=', 'book_issues.book_id')
            ->join('users as m', 'm.id', '=', 'book_issues.member_id')
            ->where('book_issues.fine_amount', '>', 0)
            ->where('book_issues.is_delete', 0)
            ->whereBetween('book_issues.return_date', [$dateFrom, $dateTo]);

        $report = [
            'total_records' => (clone $base)->count(),
            'collected'     => (clone $base)->where('book_issues.fine_status', 'paid')->sum('book_issues.fine_amount'),
            'unpaid'        => (clone $base)->where('book_issues.fine_status', 'unpaid')->sum('book_issues.fine_amount'),
            'waived'        => (clone $base)->where('book_issues.fine_status', 'waived')->sum('book_issues.fine_amount'),
            'by_method'     => [
                'cash'   => (clone $base)->where('book_issues.fine_payment_method', 'cash')->sum('book_issues.fine_amount'),
                'bank'   => (clone $base)->where('book_issues.fine_payment_method', 'bank')->sum('book_issues.fine_amount'),
                'online' => (clone $base)->where('book_issues.fine_payment_method', 'online')->sum('book_issues.fine_amount'),
            ],
            'top_members' => (clone $base)
                ->select(
                    'm.name as member_name', 'm.last_name as member_last_name',
                    'm.user_type as member_type',
                    DB::raw('COUNT(*) as fine_count'),
                    DB::raw('SUM(book_issues.fine_amount) as total_fine'),
                    DB::raw('SUM(CASE WHEN book_issues.fine_status = "paid" THEN book_issues.fine_amount ELSE 0 END) as paid_fine')
                )
                ->groupBy('m.id', 'm.name', 'm.last_name', 'm.user_type')
                ->orderByDesc('total_fine')
                ->limit(10)
                ->get(),
        ];

        $transactions = (clone $base)
            ->select(
                'book_issues.*',
                'b.title as book_title',
                'm.name as member_name', 'm.last_name as member_last_name',
                'm.user_type as member_type'
            )
            ->orderByDesc('book_issues.return_date')
            ->paginate(20);

        $data = compact('report', 'transactions', 'dateFrom', 'dateTo');
        $data['header_title'] = 'Fine Report';
        return view('admin.library.fine.report', $data);
    }

    // ═══════════════════════════════════════════════════════════════
    //  STUDENT & TEACHER
    // ═══════════════════════════════════════════════════════════════

    public function myBooks()
    {
        $member_id = Auth::id();

        BookIssue::where('member_id', $member_id)
            ->where('status', 'issued')
            ->where('due_date', '<', now()->toDateString())
            ->where('is_delete', 0)
            ->update(['status' => 'overdue']);

        $data['getRecord']    = BookIssue::getMemberBooks($member_id);
        $data['header_title'] = 'My Books';

        $view = Auth::user()->user_type == 2 ? 'teacher.my_book' : 'student.my_book';
        return view($view, $data);
    }

    public function myFines()
    {
        $member_id = Auth::id();

        BookIssue::where('member_id', $member_id)
            ->where('status', 'issued')
            ->where('due_date', '<', now()->toDateString())
            ->where('is_delete', 0)
            ->update(['status' => 'overdue']);

        $data['getRecord'] = BookIssue::getMemberFines($member_id);

        $data['totalUnpaid'] = $data['getRecord']
            ->where('fine_status', 'unpaid')
            ->sum('fine_amount');

        $data['totalAccruing'] = $data['getRecord']
            ->where('status', 'overdue')
            ->sum(fn($i) => Carbon::parse($this->parseDateSafe($i->due_date))->diffInDays(now()) * $i->fine_per_day);

        $data['header_title'] = 'My Library Fines';

        $view = Auth::user()->user_type == 2
            ? 'teacher.my_library_fines'
            : 'student.my_library_fines';

        return view($view, $data);
    }

    // ═══════════════════════════════════════════════════════════════
    //  LIBRARIAN — mirrors admin methods with librarian views/redirects
    // ═══════════════════════════════════════════════════════════════

    public function bookListLibrarian(Request $request)
    {
        $filters = $request->only(['title', 'author', 'category', 'status']);
        $data['getRecord']     = Book::getRecord($filters);
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
            'title'       => 'required|string|max:255',
            'author'      => 'required|string|max:255',
            'isbn'        => 'nullable|string|max:20|unique:books,isbn',
            'quantity'    => 'required|integer|min:1',
            'status'      => 'required|in:0,1',
            'cover_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $book              = new Book;
        $book->title       = trim($request->title);
        $book->author      = trim($request->author);
        $book->isbn        = trim($request->isbn);
        $book->publisher   = trim($request->publisher);
        $book->edition     = trim($request->edition);
        $book->publish_year = $request->publish_year;
        $book->category    = trim($request->category);
        $book->rack_number = trim($request->rack_number);
        $book->quantity    = $request->quantity;
        $book->available   = $request->quantity;
        $book->description = $request->description;
        $book->status      = $request->status;
        $book->created_by  = Auth::id();

        if ($request->hasFile('cover_image')) {
            $file            = $request->file('cover_image');
            $slug            = Str::slug($request->title);
            $fileName        = $slug . '-' . time() . '.' . $file->getClientOriginalExtension();
            $book->cover_image = $file->storeAs('library/covers', $fileName, 'public');
        }

        $book->save();
        return redirect('librarian/library/book/list')->with('success', 'Book added successfully.');
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
            'title'       => 'required|string|max:255',
            'author'      => 'required|string|max:255',
            'isbn'        => 'nullable|string|max:20|unique:books,isbn,' . $id,
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
            if (!empty($book->cover_image) && file_exists(storage_path('app/public/' . $book->cover_image))) {
                unlink(storage_path('app/public/' . $book->cover_image));
            }
            $file            = $request->file('cover_image');
            $slug            = Str::slug($request->title);
            $fileName        = $slug . '-' . time() . '.' . $file->getClientOriginalExtension();
            $book->cover_image = $file->storeAs('library/covers', $fileName, 'public');
        }

        $book->save();
        return redirect('librarian/library/book/list')->with('success', 'Book updated successfully.');
    }

    public function bookDeleteLibrarian($id)
    {
        $book            = Book::getSingle($id);
        $book->is_delete = 1;
        $book->save();
        return redirect('librarian/library/book/list')->with('success', 'Book deleted.');
    }

    public function issueListLibrarian(Request $request)
    {
        BookIssue::where('status', 'issued')
            ->where('due_date', '<', now()->toDateString())
            ->where('is_delete', 0)
            ->update(['status' => 'overdue']);

        $filters           = $request->only(['member_name', 'book_id', 'status']);
        $data['getRecord'] = BookIssue::getRecord($filters);
        $data['getBooks']  = Book::where('is_delete', 0)->orderBy('title')->get();
        $data['summary']   = Book::getSummary();
        $data['header_title'] = 'Library — Issue Records';
        return view('librarian.library.issue.list', $data);
    }

    public function issueAddLibrarian()
    {
        $data['getBooks']   = Book::getAvailable();
        $data['getMembers'] = User::whereIn('user_type', [2, 3])
            ->where('is_delete', 0)
            ->where('status', 0)
            ->orderBy('user_type')
            ->orderBy('name')
            ->get(['id', 'name', 'last_name', 'user_type', 'admission_number']);
        $data['header_title'] = 'Issue Book';
        return view('librarian.library.issue.add', $data);
    }

    public function issueInsertLibrarian(Request $request)
    {
        $request->validate([
            'book_id'      => 'required|exists:books,id',
            'member_id'    => 'required|exists:users,id',
            'issue_date'   => 'required|date',
            'due_date'     => 'required|date|after_or_equal:issue_date',
            'fine_per_day' => 'nullable|integer|min:0',
        ]);

        $book = Book::findOrFail($request->book_id);
        if ($book->available <= 0) {
            return back()->withInput()->withErrors(['book_id' => 'No copies available.']);
        }

        $alreadyIssued = BookIssue::where('book_id', $request->book_id)
            ->where('member_id', $request->member_id)
            ->whereIn('status', ['issued', 'overdue'])
            ->where('is_delete', 0)
            ->exists();
        if ($alreadyIssued) {
            return back()->withInput()->withErrors(['member_id' => 'This member already has this book.']);
        }

        BookIssue::create([
            'book_id'      => $request->book_id,
            'member_id'    => $request->member_id,
            'issue_date'   => $request->issue_date,
            'due_date'     => $request->due_date,
            'fine_per_day' => $request->fine_per_day ?? 0,
            'fine_status'  => 'none',
            'status'       => 'issued',
            'note'         => $request->note,
            'created_by'   => Auth::id(),
        ]);

        $book->decrement('available');
        return redirect('librarian/library/issue/list')->with('success', 'Book issued successfully.');
    }

    public function issueEditLibrarian($id)
    {
        $data['getRecord']    = BookIssue::with(['book', 'member'])->findOrFail($id);
        $data['header_title'] = 'Edit Issue Record';
        return view('librarian.library.issue.edit', $data);
    }

    public function returnFormLibrarian($id)
    {
        $data['getRecord']    = BookIssue::with(['book', 'member'])->findOrFail($id);
        $data['header_title'] = 'Return Book';
        return view('librarian.library.issue.return', $data);
    }

    public function returnBookLibrarian($id, Request $request)
    {
        $issue = BookIssue::findOrFail($id);

        if ($issue->status === 'returned') {
            return back()->with('error', 'This book has already been returned.');
        }

        $returnDate = $request->return_date ?? now()->toDateString();
        $due        = Carbon::createFromFormat('Y-m-d', $this->parseDateSafe($issue->due_date))->startOfDay();
        $returned   = Carbon::createFromFormat('Y-m-d', $returnDate)->startOfDay();
        $daysLate   = $returned->gt($due) ? (int) $returned->diffInDays($due) : 0;
        $fineAmount = $daysLate * $issue->fine_per_day;

        $issue->return_date = $returnDate;
        $issue->fine_amount = $fineAmount;
        $issue->status      = 'returned';
        $issue->returned_by = Auth::id();
        $issue->note        = $request->note;
        $issue->fine_status = $fineAmount > 0 ? 'unpaid' : 'none';
        $issue->save();

        $issue->book->increment('available');

        $msg = 'Book returned successfully.';
        if ($fineAmount > 0) {
            $msg .= ' Fine of Rs. ' . number_format($fineAmount, 2) . ' is pending — collect from Library Fines.';
        }

        return redirect('librarian/library/issue/list')->with('success', $msg);
    }

    public function fineListLibrarian(Request $request)
    {
        // Reuse the same fineList logic — same view
        return $this->fineList($request);
    }

    public function fineCollectLibrarian($id)
    {
        return $this->fineCollect($id);
    }

    public function fineCollectSubmitLibrarian($id, Request $request)
    {
        return $this->fineCollectSubmit($id, $request);
    }

    public function fineWaiveLibrarian($id, Request $request)
    {
        return $this->fineWaive($id, $request);
    }

    public function fineReportLibrarian(Request $request)
    {
        return $this->fineReport($request);
    }

    public function myFinesLibrarian()
    {
        return $this->myFines();
    }

    public function issueDeleteLibrarian($id)
    {
        $issue = BookIssue::findOrFail($id);
        if ($issue->status !== 'returned') {
            return back()->with('error', 'Cannot delete an active issue. Return the book first.');
        }
        $issue->is_delete = 1;
        $issue->save();
        return redirect('librarian/library/issue/list')->with('success', 'Record deleted.');
    }
}