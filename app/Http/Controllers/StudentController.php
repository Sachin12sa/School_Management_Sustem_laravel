<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ClassModel;
use Str;
use Auth;
use Hash;

class StudentController extends Controller
{
     public function list(){
        $data['getRecord'] = User::getStudent();
        $data['header_title']= 'Student List';
        return view('admin.student.list',$data);
    }
     public function add(){
        $data['getClass'] = ClassModel::getClass();
        $data['header_title']= 'Add New Student';
        return view('admin.student.add',$data);
    }

  public function insert(Request $request)
            {
             
                $request->validate([
                    'name'              => 'required|string|max:100',
                    'last_name'         => 'required|string|max:100',
                    'admission_number'  => 'required|string|max:50|unique:users,admission_number',
                    'roll_number'       => 'nullable|string|max:50',
                    'class_id'          => 'required|integer',
                    'gender'            => 'required|in:Male,Female,Other',
                    'date_of_birth'     => 'required|date',
                    'admission_date'    => 'required|date',
                    'email'             => 'required|email|unique:users,email',
                    'password'          => 'required|min:5',
                    'status'            => 'required|in:0,1',
                    'profile_pic'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
                    'mobile_number'     => 'nullable|string|max:15|min:8',
                    'blood_group'       => 'nullable|string|max:10',
                    'religion'          => 'nullable|string|max:10',
                    'height'            => 'nullable|string|max:10',
                    'weight'            => 'nullable|string|max:10',
                ]);

                $student = new User;
                $student->name = trim($request->name);
                $student->last_name = trim($request->last_name);
                $student->admission_number = trim($request->admission_number);
                $student->roll_number = trim($request->roll_number);
                $student->class_id = $request->class_id;
                $student->gender = $request->gender;
                $student->date_of_birth = $request->date_of_birth;
                $student->admission_date = $request->admission_date;
                $student->blood_group = trim($request->blood_group);
                $student->mobile_number = trim($request->mobile_number);
                $student->religion = trim($request->religion);
                $student->height = trim($request->height);
                $student->weight = trim($request->weight);
                $student->status = $request->status;
                $student->email = trim($request->email);
                $student->password = Hash::make($request->password);
                $student->user_type = 3;

                if ($request->hasFile('profile_pic')) {
                    $file = $request->file('profile_pic');
                    $extension = $file->getClientOriginalExtension();
                    $slugName = Str::slug($request->name . ' ' . $request->last_name);
                    $fileName = $slugName . '-' . time() . '.' . $extension;
                    $student->profile_pic = $file->storeAs('profile', $fileName, 'public');
                }

                $student->save();

                return redirect('admin/student/list')->with('success', 'Student Successfully Added');
            }
    public function edit($id){
        $data['getClass'] = ClassModel::getClass();
        $data['getRecord'] = User::getSingle($id);
        if(!empty($data['getRecord']))
            {
                $data['header_title']= 'Edit  Student';
        return view('admin.student.edit',$data);
            }else{
                abort(404);
            }
    }
       public function update($id, Request $request)
       {
             
                $request->validate([
                    'email'             => 'required|email|unique:users,email,'.$id,
                    'name'              => 'required|string|max:100',
                    'last_name'         => 'required|string|max:100',
                    'admission_number'  => 'required|string|max:15|unique:users,admission_number,'.$id,
                    'roll_number'       => 'nullable|string|max:50',
                    'class_id'          => 'required|integer',
                    'gender'            => 'required|in:Male,Female,Other',
                    'date_of_birth'     => 'required|date',
                    'admission_date'    => 'required|date',
                   
                    'status'            => 'required|in:0,1',
                    'profile_pic'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
                    'mobile_number'     => 'nullable|string|max:15|min:8',
                    'blood_group'       => 'nullable|string|max:10',
                    'religion'          => 'nullable|string|max:10',
                    'height'            => 'nullable|string|max:10',
                    'weight'            => 'nullable|string|max:10',
                ]);

                $student = User::getSingle($id);
                $student->name = trim($request->name);
                $student->last_name = trim($request->last_name);
                $student->admission_number = trim($request->admission_number);
                $student->roll_number = trim($request->roll_number);
                $student->class_id = $request->class_id;
                $student->gender = $request->gender;
                $student->date_of_birth = $request->date_of_birth;
                $student->admission_date = $request->admission_date;
                $student->blood_group = trim($request->blood_group);
                $student->mobile_number = trim($request->mobile_number);
                $student->religion = trim($request->religion);
                $student->height = trim($request->height);
                $student->weight = trim($request->weight);
                $student->status = $request->status;
                $student->email = trim($request->email);
                if(!empty($request->password))
                    {
                        $student->password = Hash::make($request->password);

                    }
                $student->user_type = 3;

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

                return redirect('admin/student/list')->with('success', 'Student Successfully Added');
            }
                public function delete($id){

        $user = User::getSingle($id);
        $user->is_delete = 1;
        $user->save();
        return redirect('admin/student/list')->with('success','Admin Successfully updated');
    }

}



