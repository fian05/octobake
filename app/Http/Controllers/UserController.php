<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index() {
        $karyawans = User::all();
        return view('manajemen.karyawan', compact('karyawans'));
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'nama_karyawan' => 'required',
            'username_karyawan' => 'required',
        ]);
        if ($validator->fails()) {
            Session::flash('alert', [
                'type' => 'error',
                'title' => 'Karyawan Gagal Ditambahkan',
                'message' => 'Ada inputan yang salah!',
            ]);
        } else {
            try {
                User::create([
                    "nama" => $request->nama_karyawan,
                    "username" => $request->username_karyawan,
                    "password" => bcrypt('12345678'),
                    "role" => 'karyawan',
                ]);
                Session::flash('alert', [
                    'type' => 'success',
                    'title' => 'Karyawan Berhasil Ditambahkan',
                    'message' => "",
                ]);
            } catch (QueryException $e) {
                if ($e->errorInfo[1] == 1062) {
                    // Ini adalah kode kesalahan untuk duplikat entry
                    Session::flash('alert', [
                        'type' => 'error',
                        'title' => 'Karyawan Gagal Ditambahkan',
                        'message' => "Username sudah digunakan. Silakan gunakan username lain.",
                    ]);
                } else {
                    // Tangani kesalahan lainnya jika diperlukan
                    Session::flash('alert', [
                        'type' => 'error',
                        'title' => 'Karyawan Gagal Ditambahkan',
                        'message' => "Terjadi kesalahan saat menyimpan data.",
                    ]);
                }
            }
        }
        return back();
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_karyawan' => 'required',
            'username_karyawan' => 'required',
        ]);
        if ($validator->fails()) {
            Session::flash('alert', [
                'type' => 'error',
                'title' => 'Data Karyawan Gagal Diubah',
                'message' => 'Ada inputan yang salah!',
            ]);
        } else {
            $user = User::findOrFail($request->id);
            if($user) {
                try {
                    $user->update([
                        'nama' => $request->nama_karyawan,
                        'username' => $request->username_karyawan,
                    ]);
                    Session::flash('alert', [
                        'type' => 'success',
                        'title' => 'Data Karyawan Berhasil Diubah',
                        'message' => "",
                    ]);
                } catch (QueryException $e) {
                    if ($e->errorInfo[1] == 1062) {
                        // Ini adalah kode kesalahan untuk duplikat entry
                        Session::flash('alert', [
                            'type' => 'error',
                            'title' => 'Data Gagal Diubah',
                            'message' => "Username sudah digunakan. Silakan gunakan username lain.",
                        ]);
                    } else {
                        // Tangani kesalahan lainnya jika diperlukan
                        Session::flash('alert', [
                            'type' => 'error',
                            'title' => 'Data Gagal Diubah',
                            'message' => "Terjadi kesalahan saat menyimpan data.",
                        ]);
                    }
                }
            }
        }
        return back();
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        if($user) {
            $user->delete();
            Session::flash('alert', [
                'type' => 'success',
                'title' => 'Karyawan Berhasil Dihapus',
                'message' => '',
            ]);
        } else {
            Session::flash('alert', [
                'type' => 'error',
                'title' => 'Karyawan Gagal Dihapus',
                'message' => '',
            ]);
        }
        return back();
    }

    public function login(Request $request) {
        $data = [
            'username' => $request->username,
            'password' => $request->password,
        ];
        if(Auth::attempt($data)) {
            Session::flash('alert', [
                'type' => 'success',
                'title' => 'Login Berhasil',
                'message' => "Selamat Datang ".Auth::user()->nama,
            ]);
        }
        return redirect()->route('dashboard');
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

    public function updatePw(Request $request) {
        $this->validate($request, [
            'password_old' => 'required',
            'newPw' => 'required',
        ]);
        $user = User::where('username', $request->username)->first();
        if($user && password_verify($request->password_old, $user->password)) {
            $user->update([
                'password' => bcrypt($request->newPw),
            ]);
            Session::flash('alert', [
                'type' => 'success',
                'title' => 'Password Berhasil Diubah',
                'message' => '',
            ]);
            return redirect()->route('dashboard');
        } else {
            Session::flash('alert', [
                'type' => 'error',
                'title' => 'Password Gagal Diubah',
                'message' => 'Ada inputan yang salah!',
            ]);
            return back();
        }
    }

    public function resetPw($id) {
        $user = User::findOrFail($id);
        if($user) {
            $user->update([
                'password' => bcrypt("12345678"),
            ]);
            Session::flash('alert', [
                'type' => 'success',
                'title' => 'Reset Password Berhasil',
                'message' => "",
            ]);
        } else {
            Session::flash('alert', [
                'type' => 'error',
                'title' => 'Reset Password Gagal',
                'message' => "ID tidak valid!",
            ]);
        }
        return back();
    }
}