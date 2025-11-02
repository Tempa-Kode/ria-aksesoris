<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function dashboard()
    {
        if(Auth::user()->role !== 'admin' && Auth::user()->role !== 'karyawan') {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }
        return view('dashboard');
    }
}
