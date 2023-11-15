<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
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
        $totalLaba = Pembelian::whereDate('tanggal_pembelian', $tanggal)->sum('total');
        $labaKotor = ($totalLaba * 60) / 100;
        $labaBersih = ($totalLaba * 40) / 100;
        return view('laporan.keuangan', compact('pembelians', 'tanggal', 'totalLaba', 'labaKotor', 'labaBersih'));
    }

    public function mingguan(Request $request)
    {
        $startDate = $request->input('kurun_waktu_awal');
        $endDate = $request->input('kurun_waktu_akhir');
        if (!$startDate || !$endDate) {
            $now = now();
            $startDate = $now->copy()->subDays($now->dayOfWeek)->subWeek()->format('Y-m-d');
            $endDate = $now->copy()->subDays($now->dayOfWeek)->subWeek()->copy()->addDays(6)->format('Y-m-d');
        }
        $pembelians = Pembelian::whereBetween('tanggal_pembelian', [
            Carbon::createFromFormat('Y-m-d', $startDate)->startOfDay(),
            Carbon::createFromFormat('Y-m-d', $endDate)->endOfDay()
        ])->get();
        $totalLaba = $pembelians->sum('total');
        $labaKotor = ($totalLaba * 60) / 100;
        $labaBersih = ($totalLaba * 40) / 100;
        return view('laporan.keuanganMingguan', compact('pembelians', 'startDate', 'endDate', 'totalLaba', 'labaKotor', 'labaBersih'));
    }

    public function bulanan(Request $request)
    {
        $bulan_tahun = $request->input('bulan_tahun');
        if (!$bulan_tahun) {
            $bulan_tahun = now()->format('Y-m');
        }
        list($tahun, $bulan) = explode('-', $bulan_tahun);
        $pembelians = Pembelian::whereYear('tanggal_pembelian', $tahun)
            ->whereMonth('tanggal_pembelian', $bulan)
            ->get();
        $totalLaba = Pembelian::whereYear('tanggal_pembelian', $tahun)
            ->whereMonth('tanggal_pembelian', $bulan)
            ->sum('total');
        $labaKotor = ($totalLaba * 60) / 100;
        $labaBersih = ($totalLaba * 40) / 100;
        return view('laporan.keuanganBulanan', compact('pembelians', 'bulan_tahun', 'totalLaba', 'labaKotor', 'labaBersih'));
    }
}
