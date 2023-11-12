<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

use App\Models\User;

class AuthController extends Controller
{
    //
    public function loadRegister(){
        if (Auth::user() && Auth::user()->is_admin == true) {
            return redirect('/admin/dashboard');
        } elseif (Auth::user() && Auth::user()->is_admin == false) {
            return redirect('/dashboard');
        }
        return view('register');
    }

    public function studentRegister(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email|max:255',
            'password' => 'required|string|min:8|max:255',
        ]);

        $user = new User;
        $user ->name = $request->name;
        $user ->email = $request->email;
        $user ->password = Hash::make($request->password);
        $user ->save();

        return back()->with('success', 'Your Registration has been successfull.');
    }

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
        return view('student.dashboard');
    }

    public function adminDashboard(){
        return view('admin.dashboard');
    }

    public function logout(Request $request){
        $request->session()->flush();
        Auth::logout();
        return redirect('/');
    }
}