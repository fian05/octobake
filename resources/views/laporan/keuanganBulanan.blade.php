@extends('layouts.app')

@section('title')
    Laporan Keuangan Harian
@endsection

@section('head')
    <link rel="stylesheet" href="{{ asset('js/plugins/datatables-bs5/css/dataTables.bootstrap5.min.css') }}">
    <script src="{{ asset('js/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
    {{-- Keperluan Pilih Bulan Tahun --}}
    <link rel="stylesheet" href="{{ asset('js/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}">
    <script src="{{ asset('js/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
@endsection

@section('content')
    <main id="main-container">
        <div class="bg-body-light">
            <div class="content content-full">
                <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
                    <div class="flex-grow-1">
                        <h1 class="h3 fw-bold mb-2">
                            Data Laporan Keuangan Bulanan
                        </h1>
                        <h2 class="fs-base lh-base fw-medium text-muted mb-0">
                            Halaman untuk melihat data laporan keuangan bulanan di {{ app('App\Models\Toko')::first()->nama_toko }}.
                        </h2>
                    </div>
                    <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-alt">
                            <li class="breadcrumb-item">
                                <a href="{{ route('dashboard') }}" class="link-fx">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item" aria-current="page">
                                Laporan Keuangan Bulanan
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <div class="content">
            <form action="{{ route('laporan_view_bulanan') }}" method="GET">
                <div class="mb-3">
                    <label for="bulan_tahun" class="form-label">Pilih Bulan dan Tahun:</label>
                    <input type="text" class="form-control" id="bulan_tahun" name="bulan_tahun" value="{{ $bulan_tahun }}" required readonly>
                </div>
                <button type="submit" class="btn btn-primary">Tampilkan Laporan</button>
            </form>            
        </div>
        <div class="content">
            <div class="block block-rounded">
                <div class="block-header block-header-default">
                    <h3 class="block-title">
                        Laporan Keuangan Bulan {{ date('F Y', strtotime($bulan_tahun)) }}
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
                                    <th colspan="5">Total</th>
                                    <th id="total">Rp{{ number_format($totalLaba, 0, ',', '.') }}</th>
                                </tr>
                                <tr>
                                    <th colspan="5">60%</th>
                                    <th id="laba-kotor">Rp{{ number_format($labaKotor, 0, ',', '.') }}</th>
                                </tr>
                                <tr>
                                    <th colspan="5">40%</th>
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
            $("#bulan_tahun").datepicker({
                format: "yyyy-mm",
                viewMode: "months",
                minViewMode: "months",
                autoclose: true,
                endDate: new Date(new Date().setDate(new Date().getMonth())),
            });
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
    </script>
@endsection