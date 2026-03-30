<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AccountantController extends Controller
{
    public function list()
    {
        $data['getRecord']    = User::where('user_type', 5)
                                    ->where('is_delete', 0)
                                    ->orderBy('name')
                                    ->get();
                                    // dd($data['getRecord']);
        $data['header_title'] = 'Accountant List';
        return view('admin.accountant.list', $data);
    }

    public function add()
    {
        $data['header_title'] = 'Add New Accountant';
        return view('admin.accountant.add', $data);
    }

    public function insert(Request $request)
    {
        $request->validate([
            'name'               => 'required|string|max:100',
            'middle_name'        => 'nullable|max:100',
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
        $user->middle_name       = trim($request->middle_name);
        $user->last_name         = trim($request->last_name);
        $user->email             = trim($request->email);
        $user->password          = Hash::make($request->password);
        $user->user_type         = 5;   // ← Accountant (was wrongly 1 in your version)
        $user->gender            = $request->gender;
        $user->mobile_number     = trim($request->mobile_number);
        $user->status            = $request->status;
        $user->date_of_birth     = $request->date_of_birth;
        $user->date_of_joining    = $request->date_of_joining;  // reusing existing col
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

        return redirect('admin/accountant/list')->with('success', 'Accountant successfully created.');
    }

    public function edit($id)
    {
        $data['getRecord'] = User::getSingle($id);

        if (empty($data['getRecord'])) {
            abort(404);
        }

        $data['header_title'] = 'Edit Accountant';
        return view('admin.accountant.edit', $data);
    }

    public function update($id, Request $request)
    {
        $request->validate([
            'name'               => 'required|string|max:100',
            'middle_name'        => 'nullable|max:100',
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
        $user->middle_name       = trim($request->middle_name);
        $user->last_name         = trim($request->last_name);
        $user->email             = trim($request->email);
        $user->gender            = $request->gender;
        $user->mobile_number     = trim($request->mobile_number);
        $user->status            = $request->status;
        $user->date_of_birth     = $request->date_of_birth;
        $user->date_of_joining    = $request->date_of_joining;
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

        return redirect('admin/accountant/list')->with('success', 'Accountant successfully updated.');
    }

    public function delete($id)
    {
        $user            = User::getSingle($id);
        $user->is_delete = 1;
        $user->save();

        return redirect('admin/accountant/list')->with('success', 'Accountant successfully deleted.');
    }
}