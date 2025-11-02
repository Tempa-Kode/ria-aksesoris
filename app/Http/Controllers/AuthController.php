<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class AuthController extends Controller
{
    public function register(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'username' => ['required', 'unique:users,username'],
            'email' => ['required', 'email', 'unique:users,email'],
            'no_hp' => ['required', 'unique:users,no_hp'],
            'password' => ['required', 'confirmed'],
            'alamat' => ['required'],
        ]);

        $credentials['password'] = bcrypt($credentials['password']);

        $user = User::create($credentials);

        Auth::login($user);

        $request->session()->regenerate();

        return redirect()->intended('home');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'username' => ['required'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            if (Auth::user()->role === 'admin' || Auth::user()->role === 'karyawan') {
                return redirect()->intended('dashboard');
            } elseif (Auth::user()->role === 'customer') {
                return redirect()->intended('home');
            } else {
                Auth::logout();
                return back()->with('error', 'Peran pengguna tidak dikenali.');
            }
        }

        return back()->with('error', 'tidak dapat masuk dengan kredensial yang diberikan.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
