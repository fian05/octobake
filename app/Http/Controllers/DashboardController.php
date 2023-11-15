<?php

namespace App\Http\Controllers;

use App\Models\Pembelian;
use App\Models\Toko;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index() {
        $sekarang = Carbon::now();
        // Harian
        $sebelumnya = Carbon::now()->subDays(4)->toDateString();
        $besok = Carbon::now()->addDay()->toDateString();
        $dataHarian = Pembelian::selectRaw('DATE(tanggal_pembelian) as tanggal, SUM(total) as total_pembelian')->whereBetween('tanggal_pembelian', [$sebelumnya, $besok])->groupBy('tanggal')->orderBy('tanggal', 'desc')->get();
        $dataHarian = $dataHarian->map(function ($item) {
            return [
                'tanggal_pembelian' => Carbon::parse($item['tanggal'])->format('Y-m-d'),
                'total' => $item['total_pembelian'],
            ];
        });
        $dataHarianJson = $dataHarian->toJson();

        // Mingguan
        $dataMingguan = [];
        for ($i = 0; $i < 5; $i++) {
            $startDate = $sekarang->copy()->subDays($sekarang->dayOfWeek)->subWeeks($i);
            $endDate = $startDate->copy()->addDays(6);
            $totalPenjualan = Pembelian::whereBetween('tanggal_pembelian', [$startDate->format('Y-m-d 00:00:00'), $endDate->format('Y-m-d 23:59:59')])->sum('total');
            $formattedStartDate = $startDate->format('Y-m-d');
            $label = $startDate->format('d F');
            if ($startDate->year != $endDate->year) {
                $label .= ' ' . $startDate->format('Y');
            } elseif ($startDate->month != $endDate->month) {
                $label .= ' ' . $startDate->format('F');
            }
            $label .= ' - ' . $endDate->format('d F Y');
            $dataMingguan[] = [
                'tanggal_pembelian' => $formattedStartDate,
                'label' => $label,
                'total_penjualan' => $totalPenjualan,
            ];
        }
        $dataMingguanJson = json_encode($dataMingguan);

        // Bulanan
        $dataBulanan = [];
        for ($i = 4; $i >= 0; $i--) {
            $date = $sekarang->copy()->subMonths($i);
            $startOfMonth = $date->copy()->startOfMonth();
            $endOfMonth = $date->copy()->endOfMonth();
            $totalPenjualan = Pembelian::whereBetween('tanggal_pembelian', [$startOfMonth->format('Y-m-d 00:00:00'), $endOfMonth->format('Y-m-d 23:59:59')])->sum('total');
            $formattedDate = $date->format('F Y');
            $dataBulanan[] = [
                'label' => $formattedDate,
                'total_penjualan' => $totalPenjualan,
            ];
        }
        $dataBulananJson = json_encode($dataBulanan);

        return view('dashboard', compact('dataHarianJson', 'dataMingguanJson', 'dataBulananJson'));
    }
    public function login() {
        if (Toko::count() === 0) {
            return redirect()->route('toko_aktivasi');
        }
        if(Auth()->check()) {
            return back();
        }
        $toko = Toko::first();
        return view('login', compact('toko'));
    }
}