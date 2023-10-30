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
        $pembelians->each(function ($pembelian) {
            $pembelian->harga_satuan_asli = $pembelian->harga_satuan;
            $pembelian->total_asli = $pembelian->total;
            $pembelian->harga_satuan = 'Rp' . number_format($pembelian->harga_satuan, 0, ',', '.');
            $pembelian->total = 'Rp' . number_format($pembelian->total, 0, ',', '.');
        });
        return view('transaksi.pembelian', compact('produks', 'pembelians'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tanggal_pembelian' => 'required',
            'nama_produk' => 'required|exists:produk,id',
            'harga_satuan' => 'required|numeric',
            'jumlah_dibeli' => 'required|numeric|min:1',
            'diskon' => 'numeric',
            'total' => 'required|numeric',
        ]);
        // dd($validator);
        if ($validator->fails()) {
            Session::flash('alert', [
                'type' => 'error',
                'title' => 'Data Gagal Ditambahkan',
                'message' => 'Ada inputan yang salah!',
            ]);
        } else {
            $produk = Produk::findOrFail($request->nama_produk);
            if($produk) {
                $produk->update([
                    "stok_produk" => $produk->stok_produk-$request->jumlah_dibeli,
                ]);
                Pembelian::create([
                    "nama_produk" => $produk->nama_produk,
                    "harga_satuan" => $request->harga_satuan,
                    "jumlah_dibeli" => $request->jumlah_dibeli,
                    "diskon" => $request->diskon,
                    "total" => $request->total,
                    "tanggal_pembelian" => $request->tanggal_pembelian,
                ]);
                Session::flash('alert', [
                    'type' => 'success',
                    'title' => 'Data Berhasil Ditambahkan',
                    'message' => "",
                ]);
            } else {
                Session::flash('alert', [
                    'type' => 'error',
                    'title' => 'Data Gagal Ditambahkan',
                    'message' => 'Data Produk tidak Valid!',
                ]);
            }
        }
        return back();
    }

    public function destroy($id)
    {
        $pembelian = Pembelian::findOrFail($id);
        if($pembelian) {
            $produk = Produk::where('nama_produk', $pembelian->nama_produk)->first();
            if($produk) {
                $produk->update([
                    "stok_produk" => $produk->stok_produk+$pembelian->jumlah_dibeli,
                ]);
                $pembelian->delete();
                Session::flash('alert', [
                    'type' => 'success',
                    'title' => 'Data Berhasil Dihapus',
                    'message' => '',
                ]);
            } else {
                $pembelian->delete();
                Session::flash('alert', [
                    'type' => 'success',
                    'title' => 'Data Berhasil Dihapus',
                    'message' => '',
                ]);
            }
        } else {
            Session::flash('alert', [
                'type' => 'error',
                'title' => 'Data Gagal Dihapus',
                'message' => '',
            ]);
        }
        return back();
    }

    public function print(Request $request) {
        $dataDipilih = $request->dataDipilih;
        if($dataDipilih) {
            $pembelians = Pembelian::whereIn('id', $dataDipilih)->get();
            return response()->json([
                'status' => 'success',
                'pembelians' => $pembelians,
            ]);
        } else {
            return response()->json([
                'status' => 'error',
            ]);
        }
    }
}
