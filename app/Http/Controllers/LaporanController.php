<?php

namespace App\Http\Controllers;

use App\Models\Pembelian;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $tanggal = $request->input('tanggal');
        if (!$tanggal) {
            $tanggal = now()->format('Y-m-d');
        }
        $pembelians = Pembelian::whereDate('tanggal_pembelian', $tanggal)->get();
        $totalLaba = $pembelians->sum('total');
        $labaKotor = ($totalLaba * 60) / 100;
        $labaBersih = ($totalLaba * 40) / 100;
        return view('laporan.keuangan', compact('pembelians', 'tanggal', 'totalLaba', 'labaKotor', 'labaBersih'));
    }
}
