<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    public function login(Request $request) {
        $data = [
            'username' => $request->username,
            "password" => $request->password,
        ];
        if(Auth::attempt($data)) {
            Session::flash('alert', [
                'type' => 'success',
                'title' => 'Login Berhasil',
                'message' => "Selamat Datang ".Auth::user()->nama,
            ]);
            return redirect()->route('dashboard');
        }
        Session::flash('alert', [
            'type' => 'error',
            'title' => 'Login Gagal',
            'message' => "Username atau Password salah!",
        ]);
        return back();
    }

    public function logout() {
        Auth::logout();
        Session::flash('alert', [
            'type' => 'success',
            'title' => 'Logout Berhasil',
            'message' => '',
        ]);
        return redirect()->route('login');
    }
}