<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Menampilkan halaman login
    public function showLogin() {
        return view('auth.login');
    }

    public function login(Request $request) {
        // Gunakan 'username' sesuai Model User
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();

            // Pengalihan berdasarkan role yang ada di web.php
            if ($user->role === 'warehouse_admin') {
                return redirect()->intended('/admin/management');
            } elseif ($user->role === 'procurement_manager') {
                return redirect()->intended('/manager/dashboard');
            }

            return redirect()->intended('/');
        }

        return back()->withErrors(['username' => 'Username atau password salah.']);
    }

    public function showRegister() {
        return view('auth.register');
    }

    public function register(Request $request) {
        $request->validate([
            'real_name' => 'required|string|max:255', // Sesuaikan dengan Model
            'username'  => 'required|string|max:255|unique:users', // Sesuaikan dengan Model
            'password'  => 'required|string|min:8|confirmed',
            'role'      => 'required|string|in:warehouse_admin,procurement_manager', // Sesuaikan dengan Routes
        ]);

        User::create([
            'real_name' => $request->real_name,
            'username'  => $request->username,
            'password'  => Hash::make($request->password),
            'role'      => $request->role,
        ]);

        return redirect('/login')->with('success', 'Akun berhasil dibuat!');
    }

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}