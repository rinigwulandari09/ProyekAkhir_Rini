<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        $credentials = [
            'user_username' => $request->user_username,
            'password'      => $request->user_password,
        ];

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('dashboard');
        }

        // Cek apakah username ada di database
        $userExists = User::where('user_username', $request->user_username)->first();

        if (!$userExists) {
            return back()
                ->withErrors([
                    'user_username' => 'Username yang Anda masukkan salah.'
                ])
                ->withInput();
        } else {
            return back()
                ->withErrors([
                    'user_password' => 'Password yang Anda masukkan salah.'
                ])
                ->withInput($request->except('user_password'));
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    // FITUR GOOGLE OAUTH
    // Mengalihkan pengguna ke halaman login Google.
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

   
    // Menangani respon balik (callback) dari Google setelah pengguna login.
    public function handleGoogleCallback(Request $request)
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            // Cari user berdasarkan email yang didapat dari Google
            // (Asumsi: di tabel users kamu ada kolom 'user_email')
            $user = User::where('user_email', $googleUser->getEmail())->first();

            if ($user) {
                // Login-kan user jika ditemukan di database
                Auth::login($user);
                
                // Regenerasi session seperti pada method authenticate bawaanmu
                $request->session()->regenerate();
                
                return redirect()->route('dashboard');
            }

            // Jika email Google tidak terdaftar di database (bukan Super Admin/Admin sah)
            return redirect()->route('login')
                ->withErrors(['user_username' => 'Akun Google Anda tidak terdaftar sebagai Admin.']);

        } catch (\Exception $e) {
            return redirect()->route('login')
                ->withErrors(['user_username' => 'Gagal masuk menggunakan Google, silakan coba lagi.']);
        }
    }
}