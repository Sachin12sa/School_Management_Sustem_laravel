<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use App\Mail\ForgetPasswordMail;
use Illuminate\Support\Facades\Mail;
use Str;
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
    public function forgetPassword(){
        return view('auth.forget-password');
    }

    public function PostForgetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $user = User::getEmailSingle($request->email);

        if (!empty($user)) {
            $user->remember_token = Str::random(30);
            $user->save();

            Mail::to($user->email)->send(new ForgetPasswordMail($user));

            return redirect()->back()
                ->with('success', 'Please check your email to reset your password.');
        }

        return redirect()->back()
            ->with('error', 'Email not found in the system.');
    }
    public function reset($token)
    {
        $user = User::getTokenSingle($token);

        if (!empty($user)) {
            return view('auth.reset', [
                'user' => $user,
                'token' => $token
            ]);
        }
        abort(404);
    }

    public function postReset($token, Request $request){
        if($request->password == $request->cpassword)
            {
                $user = User::getTokenSingle($token);
                $user->password = Hash::make($request->password);
                $user->remember_token = Str::random(30);
                $user->save();
                return redirect(url(''))->with('success', 'Password successfully reset. Please login.');
            }else{
                return redirect()->back()->with('error','Your Password does not match');
            }
    }

    public function logout()
        {
            Auth::logout();
            return redirect('/');
        }
    
}

