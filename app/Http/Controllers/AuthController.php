<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('Login'); // صفحة تسجيل الدخول التي كتبتها
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }


    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);



        if (Auth::attempt(['email' => $request->email, 'password' => $request->password,])) {
            $request->session()->regenerate();
            return redirect()->route('manage.index');
        }

        return back()
            ->withErrors([
                'email' => 'بيانات تسجيل الدخول غير صحيحة'
            ])
            ->withInput($request->only('email'));
    }

}
