<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\BookIssue;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class LibrarianController extends Controller
{
     public function dashboard()
    {
        // Auto-mark overdue
        BookIssue::where('status', 'issued')
                 ->where('due_date', '<', now()->toDateString())
                 ->where('is_delete', 0)
                 ->update(['status' => 'overdue']);
 
        // Summary counts
        $summary = [
            'total_books'      => Book::where('is_delete', 0)->count(),
            'total_copies'     => Book::where('is_delete', 0)->sum('quantity'),
            'available_copies' => Book::where('is_delete', 0)->sum('available'),
            'total_issued'     => BookIssue::whereIn('status', ['issued', 'overdue'])->where('is_delete', 0)->count(),
            'overdue'          => BookIssue::where('status', 'overdue')->where('is_delete', 0)->count(),
        ];
 
        // Overdue issues with member + book
        $overdueBooks = BookIssue::with(['book', 'member'])
            ->where('status', 'overdue')
            ->where('is_delete', 0)
            ->orderBy('due_date')
            ->limit(8)
            ->get();
 
        // Recently issued
        $recentIssues = BookIssue::with(['book', 'member'])
            ->where('is_delete', 0)
            ->orderByDesc('created_at')
            ->limit(8)
            ->get();
 
        // Most borrowed books
        $popularBooks = Book::withCount('issues')
            ->where('is_delete', 0)
            ->orderByDesc('issues_count')
            ->limit(8)
            ->get();
 
        // Books by category (for donut chart)
        $byCategory = Book::where('is_delete', 0)
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->selectRaw('category, COUNT(*) as total')
            ->groupBy('category')
            ->orderByDesc('total')
            ->get();
 
        $data = compact(
            'summary', 'overdueBooks', 'recentIssues',
            'popularBooks', 'byCategory'
        );
        $data['header_title'] = 'Library Dashboard';
 
        return view('librarian.dashboard', $data);
    }
    public function list()
    {
        $data['getRecord']    = User::where('user_type', 6)
                                    ->where('is_delete', 0)
                                    ->orderBy('name')
                                    ->get();
        $data['header_title'] = 'Librarian List';
        return view('admin.librarian.list', $data);
    }

    public function add()
    {
        $data['header_title'] = 'Add New Librarian';
        return view('admin.librarian.add', $data);
    }

    public function insert(Request $request)
    {
        $request->validate([
            'name'               => 'required|string|max:100',
            'last_name'          => 'required|string|max:100',
            'email'              => 'required|email|unique:users,email',
            'password'           => 'required|min:5',
            'gender'             => 'required|in:Male,Female,Other',
            'mobile_number'      => 'required|string|min:6|max:15',
            'status'             => 'required|in:0,1',
            'profile_pic'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'date_of_birth'      => 'nullable|date',
            'date_of_joining'    => 'nullable|date',
            'marital_status'     => 'nullable|in:0,1',
            'qualification'      => 'nullable|string|max:255',
            'work_experience'    => 'nullable|string|max:255',
            'current_address'    => 'nullable|string|max:255',
            'permanent_address'  => 'nullable|string|max:255',
            'note'               => 'nullable|string|max:500',
        ]);

        $user                    = new User;
        $user->name              = trim($request->name);
        $user->last_name         = trim($request->last_name);
        $user->email             = trim($request->email);
        $user->password          = Hash::make($request->password);
        $user->user_type         = 6;   // ← Librarian (was wrongly 1 in your version)
        $user->gender            = $request->gender;
        $user->mobile_number     = trim($request->mobile_number);
        $user->status            = $request->status;
        $user->date_of_birth     = $request->date_of_birth;
        $user->admission_date    = $request->date_of_joining;  // reusing existing col
        $user->marital_status    = $request->marital_status;
        $user->qualification     = trim($request->qualification);
        $user->work_experience   = trim($request->work_experience);
        $user->address           = trim($request->current_address);
        $user->permanent_address = trim($request->permanent_address);
        $user->note              = trim($request->note);

        if ($request->hasFile('profile_pic')) {
            $file      = $request->file('profile_pic');
            $slugName  = Str::slug($request->name . ' ' . $request->last_name);
            $fileName  = $slugName . '-' . time() . '.' . $file->getClientOriginalExtension();
            $user->profile_pic = $file->storeAs('profile', $fileName, 'public');
        }

        $user->save();

        return redirect('admin/librarian/list')->with('success', 'Librarian successfully created.');
    }

    public function edit($id)
    {
        $data['getRecord'] = User::getSingle($id);

        if (empty($data['getRecord'])) {
            abort(404);
        }

        $data['header_title'] = 'Edit Librarian';
        return view('admin.librarian.edit', $data);
    }

    public function update($id, Request $request)
    {
        $request->validate([
            'name'               => 'required|string|max:100',
            'last_name'          => 'required|string|max:100',
            'email'              => 'required|email|unique:users,email,' . $id,
            'gender'             => 'required|in:Male,Female,Other',
            'mobile_number'      => 'required|string|min:6|max:15',
            'status'             => 'required|in:0,1',
            'profile_pic'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'date_of_birth'      => 'nullable|date',
            'date_of_joining'    => 'nullable|date',
            'marital_status'     => 'nullable|in:0,1',
            'qualification'      => 'nullable|string|max:255',
            'work_experience'    => 'nullable|string|max:255',
            'current_address'    => 'nullable|string|max:255',
            'permanent_address'  => 'nullable|string|max:255',
            'note'               => 'nullable|string|max:500',
        ]);

        $user                    = User::getSingle($id);
        $user->name              = trim($request->name);
        $user->last_name         = trim($request->last_name);
        $user->email             = trim($request->email);
        $user->gender            = $request->gender;
        $user->mobile_number     = trim($request->mobile_number);
        $user->status            = $request->status;
        $user->date_of_birth     = $request->date_of_birth;
        $user->admission_date    = $request->date_of_joining;
        $user->marital_status    = $request->marital_status;
        $user->qualification     = trim($request->qualification);
        $user->work_experience   = trim($request->work_experience);
        $user->address           = trim($request->current_address);
        $user->permanent_address = trim($request->permanent_address);
        $user->note              = trim($request->note);

        if (!empty($request->password)) {
            $user->password = Hash::make($request->password);
        }

        if ($request->hasFile('profile_pic')) {
            if (!empty($user->profile_pic) && file_exists(storage_path('app/public/' . $user->profile_pic))) {
                unlink(storage_path('app/public/' . $user->profile_pic));
            }
            $file      = $request->file('profile_pic');
            $slugName  = Str::slug($request->name . ' ' . $request->last_name);
            $fileName  = $slugName . '-' . time() . '.' . $file->getClientOriginalExtension();
            $user->profile_pic = $file->storeAs('profile', $fileName, 'public');
        }

        $user->save();

        return redirect('admin/librarian/list')->with('success', 'Librarian successfully updated.');
    }

    public function delete($id)
    {
        $user            = User::getSingle($id);
        $user->is_delete = 1;
        $user->save();

        return redirect('admin/librarian/list')->with('success', 'Librarian successfully deleted.');
    }
}
