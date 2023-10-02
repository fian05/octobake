<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Models\Produk;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    public function index()
    {
        $produks = Produk::all();
        $produks->each(function ($produk) {
            $produk->harga_asli = $produk->harga_produk;
            $produk->harga_produk = 'Rp' . number_format($produk->harga_produk, 2, ',', '.');
        });
        return view('manajemen.produk', compact('produks'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_produk' => 'required',
            'stok_produk' => 'required|numeric',
            'harga_produk' => 'required|numeric',
            'gambar_produk' => 'required',
        ]);
        if ($validator->fails()) {
            Session::flash('alert', [
                'type' => 'error',
                'title' => 'Produk Gagal Ditambahkan',
                'message' => 'Ada inputan yang salah!',
            ]);
        } else {
            $folderPath = public_path('media/photos/upload');
            if (!file_exists($folderPath)) {
                mkdir($folderPath, 0755, true);
            }
            $nama = strtolower(str_replace(' ', '-', $request->nama_produk));
            $namaFile = $nama.'.jpg';
            $request->file('gambar_produk')->move($folderPath, $namaFile);
            Produk::create([
                "nama_produk" => $request->nama_produk,
                "stok_produk" => $request->stok_produk,
                "harga_produk" => $request->harga_produk,
                "gambar_produk" => $namaFile,
            ]);
            Session::flash('alert', [
                'type' => 'success',
                'title' => 'Produk Berhasil Ditambahkan',
                'message' => "",
            ]);
        }
        return back();
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_produk' => 'required',
            'stok_produk' => 'required|numeric',
            'harga_produk' => 'required|numeric',
            'id' => 'required',
            'gambar' => 'required',
        ]);
        if ($validator->fails()) {
            Session::flash('alert', [
                'type' => 'error',
                'title' => 'Produk Gagal Diubah',
                'message' => 'Ada inputan yang salah!',
            ]);
        } else {
            $produk = Produk::findOrFail($request->id);
            if($produk) {
                $folderPath = public_path('media/photos/upload');
                if (!file_exists($folderPath)) {
                    mkdir($folderPath, 0755, true);
                }
                if ($request->hasFile('gambar_produk')) {
                    $nama = strtolower(str_replace(' ', '-', $request->nama_produk));
                    $namaFile = $nama.'.jpg';
                    $request->file('gambar_produk')->move($folderPath, $namaFile);
                    $produk->update([
                        'nama_produk' => $request->nama_produk,
                        'stok_produk' => $request->stok_produk,
                        'harga_produk' => $request->harga_produk,
                        'gambar_produk' => $namaFile,
                    ]);
                } else {
                    $produk->update([
                        'nama_produk' => $request->nama_produk,
                        'stok_produk' => $request->stok_produk,
                        'harga_produk' => $request->harga_produk,
                        'gambar_produk' => $request->gambar,
                    ]);
                }
                Session::flash('alert', [
                    'type' => 'success',
                    'title' => 'Produk Berhasil Diubah',
                    'message' => "",
                ]);
            } else {
                Session::flash('alert', [
                    'type' => 'error',
                    'title' => 'Produk Gagal Diubah',
                    'message' => 'ID Produk tidak ditemukan!',
                ]);
            }
        }
        return back();
    }

    public function destroy($id)
    {
        $produk = Produk::findOrFail($id);
        if($produk) {
            File::delete(public_path('media/photos/upload/'.$produk->gambar_produk));
            $produk->delete();
            Session::flash('alert', [
                'type' => 'success',
                'title' => 'Produk Berhasil Dihapus',
                'message' => '',
            ]);
        } else {
            Session::flash('alert', [
                'type' => 'error',
                'title' => 'Produk Gagal Dihapus',
                'message' => '',
            ]);
        }
        return back();
    }
}
