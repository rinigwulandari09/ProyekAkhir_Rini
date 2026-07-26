<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash; // <-- Tambahkan ini
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function authenticate(Request $request)
    {
        $request->validate([
            'user_username' => 'required',
            'user_password' => 'required',
        ]);

        // 1. Cari user admin berdasarkan username
        $user = User::where('user_username', $request->user_username)->first();

        if ($user) {
            $inputPassword = $request->user_password;
            $inputPasswordSha256 = hash('sha256', $inputPassword);
            $isPasswordValid = false;

            // 2. Cek apakah password cocok dengan:
            // - SHA-256 (dari mobile)
            // - Teks biasa (plain text)
            // - Standar Bcrypt Laravel (Hash::check)
            if ($user->user_password == $inputPassword || 
                $user->user_password == $inputPasswordSha256 || 
                Hash::check($inputPassword, $user->user_password)) {
                $isPasswordValid = true;
            }

            // 3. Jika valid, login-kan secara manual menggunakan Auth::login()
            if ($isPasswordValid) {
                Auth::login($user);
                $request->session()->regenerate();
                return redirect()->route('dashboard');
            }
        }

        // Jika user tidak ditemukan atau password salah
        return back()
            ->withErrors([
                'user_username' => 'Username atau Password salah'
            ])
            ->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    // FITUR GOOGLE OAUTH
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }
   
    public function handleGoogleCallback(Request $request)
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            $user = User::where('user_email', $googleUser->getEmail())->first();

            if ($user) {
                Auth::login($user);
                $request->session()->regenerate();
                return redirect()->route('dashboard');
            }

            return redirect()->route('login')
                ->withErrors(['user_username' => 'Akun Google Anda tidak terdaftar sebagai Admin.']);

        } catch (\Exception $e) {
            return redirect()->route('login')
                ->withErrors(['user_username' => 'Gagal masuk menggunakan Google, silakan coba lagi.']);
        }
    }
}