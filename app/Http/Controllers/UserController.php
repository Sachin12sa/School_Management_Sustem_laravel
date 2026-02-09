<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Auth;
use Hash;
class UserController extends Controller
{
    function change_password(){
        $data['header_title'] = "Change Password";
        return view('admin.profile.change_password',$data);
    }
    function update_change_password(Request $request) {
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
