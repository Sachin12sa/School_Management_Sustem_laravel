<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ClassModel;
use Str;
use Auth;
use Hash;
class ParentController extends Controller
{
       public function list(){
        $data['getRecord'] = User::getParent();
        $data['header_title']= 'parent List';
        return view('admin.parent.list',$data);
    }
     public function add(){
        $data['header_title']= 'Add New parent';
        return view('admin.parent.add',$data);
    }
    public function insert(Request $request)
            {
             
                $request->validate([
                    'name'              => 'required|string|max:100',
                    'last_name'         => 'required|string|max:100',
                    'gender'            => 'required|in:Male,Female,Other',
                    'email'             => 'required|email|unique:users,email',
                    'status'            => 'required|in:0,1',
                    'profile_pic'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
                    'mobile_number'     => 'required|string|max:15|min:8',
                    'blood_group'       => 'nullable|string|max:10',
                    'occupation'          => 'nullable|string|max:10',
                    'address'            => 'nullable|string|max:10',
                ]);

                $student = new User;
                $student->name = trim($request->name);
                $student->last_name = trim($request->last_name);
                $student->gender = $request->gender;
                $student->address = $request->address;
                $student->occupation = trim($request->occupation);
                $student->mobile_number = trim($request->mobile_number);
                $student->status = $request->status;
                $student->blood_group = trim($request->blood_group);
                $student->email = trim($request->email);
                $student->password = Hash::make($request->password);
                $student->user_type = 4;

                if ($request->hasFile('profile_pic')) {
                    $file = $request->file('profile_pic');
                    $extension = $file->getClientOriginalExtension();
                    $slugName = Str::slug($request->name . ' ' . $request->last_name);
                    $fileName = $slugName . '-' . time() . '.' . $extension;
                    $student->profile_pic = $file->storeAs('profile', $fileName, 'public');
                }

                $student->save();

                return redirect('admin/parent/list')->with('success', 'Parent Successfully Added');
            }
            // edit
    public function edit($id){
        $data['getRecord'] = User::getSingle($id);
        if(!empty($data['getRecord']))
            {
                $data['header_title']= 'Edit  Parent';
        return view('admin.parent.edit',$data);
            }else{
                abort(404);
            }

      } 
            //update
    public function update($id, Request $request)
       {        
            $request->validate([
                
                    'name'              => 'required|string|max:100',
                    'last_name'         => 'required|string|max:100',
                    'gender'            => 'required|in:Male,Female,Other',
                    'status'            => 'required|in:0,1',
                    'profile_pic'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
                    'mobile_number'     => 'required|string|max:15|min:8',
                    'blood_group'       => 'nullable|string|max:10',
                    'occupation'          => 'nullable|string|max:10',
                    'address'            => 'nullable|string|max:10',
                ]);

                $student = User::getSingle($id);
                
                $student->name = trim($request->name);
                $student->last_name = trim($request->last_name);
                $student->gender = $request->gender;
                $student->address = $request->address;
                $student->occupation = trim($request->occupation);
                $student->blood_group = trim($request->blood_group);
                $student->mobile_number = trim($request->mobile_number);
                $student->status = $request->status;
                $student->email = trim($request->email);
                if(!empty($request->password))
                    {
                        $student->password = Hash::make($request->password);

                    }
                $student->user_type = 4;

                if ($request->hasFile('profile_pic')) {
                    if (!empty($student->profile_pic) && file_exists(storage_path('app/public/' . $student->profile_pic))) {
                            unlink(storage_path('app/public/' . $student->profile_pic));
                        }
                    $file = $request->file('profile_pic');
                    $extension = $file->getClientOriginalExtension();
                    $slugName = Str::slug($request->name . ' ' . $request->last_name);
                    $fileName = $slugName . '-' . time() . '.' . $extension;
                    $student->profile_pic = $file->storeAs('profile', $fileName, 'public');
                }

                $student->save();

                return redirect('admin/parent/list')->with('success', 'Parent Successfully Updated');
       }
    //    Delete
    public function delete($id){

        $user = User::getSingle($id);
        $user->is_delete = 1;
        $user->save();
        return redirect('admin/parent/list')->with('success','Parent Successfully updated');
    }

    public function myStudent($id){
        $data['getParent']= User::getSingle($id);
        $data['parent_id'] = $id;
        $data['getSearchStudent'] = User::getSearchStudent(); // students from search
        $data['getRecord'] = User::getMyStudent($id); // assigned students
        $data['header_title'] = 'Assign Parent to Student';
        return view('admin.parent.my_student', $data);
    }

    public function assignStudentParent($parent_id, $student_id)
{
        $student = User::getSingle($student_id);
        $student -> parent_id = $parent_id;
        $student -> save();
        return redirect()->back()->with('success','Studdent Successfully Assigned.');


    }

    public function assignStudentParentDelete($student_id)
    {
        $student = User::getSingle($student_id);
        $student -> parent_id = null;
        $student -> save();
        return redirect()->back()->with('success','Student Successfully Deleted.');


    }
    // Parents side

    public function myStudentParent()
    {
        $id = Auth::user()->id;
        $data['getRecord'] = User::getMyStudent($id); // assigned students
        $data['header_title'] = '  My Student ';
        return view('parent.my_student', $data);
    }
}

