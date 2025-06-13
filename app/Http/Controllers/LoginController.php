<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index(){
        return view('auth.login');
    }

    public function process(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Tambahkan is_active dalam credentials
        $credentials = $request->only('email', 'password');
        $credentials['is_active'] = 1;

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended()->with('success', 'Login successful.');
        }

        return back()->with('error', 'Invalid credentials or your account is inactive.');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'You have been logged out successfully.');
    }
}
