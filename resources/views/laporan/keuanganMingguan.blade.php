@extends('layouts.app')

@section('title')
    Laporan Keuangan Mingguan
@endsection

@section('head')
    <link rel="stylesheet" href="{{ asset('js/plugins/datatables-bs5/css/dataTables.bootstrap5.min.css') }}">
    <script src="{{ asset('js/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
    {{-- Keperluan Pilih Tanggal --}}
    <script src="{{ asset('js/plugins/daterangepicker/moment.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('js/plugins/daterangepicker/daterangepicker.css') }}">
    <script src="{{ asset('js/plugins/daterangepicker/daterangepicker.min.js') }}"></script>
@endsection

@php
    $startDay = date('d', strtotime($startDate));
    $startMonth = date('F', strtotime($startDate));
    $startYear = date('Y', strtotime($startDate));
    $endDay = date('d', strtotime($endDate));
    $endMonth = date('F', strtotime($endDate));
    $endYear = date('Y', strtotime($endDate));
    if($startYear == $endYear) { // if 2023 == 2023
        if($startMonth == $endMonth) { // if November == November
            $dateRangeString = $startDay.' - '.$endDay.' '.$endMonth.' '.$endYear; // 5 - 11 November 2023
        } else { // if October != November
            $dateRangeString = $startDay.' '.$startMonth.' - '.$endDay.' '.$endMonth.' '.$endYear; // 29 October - 4 November 2023
        }
    } else { // if 2022 != 2023
        $dateRangeString = $startDay.' '.$startMonth.' '.$startYear.' - '.$endDay.' '.$endMonth.' '.$endYear; // 31 December 2022 - 1 January 2023
    }
@endphp

@section('content')
<main id="main-container">
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
                <div class="flex-grow-1">
                    <h1 class="h3 fw-bold mb-2">
                        Data Laporan Keuangan Mingguan
                    </h1>
                    <h2 class="fs-base lh-base fw-medium text-muted mb-0">
                        Halaman untuk melihat data laporan keuangan mingguan di {{ app('App\Models\Toko')::first()->nama_toko }}.
                    </h2>
                </div>
                <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}" class="link-fx">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            Laporan Keuangan Mingguan
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div class="content">
        <form action="{{ route('laporan_view_mingguan') }}" method="GET">
            <div class="mb-3">
                <label for="tanggal" class="form-label">Pilih Tanggal:</label>
                <div class="input-group">
                    <input type="text" class="form-control" id="kurun_waktu_awal" name="kurun_waktu_awal" value="{{ $startDate }}" required readonly>
                    <div class="input-group-text">s/d</div>
                    <input type="text" class="form-control" id="kurun_waktu_akhir" name="kurun_waktu_akhir" value="{{ $endDate }}" required readonly>
                    <a role="button" class="btn btn-outline-info" id="tanggalInterval">Pilih</a>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Tampilkan Laporan</button>
        </form>
    </div>
    <div class="content">
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">
                    Laporan Keuangan: {{ $dateRangeString }}
                </h3>
                <div class="block-options">
                    {{-- <a role="button" id="btnPDF" class="btn text-primary btn-block-option" data-bs-toggle="modal" data-bs-target="#modal"><i class="fa fa-download"></i> PDF</a> --}}
                </div>
            </div>
            <div class="block-content">
                <div class="table-responsive mb-3">
                    <table id="example" class="table table-bordered table-striped table-vcenter">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Tanggal Pembelian</th>
                                <th>Nama Produk</th>
                                <th>Harga Satuan</th>
                                <th>Jumlah Dibeli</th>
                                <th>Diskon</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pembelians as $pembelian)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $pembelian->tanggal_pembelian }} WIB</td>
                                    <td>{{ $pembelian->nama_produk }}</td>
                                    <td>Rp{{ number_format($pembelian->harga_satuan, 0, ',', '.') }}</td>
                                    <td>{{ $pembelian->jumlah_dibeli }}</td>
                                    <td>{{ $pembelian->diskon }}%</td>
                                    <td>Rp{{ number_format($pembelian->total, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="6">Total</th>
                                <th id="total">Rp{{ number_format($totalLaba, 0, ',', '.') }}</th>
                            </tr>
                            <tr>
                                <th colspan="6">60%</th>
                                <th id="laba-kotor">Rp{{ number_format($labaKotor, 0, ',', '.') }}</th>
                            </tr>
                            <tr>
                                <th colspan="6">40%</th>
                                <th id="laba-bersih">Rp{{ number_format($labaBersih, 0, ',', '.') }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            var table = $('.table').DataTable({
                language: {
                    lengthMenu: "Tampilkan _MENU_ data per halaman",
                    zeroRecords: "Data tidak ditemukan.",
                    info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                    infoEmpty: "Menampilkan 0 - 0 dari 0 data",
                    infoFiltered: "(difilter dari _MAX_ total data)",
                    search: "Cari",
                    decimal: ",",
                    thousands: ".",
                    paginate: {
                        previous: "Sebelumnya",
                        next: "Selanjutnya"
                    }
                },
                lengthChange: false,
                paging: false,
            });
        });
        
        document.addEventListener('DOMContentLoaded', function() {
            // Inisialisasi date range picker
            $('#tanggalInterval').daterangepicker({
                opens: 'left', // Posisi kalender
                locale: {
                    format: 'DD MMMM YYYY' // Format tanggal yang ditampilkan
                },
                ranges: {
                    'Minggu Ini': [moment().startOf('week'), moment().endOf('week')],
                    '1 Minggu Lalu': [moment().subtract(1, 'week').startOf('week'), moment().subtract(1, 'week').endOf('week')],
                    '2 Minggu Lalu': [moment().subtract(2, 'weeks').startOf('week'), moment().subtract(2, 'weeks').endOf('week')],
                    '3 Minggu Lalu': [moment().subtract(3, 'weeks').startOf('week'), moment().subtract(3, 'weeks').endOf('week')],
                    '4 Minggu Lalu': [moment().subtract(4, 'weeks').startOf('week'), moment().subtract(4, 'weeks').endOf('week')],
                    '5 Minggu Lalu': [moment().subtract(5, 'weeks').startOf('week'), moment().subtract(5, 'weeks').endOf('week')],
                },
                alwaysShowCalendars: true,
                maxDate: moment().endOf('week'),
            }, function(start, end) {
                // Validasi custom range, jika lebih dari satu minggu, atur ulang ke satu minggu
                if (end.diff(start, 'weeks') > 1) {
                    $('#tanggalInterval').data('daterangepicker').setStartDate(start);
                    var newEndDate = start.clone().add(1, 'week').endOf('week');
                    $('#tanggalInterval').data('daterangepicker').setEndDate(newEndDate);
                    $('#kurun_waktu_awal').val(start.format('YYYY-MM-DD'));
                    $('#kurun_waktu_akhir').val(newEndDate.format('YYYY-MM-DD'));
                } else {
                    $('#kurun_waktu_awal').val(start.format('YYYY-MM-DD'));
                    $('#kurun_waktu_akhir').val(end.format('YYYY-MM-DD'));
                }
            });
            // Tambahkan callback untuk menangani custom range
            $('#tanggalInterval').on('apply.daterangepicker', function(ev, picker) {
                var start = picker.startDate;
                var end = picker.endDate;
                // Batasi pemilihan hingga satu minggu
                if (end.diff(start, 'weeks') > 1) {
                    picker.setStartDate(start);
                    var newEndDate = start.clone().add(1, 'week').endOf('week');
                    picker.setEndDate(newEndDate);
                    $('#kurun_waktu_awal').val(start.format('YYYY-MM-DD'));
                    $('#kurun_waktu_akhir').val(newEndDate.format('YYYY-MM-DD'));
                } else {
                    $('#kurun_waktu_awal').val(start.format('YYYY-MM-DD'));
                    $('#kurun_waktu_akhir').val(end.format('YYYY-MM-DD'));
                }
            });
        });
    </script>
@endsection
