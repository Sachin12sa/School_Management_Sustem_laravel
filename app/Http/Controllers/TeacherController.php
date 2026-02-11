<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Str;
use Auth;
use Hash;

class TeacherController extends Controller
{
    public function list(){
        // Ensure you create the getTeacher() scope in your User model
        $data['getRecord'] = User::getTeacher(); 
        $data['header_title'] = 'Teacher List';
        return view('admin.teacher.list', $data);
    }

    public function add(){
        $data['header_title'] = 'Add New Teacher';
        return view('admin.teacher.add', $data);
    }

    public function insert(Request $request)
    {
        $request->validate([
            'name'              => 'required|string|max:100',
            'last_name'         => 'required|string|max:100',
            'email'             => 'required|email|unique:users,email',
            'password'          => 'required|min:5',
            'gender'            => 'required|in:Male,Female,Other',
            'date_of_birth'     => 'required|date',
            'date_of_joining'   => 'required|date', // Replaces admission_date
            'mobile_number'     => 'required|string|max:15|min:6',
            'marital_status'    => 'nullable|in:0,1',
            'current_address'   => 'nullable|string|max:255',
            'permanent_address' => 'nullable|string|max:255',
            'qualification'     => 'nullable|string|max:255',
            'work_experience'   => 'nullable|string|max:255',
            'note'              => 'nullable|string|max:500',
            'status'            => 'required|in:0,1',
            'profile_pic'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $teacher = new User;
        $teacher->name = trim($request->name);
        $teacher->last_name = trim($request->last_name);
        $teacher->gender = $request->gender;
        $teacher->date_of_birth = $request->date_of_birth;
        $teacher->admission_date = $request->date_of_joining; // Saving Joining Date to admission_date col
        
        $teacher->mobile_number = trim($request->mobile_number);
        $teacher->marital_status = trim($request->marital_status);
        $teacher->address = trim($request->current_address); // Assuming 'address' col for current
        $teacher->permanent_address = trim($request->permanent_address);
        $teacher->qualification = trim($request->qualification);
        $teacher->work_experience = trim($request->work_experience);
        $teacher->note = trim($request->note);
        
        $teacher->status = $request->status;
        $teacher->email = trim($request->email);
        $teacher->password = Hash::make($request->password);
        $teacher->user_type = 2; // 2 is typically Teacher

        if ($request->hasFile('profile_pic')) {
            $file = $request->file('profile_pic');
            $extension = $file->getClientOriginalExtension();
            $slugName = Str::slug($request->name . ' ' . $request->last_name);
            $fileName = $slugName . '-' . time() . '.' . $extension;
            $teacher->profile_pic = $file->storeAs('profile', $fileName, 'public');
        }

        $teacher->save();

        return redirect('admin/teacher/list')->with('success', 'Teacher Successfully Added');
    }

    public function edit($id){
        $data['getRecord'] = User::getSingle($id);
        if(!empty($data['getRecord'])) {
            $data['header_title'] = 'Edit Teacher';
            return view('admin.teacher.edit', $data);
        } else {
            abort(404);
        }
    }

    public function update($id, Request $request)
    {
        $request->validate([
            'email'             => 'required|email|unique:users,email,'.$id,
            'name'              => 'required|string|max:100',
            'last_name'         => 'required|string|max:100',
            'gender'            => 'required|in:Male,Female,Other',
            'date_of_birth'     => 'required|date',
            'date_of_joining'   => 'required|date',
            'mobile_number'     => 'required|string|max:15|min:8',
            'marital_status'    => 'nullable|in:0,1',
            'current_address'   => 'nullable|string|max:255',
            'permanent_address' => 'nullable|string|max:255',
            'qualification'     => 'nullable|string|max:255',
            'work_experience'   => 'nullable|string|max:255',
            'note'              => 'nullable|string|max:500',
            'status'            => 'required|in:0,1',
            'profile_pic'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $teacher = User::getSingle($id);
        $teacher->name = trim($request->name);
        $teacher->last_name = trim($request->last_name);
        $teacher->gender = $request->gender;
        $teacher->date_of_birth = $request->date_of_birth;
        $teacher->admission_date = $request->date_of_joining;

        $teacher->mobile_number = trim($request->mobile_number);
        $teacher->marital_status = trim($request->marital_status);
        $teacher->address = trim($request->current_address);
        $teacher->permanent_address = trim($request->permanent_address);
        $teacher->qualification = trim($request->qualification);
        $teacher->work_experience = trim($request->work_experience);
        $teacher->note = trim($request->note);

        $teacher->status = $request->status;
        $teacher->email = trim($request->email);
        
        if(!empty($request->password)) {
            $teacher->password = Hash::make($request->password);
        }
        // $teacher->user_type = 2; // Usually don't need to update user_type on edit

        if ($request->hasFile('profile_pic')) {
            if (!empty($teacher->profile_pic) && file_exists(storage_path('app/public/' . $teacher->profile_pic))) {
                unlink(storage_path('app/public/' . $teacher->profile_pic));
            }
            $file = $request->file('profile_pic');
            $extension = $file->getClientOriginalExtension();
            $slugName = Str::slug($request->name . ' ' . $request->last_name);
            $fileName = $slugName . '-' . time() . '.' . $extension;
            $teacher->profile_pic = $file->storeAs('profile', $fileName, 'public');
        }

        $teacher->save();

        return redirect('admin/teacher/list')->with('success', 'Teacher Successfully Updated');
    }

    public function delete($id){
        $user = User::getSingle($id);
        $user->is_delete = 1;
        $user->save();
        return redirect('admin/teacher/list')->with('success','Teacher Successfully Deleted');
    }
}