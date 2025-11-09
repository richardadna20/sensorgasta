<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash; // WAJIB: Untuk meng-hash password
use App\Models\User;

class AuthController extends Controller
{
    // --- Metode Login & Register ---
    public function login()
    {
        return view('auth.login');
    }

    public function loginPost(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            return redirect()->intended('/dashboard');
        }

        return redirect()->back()->with('error', 'Email atau password salah!');
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }

    public function register()
    {
        return view('auth.register');
    }

    public function registerPost(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);

        return redirect('/dashboard');
    }

    // --- Metode Reset Password Sederhana (BARU) ---

    // 1. Menampilkan form reset sederhana (Dipanggil oleh route('password.request'))
    public function showSimpleResetForm()
    {
        // Memanggil view yang dikonfirmasi: auth/forgot-password.blade.php
        return view('auth.forgot-password'); 
    }

    // 2. Memproses update password langsung (Dipanggil oleh route('password.update'))
    public function simpleResetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:6|confirmed',
        ], [
            'email.exists' => 'Email yang Anda masukkan tidak terdaftar.',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user) {
            $user->password = Hash::make($request->password);
            $user->save();
            
            return redirect()->route('login')->with('success', 'Password Anda berhasil diubah! Silakan login.');
        }

        return back()->withErrors(['email' => 'Terjadi kesalahan saat mengubah password.']);
    }
}