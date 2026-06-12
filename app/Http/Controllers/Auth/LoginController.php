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

            $user = Auth::user();

            switch ($user->user_role) {

                case 'super_admin':
                    return redirect()->route('super_admin.dashboard');

                case 'admin':
                    return redirect()->route('admin.dashboard');

                default:
                    return redirect()->route('dashboard');
            }
        }

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
}