<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Toko;
use App\Models\Pembelian;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

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

    public function laporanPDF(Request $request) {
        switch($request->jenis_laporan) {
            case 'harian':
                $tanggal = $request->tanggal;
                $pembelians = Pembelian::whereDate('tanggal_pembelian', $tanggal)->get();
                $totalLaba = Pembelian::whereDate('tanggal_pembelian', $tanggal)->sum('total');
                $labaKotor = ($totalLaba * 60) / 100;
                $labaBersih = ($totalLaba * 40) / 100;
                $tanggal = date('d F Y', strtotime($tanggal));
                break;
            case 'mingguan':
                $startDate = $request->kurun_waktu_awal;
                $endDate = $request->kurun_waktu_akhir;
                $pembelians = Pembelian::whereBetween('tanggal_pembelian', [
                    Carbon::createFromFormat('Y-m-d', $startDate)->startOfDay(),
                    Carbon::createFromFormat('Y-m-d', $endDate)->endOfDay()
                ])->get();
                $totalLaba = $pembelians->sum('total');
                $labaKotor = ($totalLaba * 60) / 100;
                $labaBersih = ($totalLaba * 40) / 100;
                
                $startDay = date('d', strtotime($startDate));
                $startMonth = date('F', strtotime($startDate));
                $startYear = date('Y', strtotime($startDate));
                $endDay = date('d', strtotime($endDate));
                $endMonth = date('F', strtotime($endDate));
                $endYear = date('Y', strtotime($endDate));
                if($startYear == $endYear) { // if 2023 == 2023
                    if($startMonth == $endMonth) { // if November == November
                        $tanggal = $startDay.' - '.$endDay.' '.$endMonth.' '.$endYear; // 5 - 11 November 2023
                    } else { // if October != November
                        $tanggal = $startDay.' '.$startMonth.' - '.$endDay.' '.$endMonth.' '.$endYear; // 29 October - 4 November 2023
                    }
                } else { // if 2022 != 2023
                    $tanggal = $startDay.' '.$startMonth.' '.$startYear.' - '.$endDay.' '.$endMonth.' '.$endYear; // 31 December 2022 - 1 January 2023
                }
                break;
            case 'bulanan':
                $tanggal = $request->bulan_tahun;
                list($tahun, $bulan) = explode('-', $tanggal);
                $pembelians = Pembelian::whereYear('tanggal_pembelian', $tahun)
                    ->whereMonth('tanggal_pembelian', $bulan)
                    ->get();
                $totalLaba = Pembelian::whereYear('tanggal_pembelian', $tahun)
                    ->whereMonth('tanggal_pembelian', $bulan)
                    ->sum('total');
                $labaKotor = ($totalLaba * 60) / 100;
                $labaBersih = ($totalLaba * 40) / 100;
                $tanggal = date('F Y', strtotime($tanggal));
                break;
            default:
                $tanggal = now()->format('Y-m-d');
                $pembelians = Pembelian::whereDate('tanggal_pembelian', $tanggal)->get();
                $totalLaba = Pembelian::whereDate('tanggal_pembelian', $tanggal)->sum('total');
                $labaKotor = ($totalLaba * 60) / 100;
                $labaBersih = ($totalLaba * 40) / 100;
                $tanggal = date('d F Y', strtotime($tanggal));
                break;
        }
        $toko = Toko::first();
        $output = '
            <html>
            <head>
                <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
                <style>
                    @page { size: A4 portrait; }
                    html { margin: 1cm 2cm; }
                    body { max-width: 21cm; font-family: sans-serif; }
                    .page-break {
                        page-break-after: always;
                    }
                </style>
            </head>
            <body>
                <table style="border-collapse: collapse; border-spacing: 0; width: 100%; text-align: center; border-bottom: 2px solid black;">
                    <tr></tr>
                        <td><img src="'.public_path("media/photos/upload/$toko->logo_toko").'" style="width: 100px; padding-bottom: 7px;"></td>
                        <td>
                            <h1 style="margin: 0;">'.strtoupper($toko->nama_toko).'</h1>
                            <h5 style="margin: 0;">'.strtoupper($toko->alamat_toko).'</h5>
                            <h5 style="margin: 0;">'.strtoupper("WhatsApp: 0".$toko->nohp_toko." | Instagram: ".$toko->ig_toko).'</h5>
                        </td>
                    </tr>
                </table>
                <h4>Laporan Penjualan '.($request->jenis_laporan != 'bulanan' ? ($request->jenis_laporan == 'harian' ? 'Tanggal '.$tanggal : $tanggal) : 'Bulan '.$tanggal).'</h4>
                <table style="border-collapse: collapse; border-spacing: 0; width: 100%; border: 2px solid black; font-size: 10pt;">
                    <thead style="background: #f7f7f7;">
                        <tr>
                            <th style="border: 1px solid black; padding: 3px;">No.</th>
                            '.($request->jenis_laporan != 'harian' ? '<th style="border: 1px solid black; padding: 3px;">Tanggal Pembelian</th>' : '').'
                            <th style="border: 1px solid black; padding: 3px;">Nama Produk</th>
                            <th style="border: 1px solid black; padding: 3px;">Harga Satuan</th>
                            <th style="border: 1px solid black; padding: 3px;">Jumlah Dibeli</th>
                            <th style="border: 1px solid black; padding: 3px;">Diskon</th>
                            <th style="border: 1px solid black; padding: 3px;">Total</th>
                        </tr>
                    </thead>
                    <tbody>';
        $no = 1;
        foreach($pembelians as $pembelian) {
            $output .= '
                        <tr>
                            <td style="border: 1px solid black; padding: 3px; text-align: center;">'.$no++.'</td>
                            '.($request->jenis_laporan != 'harian' ? '<td style="border: 1px solid black; padding: 3px;">'.$pembelian->tanggal_pembelian.' WIB</td>' : '').'
                            <td style="border: 1px solid black; padding: 5px">'.$pembelian->nama_produk.'</td>
                            <td style="border: 1px solid black; padding: 3px; text-align: right;">Rp'.number_format($pembelian->harga_satuan, 0, ',', '.').'</td>
                            <td style="border: 1px solid black; padding: 3px; text-align: center;">'.$pembelian->jumlah_dibeli.' produk</td>
                            <td style="border: 1px solid black; padding: 3px; text-align: center;">'.$pembelian->diskon.'%</td>
                            <td style="border: 1px solid black; padding: 3px; text-align: right;">Rp'.number_format($pembelian->total, 0, ',', '.').'</td>
                        </tr>';
        }
        $output .= '
                    </tbody>
                    <tfoot style="background: #f7f7f7;">
                        <tr>
                            <td colspan="'.($request->jenis_laporan != 'harian' ? 6 : 5).'" style="font-weight: bold; padding: 3px; border: 1px solid black;">Jumlah</td>
                            <td id="total" style="font-weight: bold; padding: 3px; border: 1px solid black; text-align: right;">Rp'.number_format($totalLaba, 0, ',', '.').'</td>
                        </tr>
                        <tr>
                            <td colspan="'.($request->jenis_laporan != 'harian' ? 6 : 5).'" style="font-weight: bold; padding: 3px; border: 1px solid black;">60%</td>
                            <td style="font-weight: bold; padding: 3px; border: 1px solid black; text-align: right;">Rp'.number_format($labaKotor, 0, ',', '.').'</td>
                        </tr>
                        <tr>
                            <td colspan="'.($request->jenis_laporan != 'harian' ? 6 : 5).'" style="font-weight: bold; padding: 3px; border: 1px solid black;">40%</td>
                            <td style="font-weight: bold; padding: 3px; border: 1px solid black; text-align: right;">Rp'.number_format($labaBersih, 0, ',', '.').'</td>
                        </tr>
                    </tfoot>';
        $alamatParts = explode(',', $toko->alamat_toko);
        $namaKota = trim(end($alamatParts));
        $output .= '
                </table>
                <div style="height: 30px;"></div>
                <table style="float: right">
                    <tr style="text-align: right">
                        <td colspan="2">
                            '.$namaKota.', '.date("d F Y").'<br>
                            <div style="height: 100px;"></div>
                            <u>( Owner )</u>
                        </td>
                    </tr>
                </table>
            </body>
            </html>';
        $pdf = PDF::loadHTML($output)->setPaper('A4', 'portrait');
        $pdf->output();
        // $domPdf = $pdf->getDomPDF();
        // $canvas = $domPdf->getCanvas();
        // $pageHeight = $canvas->get_height();
        // $pageWidth = $canvas->get_width();
        // $marginHorizontal = 2 * 72; // Mengonversi margin dalam cm ke pt (1 cm = 28.35 pt)
        // $marginVertical = 1 * 72;
        // $y = $pageHeight - $marginVertical;
        // $canvas->page_text($pageWidth/2, $y, "{PAGE_NUM} of {PAGE_COUNT}", null, 12, array(0, 0, 0));

        return $pdf->stream('Laporan Penjualan '.$toko->nama_toko.' '.$tanggal.'.pdf', array('Attachment' => false));
        // return $pdf->download('Laporan Penjualan '.$toko->nama_toko.' '.$tanggal.'.pdf');
    }
}
