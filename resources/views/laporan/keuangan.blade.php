@extends('layouts.app')

@section('title')
    Laporan Keuangan
@endsection

@section('head')
    <link rel="stylesheet" href="{{ asset('js/plugins/datatables-bs5/css/dataTables.bootstrap5.min.css') }}">
    <script src="{{ asset('js/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
    {{-- Keperluan Tombol DataTables --}}
    <link rel="stylesheet" href="{{ asset('js/plugins/datatables-buttons-bs5/css/buttons.bootstrap5.min.css') }}">
    <script src="{{ asset('js/plugins/datatables-buttons/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-buttons-bs5/js/buttons.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-buttons-pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-buttons/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-buttons-pdfmake/vfs_fonts.js') }}"></script>

@endsection

@section('content')
    <main id="main-container">
        <div class="bg-body-light">
            <div class="content content-full">
                <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
                    <div class="flex-grow-1">
                        <h1 class="h3 fw-bold mb-2">
                            Data Laporan Keuangan
                        </h1>
                        <h2 class="fs-base lh-base fw-medium text-muted mb-0">
                            Halaman untuk manajemen data laporan keuangan di Octobake.
                        </h2>
                    </div>
                    <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-alt">
                            <li class="breadcrumb-item">
                                <a href="{{ route('dashboard') }}" class="link-fx">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item" aria-current="page">
                                Laporan Keuangan
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <div class="container">
            <form action="{{ route('laporan_view') }}" method="GET">
                <div class="mb-3">
                    <label for="tanggal" class="form-label">Pilih Tanggal:</label>
                    <input type="date" class="form-control" id="tanggal" name="tanggal" value="{{ $tanggal }}">
                </div>
                <button type="submit" class="btn btn-primary">Tampilkan Laporan</button>
            </form>
        </div>
        <div class="content">
            <div class="block block-rounded">
                <div class="block-header block-header-default">
                    <h3 class="block-title">
                        Laporan Keuangan Tanggal {{ $tanggal }}
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
                                    <th colspan="4">Total</th>
                                    <th id="total">Rp{{ number_format($totalLaba, 0, ',', '.') }}</th>
                                </tr>
                                <tr>
                                    <th colspan="4">60%</th>
                                    <th id="laba-kotor">Rp{{ number_format($labaKotor, 0, ',', '.') }}</th>
                                </tr>
                                <tr>
                                    <th colspan="4">40%</th>
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
                dom: 'Bfrtip',
                buttons: [{
                    extend: 'pdfHtml5',
                    text: 'Export Data ke PDF',
                    exportOptions: {
                        columns: [0, 1, 2, 3],
                    },
                    customize: function(doc) {
                        // Hitung total laba
                        var totalLaba = 0;
                        table.column(4, { page: 'current' }).data().each(function(value) {
                            totalLaba += parseInt(value.replace(/\D/g, ''), 10);
                        });
                        // Hitung laba kotor (60%)
                        var labaKotor = (totalLaba * 60) / 100;
                        // Hitung laba bersih (40%)
                        var labaBersih = (totalLaba * 40) / 100;

                        // Tambahkan elemen-elemen ke PDF
                        doc.content.splice(1, 0, {
                            text: [
                                'Total Laba: Rp' + $.fn.dataTable.render.number(',', '.', 0).display(totalLaba),
                                'Laba Kotor (60%): Rp' + $.fn.dataTable.render.number(',', '.', 0).display(labaKotor),
                                'Laba Bersih (40%): Rp' + $.fn.dataTable.render.number(',', '.', 0).display(labaBersih),
                            ],
                            margin: [0, 0, 0, 12],
                            alignment: 'right'
                        });
                    }
                }],
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
            });

            table.buttons().container().appendTo('#example_wrapper .col-md-6:eq(0)');
        });
    </script>
@endsection