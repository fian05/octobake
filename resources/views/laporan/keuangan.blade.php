@extends('layouts.app')

@section('title')
    Laporan Keuangan Harian
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

@section('content')
    <main id="main-container">
        <div class="bg-body-light">
            <div class="content content-full">
                <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
                    <div class="flex-grow-1">
                        <h1 class="h3 fw-bold mb-2">
                            Data Laporan Keuangan Harian
                        </h1>
                        <h2 class="fs-base lh-base fw-medium text-muted mb-0">
                            Halaman untuk melihat data laporan keuangan harian di {{ app('App\Models\Toko')::first()->nama_toko }}.
                        </h2>
                    </div>
                    <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-alt">
                            <li class="breadcrumb-item">
                                <a href="{{ route('dashboard') }}" class="link-fx">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item" aria-current="page">
                                Laporan Keuangan Harian
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <div class="content">
            <form action="{{ route('laporan_view') }}" method="GET">
                <div class="mb-3">
                    <label for="tanggal" class="form-label">Pilih Tanggal:</label>
                    <input type="text" class="form-control" id="tanggal" name="tanggal" value="{{ $tanggal }}" required readonly>
                </div>
                <button type="submit" class="btn btn-primary">Tampilkan Laporan</button>
            </form>
        </div>
        <div class="content">
            <div class="block block-rounded">
                <div class="block-header block-header-default">
                    <h3 class="block-title">
                        Laporan Keuangan Tanggal {{ date('d F Y', strtotime($tanggal)) }}
                    </h3>
                    <div class="block-options">
                        <form action="{{ route('laporan_download') }}" method="POST">
                            @csrf
                            <input type="hidden" name="jenis_laporan" value="harian" required>
                            <input type="hidden" name="tanggal" value="{{ $tanggal }}" required>
                            <button type="submit" class="btn text-primary btn-block-option" title="Export Laporan ke PDF"><i class="fa fa-download"></i> Download</button>
                        </form>
                    </div>
                </div>
                <div class="block-content">
                    <div class="table-responsive mb-3">
                        <table id="example" class="table table-bordered table-striped table-vcenter">
                            <thead>
                                <tr>
                                    <th style="text-align: center;">No.</th>
                                    <th style="text-align: center;">Nama Produk</th>
                                    <th style="text-align: center;">Harga Satuan</th>
                                    <th style="text-align: center;">Jumlah Dibeli</th>
                                    <th style="text-align: center;">Diskon</th>
                                    <th style="text-align: center;">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pembelians as $pembelian)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $pembelian->nama_produk }}</td>
                                        <td style="text-align: right;">Rp{{ number_format($pembelian->harga_satuan, 0, ',', '.') }}</td>
                                        <td style="text-align: center;">{{ $pembelian->jumlah_dibeli }} produk</td>
                                        <td style="text-align: center;">{{ $pembelian->diskon }}%</td>
                                        <td style="text-align: right;">Rp{{ number_format($pembelian->total, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="5">Jumlah</th>
                                    <th id="total" style="text-align: right;">Rp{{ number_format($totalLaba, 0, ',', '.') }}</th>
                                </tr>
                                <tr>
                                    <th colspan="5">60%</th>
                                    <th id="laba-kotor" style="text-align: right;">Rp{{ number_format($labaKotor, 0, ',', '.') }}</th>
                                </tr>
                                <tr>
                                    <th colspan="5">40%</th>
                                    <th id="laba-bersih" style="text-align: right;">Rp{{ number_format($labaBersih, 0, ',', '.') }}</th>
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
            $('#tanggal').daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                autoUpdateInput: false,
                locale: {
                    format: 'YYYY-MM-DD',
                    cancelLabel: 'Clear'
                },
                maxDate: moment(),
            });
            // Menangani perubahan tanggal pada Daterangepicker
            $('#tanggal').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('YYYY-MM-DD'));
            });
            // Menangani penghapusan tanggal pada Daterangepicker
            $('#tanggal').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val("{{ date('Y-m-d') }}");
            });
            var table = $('.table').DataTable({
                ordering: false,
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
    </script>
@endsection