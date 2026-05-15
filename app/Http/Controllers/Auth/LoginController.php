<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function authenticate(Request $request)
    {
        // Validasi input sesuai nama kolom Supabase
        $credentials = $request->validate([
            'user_username' => ['required'],
            'user_password' => ['required'],
        ]);

        // Proses Login (Mapping user_password ke 'password' untuk internal Laravel)
        if (Auth::attempt([
            'user_username' => $credentials['user_username'],
            'password' => $credentials['user_password']
        ])) {
            $request->session()->regenerate();

            // Redirect berdasarkan Role
            if (Auth::user()->user_role === 'super_admin') {
                return redirect()->intended('/dashboard'); // Route Super Admin
            }

            return redirect()->intended('/admin/dashboard'); // Route Admin
        }

        // Jika Gagal
        return back()->withErrors([
            'user_username' => 'Username atau password salah.',
        ])->onlyInput('user_username');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}