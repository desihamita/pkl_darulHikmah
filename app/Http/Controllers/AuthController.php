<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;


use App\Models\User;
use App\Models\PasswordReset;
use App\Models\Subject;
use App\Models\Exam;
use Mail;

class AuthController extends Controller
{
    public function loadLogin(){
        if (Auth::user() && Auth::user()->is_admin == true) {
            return redirect('/admin/dashboard');
        } elseif (Auth::user() && Auth::user()->is_admin == false) {
            return redirect('/dashboard');
        }
        return view('login');
    }

    public function userLogin(Request $request){
        $request->validate([
            'email' => 'required|email|string',
            'password' => 'required|string',
        ]);

        $userCredential = $request->only('email', 'password');

        if (Auth::attempt($userCredential)) {
            if (Auth::user()->is_admin == true) {
                return redirect('/admin/dashboard');
            } else {
                return redirect('/dashboard');
            }
        } else {
            return back()->with('error', 'Username & Password is incorrect');
        }
    }

    public function loadDashboard(){
        $exams = Exam::with('subjects')->orderBy('date')->get();
        return view('student.dashboard', compact('exams'));
    }

    public function adminDashboard(){
        return view('admin.admin-dashboard');
    }

    public function logout(Request $request){
        $request->session()->flush();
        Auth::logout();
        return redirect('/');
    }

    public function forgetPasswordLoad(){
        return view('forget-password');
    }

    public function forgetPassword(Request $request){
        try {
            $user = User::where('email', $request->email)->get();
            if (count($user) > 0) {
                $token = Str::random(40);
                $domain = URL::to('/');
                $url = $domain.'/reset-password?token='.$token;

                $data['url'] = $url;
                $data['email'] = $request->email;
                $data['title'] = 'Password Reset';
                $data['body'] = 'Please click on below link to reset your password';

                Mail::send('forgetPasswordMail', ['data' => $data], function($message) use ($data) {
                    $message->to($data['email'])->subject($data['title']);
                });

                $dateTime = Carbon::now()->format('Y-m-d H:i:s');

                PasswordReset::updateOrCreate(
                    ['email' => $request->email],
                    [
                        'email' => $request->email,
                        'token' => $token,
                        'created_at' => $dateTime,
                    ]
                );
                return back()->with('success', 'Please check your mail to reset your password!');

            } else {
                return back()->with('error', 'Email is not exists!');
            }
        } catch (\Exception $ex) {
            return back()->with('error', $ex->getMessage());
        }
    }
}
