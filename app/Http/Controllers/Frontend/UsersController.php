<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{

    public function login() {
        return view('users.login', [
            "page" => "login"
        ]);
    }

    public function forgotPassword() {
        return view('users.forgot-password', [
            "page" => "login",
            "ngApp" => "barefilterForgotPassword"
        ]);
    }

    public function logout() {
        Auth::logout();
        return redirect()->route('home');
    }

    public function register() {
        return view('users.register', [
            "page" => "become-member"
        ]);
    }
    
    
}
