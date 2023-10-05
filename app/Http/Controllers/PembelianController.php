<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Models\Pembelian;
use App\Models\Produk;
use Illuminate\Http\Request;

class PembelianController extends Controller
{
    public function index()
    {
        $produks = Produk::all();
        $pembelians = Pembelian::all();
        return view('transaksi.pembelian', compact('produks', 'pembelians'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_produk' => 'required',
            'harga_satuan' => 'required|numeric',
            'jumlah_dibeli' => 'required|numeric',
            'diskon' => 'required|numeric',
            'total' => 'required|numeric',
        ]);
        dd($validator);
        if ($validator->fails()) {
            Session::flash('alert', [
                'type' => 'error',
                'title' => 'Data Gagal Ditambahkan',
                'message' => 'Ada inputan yang salah!',
            ]);
        } else {
            Pembelian::create([
                "nama_produk" => $request->nama_produk,
                "harga_satuan" => $request->harga_satuan,
                "jumlah_dibeli" => $request->jumlah_dibeli,
                "diskon" => $request->diskon,
                "total" => $request->total,
            ]);
            Session::flash('alert', [
                'type' => 'success',
                'title' => 'Data Berhasil Ditambahkan',
                'message' => "",
            ]);
        }
        return back();
    }
}
