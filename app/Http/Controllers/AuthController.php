<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Auth;
class AuthController extends Controller
{
    function login(){
        if(!empty(Auth::check()))
            {
                if(Auth::user()->user_type == 1){
                    return redirect('admin/dashboard');
                }
                else if(Auth::user()->user_type == 2){
                    return redirect('teacher/dashboard');
                    
                }
                else if(Auth::user()->user_type == 3){
                    return redirect('student/dashboard');
                    
                }
                else if(Auth::user()->user_type == 4){
                    return redirect('parent/dashboard');
                    
                }
                else if(Auth::user()->user_type == 5){
                    return redirect('accountant/dashboard');
                    
                }

            }
        return view('auth.login');
    }
    function AuthLogin(Request $request)
    {
        
        $remember = !empty($request->remember) ? true : false;
        if(Auth::attempt(['email'=>$request->email,'password'=>$request->password],true))
            {
                if(Auth::user()->user_type == 1){
                    return redirect('admin/dashboard');
                }
                else if(Auth::user()->user_type == 2){
                    return redirect('teacher/dashboard');
                    
                }
                else if(Auth::user()->user_type == 3){
                    return redirect('student/dashboard');
                    
                }
                else if(Auth::user()->user_type == 4){
                    return redirect('parent/dashboard');
                    
                }
                else if(Auth::user()->user_type == 5){
                    return redirect('accountant/dashboard');
                    
                }
                
        }
        else{
            return redirect()->back()->with('error','Please enter correct email and password');
        }

    }
    public function logout()
        {
            Auth::logout();
            return redirect('/');
        }

}

