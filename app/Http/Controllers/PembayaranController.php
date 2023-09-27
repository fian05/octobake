<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Models\Pembayaran;
use Illuminate\Http\Request;

class PembayaranController extends Controller
{
    public function index()
    {
        $pembayarans = Pembayaran::all();
        return view('pembayaran', compact('pembayarans'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'produk' => 'required',
            'dp' => 'required|numeric',
            'total' => 'required|numeric',
            'atas_nama' => 'required',
        ]);
        dd($validator);
        if ($validator->fails()) {
            Session::flash('alert', [
                'type' => 'error',
                'title' => 'Data Gagal Ditambahkan',
                'message' => 'Ada inputan yang salah!',
            ]);
        } else {
            Pembayaran::create([
                "invoice" => "INV".date("Ymd"),
                "produk" => $request->produk,
                "dp" => $request->dp,
                "total" => $request->total,
                "atas_nama" => $request->atas_nama,
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
