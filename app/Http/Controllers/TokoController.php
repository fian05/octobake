<?php

namespace App\Http\Controllers;

use App\Models\Toko;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class TokoController extends Controller
{
    public function aktivasi() {
        if (Toko::count() != 0) {
            Session::flash('alert', [
                'type' => 'success',
                'title' => 'Toko Berhasil Diaktivasi',
                'message' => "Silahkan melakukan validasi akun admin.",
            ]);
            return redirect()->route('login');
        }
        return view('aktivasi');
    }

    public function aktivasi_proses(Request $request) {
        $validator = Validator::make($request->all(), [
            'nama_owner' => 'required',
            'username_owner' => 'required',
            'password1' => 'required',
            'nama_toko' => 'required',
            'logo_toko' => 'required',
            'nohp_toko' => 'required|numeric',
            'alamat_toko' => 'required',
        ]);
        if ($validator->fails()) {
            Session::flash('alert', [
                'type' => 'error',
                'title' => 'Aktivasi Toko Gagal',
                'message' => 'Ada inputan yang salah!',
            ]);
        } else {
            $folderPath = public_path('media/photos/upload');
            if (!file_exists($folderPath)) {
                mkdir($folderPath, 0755, true);
            }
            $nama = strtolower(str_replace([' ', '(', ')', '+'], ['-', '', '', '-'], $request->nama_toko));
            $namaFile = $nama.'.png';
            $request->file('logo_toko')->move($folderPath, $namaFile);

            $nohp = $request->nohp_toko;
            // Menghapus karakter +62 atau 0 pada awal nomor
            $nohp = preg_replace('/^(?:\+62|0)(\d{9,})$/', '$1', $nohp);
            // Pastikan hanya angka yang tersisa
            $nohp = preg_replace('/\D/', '', $nohp);
            Toko::create([
                "nama_toko" => $request->nama_toko,
                "logo_toko" => $namaFile,
                "nohp_toko" => $nohp,
                "ig_toko" => $request->ig_toko,
                "alamat_toko" => $request->alamat_toko
            ]);
            User::create([
                "nama" => $request->nama_owner,
                "username" => $request->username_owner,
                "password" => bcrypt($request->password1),
                "role" => 'owner',
            ]);
            Session::flash('alert', [
                'type' => 'success',
                'title' => 'Aktivasi Toko Berhasil',
                'message' => 'Selamat Datang Admin!',
            ]);
        }
        return back();
    }

    public function index()
    {
        $toko = Toko::all();
        return view('manajemen.toko', compact('toko'));
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_toko' => 'required',
            'logo' => 'required',
            'nohp_toko' => 'required|numeric',
            'alamat_toko' => 'required',
        ]);
        if ($validator->fails()) {
            Session::flash('alert', [
                'type' => 'error',
                'title' => 'Perubahan Data Gagal',
                'message' => 'Ada inputan yang salah!',
            ]);
        } else {
            $folderPath = public_path('media/photos/upload');
            if (!file_exists($folderPath)) {
                mkdir($folderPath, 0755, true);
            }
            $nohp = $request->nohp_toko;
            // Menghapus karakter +62 atau 0 pada awal nomor
            $nohp = preg_replace('/^(?:\+62|0)(\d{9,})$/', '$1', $nohp);
            // Pastikan hanya angka yang tersisa
            $nohp = preg_replace('/\D/', '', $nohp);
            if ($request->hasFile('gambar_produk')) {
                $nama = strtolower(str_replace([' ', '(', ')', '+'], ['-', '', '', '-'], $request->nama_toko));
                $namaFile = $nama.'.png';
                $request->file('logo_toko')->move($folderPath, $namaFile);

                Toko::first()->update([
                    "nama_toko" => $request->nama_toko,
                    "logo_toko" => $namaFile,
                    "nohp_toko" => $nohp,
                    "ig_toko" => $request->ig_toko,
                    "alamat_toko" => $request->alamat_toko
                ]);
            } else {
                Toko::first()->update([
                    "nama_toko" => $request->nama_toko,
                    "nohp_toko" => $nohp,
                    "ig_toko" => $request->ig_toko,
                    "alamat_toko" => $request->alamat_toko
                ]);
            }
            Session::flash('alert', [
                'type' => 'success',
                'title' => 'Perubahan Data Berhasil',
                'message' => '',
            ]);
        }
        return back();
    }
}
