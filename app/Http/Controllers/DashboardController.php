<?php

namespace App\Http\Controllers;

use App\Models\Pembelian;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index() {
        $sebelumnya = Carbon::now()->subDays(4)->toDateString();
        $besok = Carbon::now()->addDay()->toDateString();
        $dataHarian = Pembelian::selectRaw('DATE(tanggal_pembelian) as tanggal, SUM(total) as total_pembelian')->whereBetween('tanggal_pembelian', [$sebelumnya, $besok])->groupBy('tanggal')->orderBy('tanggal', 'desc')->get();

        return view('dashboard', compact('dataHarian'));
    }
}