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

    // public function pdf(Request $request) {
    //     $namaBulan = array(
    //         1 => 'Januari',
    //         2 => 'Februari',
    //         3 => 'Maret',
    //         4 => 'April',
    //         5 => 'Mei',
    //         6 => 'Juni',
    //         7 => 'Juli',
    //         8 => 'Agustus',
    //         9 => 'September',
    //         10 => 'Oktober',
    //         11 => 'November',
    //         12 => 'Desember',
    //     );
        
    //     // Ambil data dari method index
    //     $aplikasi = $this->index($request)->aplikasi;
    //     $reqSistem = $this->index($request)->reqSistem;
    //     $reqBulanTahun = $this->index($request)->reqBulanTahun;
    //     $sistemDipilih = $this->index($request)->sistemDipilih;
 
    //     // Eksekusi PDF
    //     $pdf = App::make('dompdf.wrapper');
    //     $dompdf = $pdf->getDomPDF()->setPaper('A4', 'portrait');
    //     $canvas = $dompdf->get_canvas();
    //     $canvas->page_text(437.8, 784.77, "{PAGE_NUM} of {PAGE_COUNT}", null, 8.1, array(0, 0, 0));
    //     $output = '
    //     <html>
    //         <head>
    //             <style>
    //             @page { margin: 190px 96px 120px 96px; }
    //             // @page { margin: 230px 37px 90px 37px; }
    //             body { font-family: sans-serif; }
    //             header { position: fixed; top: -130px; left: 0px; right: 0px; }
    //             main { margin-left: 8px; margin-right: 8px; }
    //             footer { position: fixed; bottom: -60px; left: 0px; right: 0px; }
    //             </style>
    //         </head>
    //         <body>
    //             <header>
    //                 <table style="border-collapse: collapse; border-spacing: 0; width: 100%; background: #f7f7f7; border: 2px solid black">
    //                     <tr>
    //                         <td style="border: 1px solid black; text-align: center; width: 20%;"><img width="90%" src="'.public_path("creative/img/inka/cropped-logo3.png").'"></td>
    //                         <td style="border: 1px solid black; text-align: center; width: 60%;"><h4 style="margin: 1px;">REPORT</h4><h4 style="margin: 10px; font-style: italic; font-size: 14px;">Penggunaan '.($reqSistem != null ? "Sistem Aplikasi ".$reqSistem : "Semua Sistem Aplikasi").'</h4><h4 style="margin: 10px; font-style: italic; font-size: 14px;">'.$namaBulan[date("n", strtotime($reqBulanTahun))].' '.date("Y", strtotime($reqBulanTahun)).'</h4></td>
    //                         <td style="border: 1px solid black; text-align: center; width: 20%;"><img width="90%" src="'.public_path("creative/img/inka/logo_ti.jpeg").'"></td>
    //                     </tr>
    //                 </table>
    //             </header>
    //             <footer>
    //                 <table style="border-collapse: collapse; border-spacing: 0; width: 100%; background: #f7f7f7; border: 2px solid black">
    //                     <tr>
    //                         <td style="border: 1px solid black; width: 20%; font-size: 11px;">&nbsp; Last changed on:</td>
    //                         <td style="border: 1px solid black; width: 60%; font-size: 11px;">&nbsp; File Name:</td>
    //                         <td style="border: 1px solid black; width: 20%; font-size: 11px;">&nbsp; Page:</td>
    //                     </tr>
    //                     <tr>
    //                         <td style="border: 1px solid black; width: 20%; font-size: 11px;">&nbsp; '.date("d/m/Y").'</td>
    //                         <td style="border: 1px solid black; width: 60%; font-size: 11px;">&nbsp; Penggunaan '.($reqSistem != null ? "Sistem Aplikasi ".$reqSistem : "Semua Sistem Aplikasi").'</td>
    //                         <td style="border: 1px solid black; width: 20%; font-size: 11px; color: red;">&nbsp; </td>
    //                     </tr>
    //                 </table>
    //             </footer>
    //             <main>
    //                 <div style="float: left; width: 50%; padding: 5px;">
    //                     <img src="'.public_path("").'/chartAuth.png" width="96%">
    //                 </div>
    //                 <div style="float: left; width: 50%; padding: 5px;">
    //                     <img src="'.public_path("").'/chartTrans.png" width="96%">
    //                 </div><br>
    //                 <h4 align="center" style="font-size: 14px;">Daftar Users</h4>
    //                 <table width="99.5%" style="border-collapse: collapse; border: 0px; margin: auto">
    //                     <tr>
    //                         <th style="border: 1px solid; padding: 6px; font-size: 12px;" width="30%">Username</th>
    //                         <th style="border: 1px solid; padding: 6px; font-size: 12px;" width="30%">Divisi</th>
    //                         <th style="border: 1px solid; padding: 6px; font-size: 12px;" width="40%">Waktu</th>
    //                     </tr>';  
    //                     foreach($sistemDipilih as $users) {
    //                     $output .= '
    //                     <tr>
    //                         <td style="border: 1px solid; padding: 5px; font-size: 12px;">&nbsp; '.$users->username.'</td>
    //                         <td style="border: 1px solid; padding: 5px; font-size: 12px;">&nbsp; '.$users->division.'</td>
    //                         <td style="border: 1px solid; padding: 5px; font-size: 12px;">&nbsp; '.\Carbon\Carbon::parse($users->event_at)->format("d M Y H:i:s").'</td>
    //                     </tr>';
    //                     }
    //                     $output .= '
    //                 </table>
    //                 <!-- <p style="font-size: 12px;">Total: '.$sistemDipilih->count().' Data.</p> -->
    //             </main>
    //         </body>
    //     </html>';
    //     $pdf->loadHTML($output);

    //     $return = $pdf->download("mLogs ".($reqSistem != null ? $reqSistem : "Semua Aplikasi")." ".$reqBulanTahun." at ".date("Y-m-d H i s"));

    //     unlink(public_path("")."/chartAuth.png");
    //     unlink(public_path("")."/chartTrans.png");

    //     return $return;
    // }

    // public function laporanPDF() {
    //     $output = '
    //         <html>
    //         <head>
    //             <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    //             <style>
    //                 body { font-family: sans-serif; }
    //             </style>
    //         </head>
    //         <body>
    //             <h2 align="center">Laporan Penjualan</h2>
    //             <h4 align="center">Desa Mojorayung, Kecamatan Wungu, Kabupaten Madiun</h4>
    //             <hr style="border-color: black;">
    //             <h3 align="center">LAPORAN</h3>
    //             <table style="border-collapse: collapse; border-spacing: 0; width: 100%; border: 2px solid black">
    //                 <thead style="background: #f7f7f7;">
    //                     <tr>
    //                         <th style="border: 1px solid black; padding: 10px;">Tanggal Pesan</th>
    //                         <th style="border: 1px solid black; padding: 10px;">Nama Pelanggan</th>
    //                         <th style="border: 1px solid black; padding: 10px;">Nama Produk</th>
    //                         <th style="border: 1px solid black; padding: 10px;">Jumlah</th>
    //                         <th style="border: 1px solid black; padding: 10px;">Total Harga</th>
    //                     </tr>
    //                 </thead>
    //                 <tbody>';
    //     $output .= '
    //                     <tr>
    //                         <td style="border: 1px solid black; padding: 10px">1</td>
    //                         <td style="border: 1px solid black; padding: 10px">2</td>
    //                         <td style="border: 1px solid black; padding: 10px">3</td>
    //                         <td style="border: 1px solid black; padding: 10px">4 unit</td>
    //                         <td style="border: 1px solid black; padding: 10px">5</td>
    //                     </tr>';
    //     $output .= '
    //                 </tbody>
    //                 <tfoot style="background: #f7f7f7;">
    //                     <tr>
    //                         <td colspan="4" style="font-weight: bold; padding: 10px; border: 1px solid black;">Jumlah Semua Produk yang Terjual</td>
    //                         <td style="font-weight: bold; padding: 10px; border: 1px solid black;">10 unit</td>
    //                     </tr>
    //                     <tr>
    //                         <td colspan="4" style="font-weight: bold; padding: 10px; border: 1px solid black;">Total Pendapatan Tahun 2023</td>
    //                         <td style="font-weight: bold; padding: 10px; border: 1px solid black;">10JT</td>
    //                     </tr>
    //                 </tfoot>';
    //     $output .= '
    //             </table><br><br>

    //             <table style="float: right">
    //                 <br><br>
    //                 <tr style="text-align: right">
    //                     <td colspan="2">
    //                         Madiun, 12345<br>
    //                         <br>TTD<br><br>
    //                         <u>( Owner )</u>
    //                     </td>
    //                 </tr>
    //             </table>
    //         </body>
    //         </html>';

    //     return Pdf::loadHtml($output)->setPaper('A4', 'portrait')->stream('Laporan Penjualan '.date("Y-m-d H-i-s").'.pdf', array('Attachment' => 0));

    // }
}
