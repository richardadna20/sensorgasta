<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Pastikan pengguna harus login (auth) untuk mengakses semua metode di Controller ini.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Menampilkan formulir edit profil.
     */
    public function index()
    {
        // Karena ada middleware 'auth', Auth::user() dijamin BUKAN null.
        $user = Auth::user(); 
        
        // Pastikan Anda memanggil view yang benar (profil.index)
        return view('profil.index', compact('user'));
    }

    /**
     * Memperbarui data profil pengguna.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            // Validasi email agar unik kecuali email milik user yang sedang diedit
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id, 
            'password' => 'nullable|min:6|confirmed',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->back()->with('success', 'Profil berhasil diperbarui.');
    }
}