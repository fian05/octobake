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
        <div class="content">
            <div class="block block-rounded">
                <div class="block-header block-header-default">
                    <h3 class="block-title">
                        List Laporan Keuangan
                    </h3>
                    {{-- <div class="block-options">
                        <a role="button" id="btnPDF" class="btn text-primary btn-block-option" data-bs-toggle="modal" data-bs-target="#modal"><i class="fa fa-download"></i> PDF</a>
                    </div> --}}
                </div>
                <div class="block-content">
                    <div class="table-responsive mb-3">
                        <table id="example" class="table table-bordered table-striped table-vcenter">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Nama Produk</th>
                                    <th>Harga</th>
                                    <th>Jumlah</th>
                                    <th>Diskon</th>
                                    <th>Total</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>2</td>
                                    <td>3</td>
                                    <td>4</td>
                                    <td>5</td>
                                    <td>6</td>
                                    <td>Gass</td>
                                </tr>
                            </tbody>
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
            $('.table').DataTable({
                order: [[0, 'desc']],
                columnDefs: [
                    { orderable: false, targets: [0, 1, 2, 3, 4, 5, 6] },
                ],
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
                buttons: [{
                    extend: 'pdfHtml5',
                    text: 'Export Data ke PDF',
                    exportOptions: {
                        columns: [0, 1, 2, 3],
                    },
                    filename: 'Laporan Keuangan Octobake - {{ date("d F Y H.i.s") }} WIB',
                    title: 'Laporan Keuangan Octobake',
                },],
            }).buttons().container().appendTo('#example_wrapper .col-md-6:eq(0)');
        });
    </script>
@endsection