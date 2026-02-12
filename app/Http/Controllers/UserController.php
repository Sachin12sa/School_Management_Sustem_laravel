<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ClassModel;
use Str;
use Auth;
use Hash;
class UserController extends Controller
{
    function MyAccount()
    {   $data['getRecord'] = User::getSingle(Auth::user()->id);
        $data['header_title'] = "My Account";
        if(Auth::user()->user_type == 1)
        {
            return view('admin.my_account',$data);
        }
        elseif(Auth::user()->user_type == 2)
        {
            return view('teacher.my_account',$data);
        }
        elseif(Auth::user()->user_type == 3)
        {
            return view('student.my_account',$data);
        }
        elseif(Auth::user()->user_type == 4)
            {
                 return view('parent.my_account',$data);
            }
       
        
    }

    public function update(Request $request)
    {   
        // Function to update the admin account
        if(Auth::user()->user_type == 1)
            {
                            $id = Auth::user()->id;

                request()->validate([
                    'email' => 'required|email|unique:users,email,'.$id
                ]);
            $user = User::getSingle($id);
                $user->name = trim($request -> name);
                $user->email = trim($request -> email);
                
                $user->save();
                     return redirect()->back()->with('success','Account Successfully Updated');
                    }
        // Teacher Update account
        elseif(Auth::user()->user_type == 2)
        {

            $id = Auth::user()->id;
            $request->validate([
                'email'             => 'required|email|unique:users,email,'.$id,
                'name'              => 'required|string|max:100',
                'last_name'         => 'required|string|max:100',
                'gender'            => 'required|in:Male,Female,Other',
                'date_of_birth'     => 'required|date',
                'mobile_number'     => 'required|string|max:15|min:8',
                'marital_status'    => 'nullable|in:0,1',
                'current_address'   => 'nullable|string|max:255',
                'permanent_address' => 'nullable|string|max:255',
                'qualification'     => 'nullable|string|max:255',
                'work_experience'   => 'nullable|string|max:255',
                'profile_pic'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            ]);

            $teacher = Auth::user();
            $teacher->name = trim($request->name);
            $teacher->last_name = trim($request->last_name);
            $teacher->gender = $request->gender;
            $teacher->date_of_birth = $request->date_of_birth;
        
            $teacher->mobile_number = trim($request->mobile_number);
            $teacher->marital_status = $request->marital_status;
            $teacher->address = trim($request->current_address);
            $teacher->permanent_address = trim($request->permanent_address);
            $teacher->qualification = trim($request->qualification);
            $teacher->work_experience = trim($request->work_experience);
            $teacher->email = trim($request->email);
            
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

            return redirect()->back()->with('success','Account Successfully Updated');
        
            }
            // Function to update Student
            elseif(Auth::user()->user_type == 3)
            {
                
                    $id = Auth::user()->id;
                    $request->validate([
                        'email'             => 'required|email|unique:users,email,'.$id,
                        'name'              => 'required|string|max:100',
                        'last_name'         => 'required|string|max:100',
                        'gender'            => 'required|in:Male,Female,Other',
                        'date_of_birth'     => 'required|date',                   
                        'profile_pic'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
                        'mobile_number'     => 'nullable|string|max:15|min:8',
                        'blood_group'       => 'nullable|string|max:10',
                        'religion'          => 'nullable|string|max:10',
                        'height'            => 'nullable|string|max:10',
                        'weight'            => 'nullable|string|max:10',
                    ]);

                    $student = Auth::user();
                    $student->name = trim($request->name);
                    $student->last_name = trim($request->last_name);
                    $student->gender = $request->gender;
                    $student->date_of_birth = $request->date_of_birth;
                    $student->blood_group = trim($request->blood_group);
                    $student->mobile_number = trim($request->mobile_number);
                    $student->religion = trim($request->religion);
                    $student->height = trim($request->height);
                    $student->weight = trim($request->weight);
                    $student->email = trim($request->email);
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

                    return redirect()->back()->with('success','Account Successfully Updated');
                }
            // Function to update parents
            elseif(Auth::user()->user_type == 4)
                {
                    $id = Auth::user()->id;
                    $request->validate([
                        'email' => 'required|email|unique:users,email,'.$id,
                        'name'              => 'required|string|max:100',
                        'last_name'         => 'required|string|max:100',
                        'gender'            => 'required|in:Male,Female,Other',
                        'status'            => 'required|in:0,1',
                        'profile_pic'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
                        'mobile_number'     => 'required|string|max:15|min:8',
                        'blood_group'       => 'nullable|string|max:10',
                        'occupation'          => 'nullable|string|max:10',
                        'address'            => 'nullable|string|max:255',
                    ]);

                    $student = Auth::user();
                    $student->name = trim($request->name);
                    $student->last_name = trim($request->last_name);
                    $student->blood_group = trim($request->blood_group);
                    $student->gender = $request->gender;
                    $student->address = $request->address;
                    $student->occupation = trim($request->occupation);
                    $student->mobile_number = trim($request->mobile_number);
                    $student->status = $request->status;
                    $student->email = trim($request->email);
                
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

                    return redirect()->back()->with('success','Account Successfully Updated');
                }
            
    }
    public function change_password() {
    $data['header_title'] = "Change Password";
    $userType = Auth::user()->user_type;

    $viewPath = match($userType) {
        1 => 'admin.profile.change_password',
        2 => 'teacher.profile.change_password',
        3 => 'student.profile.change_password',
        4 => 'parent.profile.change_password',
        default => abort(404),
    };

    return view($viewPath, $data);
}
    
    function update_change_password(Request $request) {
     
        $request->validate([
        'old_password' => 'required',
        'new_password' => 'required|min:6',
        'confirm_password' => 'required|same:new_password',
    ]);
        $user = User::getSingle(Auth::user()->id);

        if (Hash::check($request->old_password, $user->password)) {
            
            if ($request->new_password === $request->confirm_password) {
                
                $user->password = Hash::make($request->new_password);
                $user->save();
                return redirect()->back()->with('success', 'Password Successfully Updated.');
                
            } else {
                return redirect()->back()->with('error', 'New password and confirm password do not match.');
            }

        } else {
            return redirect()->back()->with('error', 'Old Password does not match.');
        }
    }
}       
 

