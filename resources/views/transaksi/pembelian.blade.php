@extends('layouts.app')

@section('title')
    Data Pembelian
@endsection

@section('head')
    <link rel="stylesheet" href="{{ asset('js/plugins/datatables-bs5/css/dataTables.bootstrap5.min.css') }}">
    <script src="{{ asset('js/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
@endsection

@section('content')
    <main id="main-container">
        <div class="bg-body-light">
            <div class="content content-full">
                <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
                    <div class="flex-grow-1">
                        <h1 class="h3 fw-bold mb-2">
                            Data Pembelian
                        </h1>
                        <h2 class="fs-base lh-base fw-medium text-muted mb-0">
                            Halaman untuk manajemen transaksi pembelian di Octobake.
                        </h2>
                    </div>
                    <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-alt">
                            <li class="breadcrumb-item">
                                <a href="{{ route('dashboard') }}" class="link-fx">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item" aria-current="page">
                                Transaksi Pembelian
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
                        List Transaksi Pembelian
                    </h3>
                    <div class="block-options">
                        <a role="button" id="btnTambahData" class="btn text-primary btn-block-option" data-bs-toggle="modal" data-bs-target="#modal"><i class="fa fa-plus"></i> Tambah Data</a>
                    </div>
                </div>
                <div class="block-content">
                    <div class="table-responsive mb-3">
                        <table class="table table-bordered table-striped table-vcenter">
                            <thead>
                                <tr>
                                    <th rowspan="2">Tanggal</th>
                                    <th rowspan="2">Nama Produk</th>
                                    <th rowspan="2">Harga</th>
                                    <th rowspan="2">Jumlah</th>
                                    <th colspan="2" class="text-center">Diskon</th>
                                    <th rowspan="2">Total</th>
                                    <th rowspan="2">Aksi</th>
                                </tr>
                                <tr>
                                    <th class="text-center">20%</th>
                                    <th class="text-center">50%</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pembelians as $pembelian)
                                    <tr>
                                        <td>{{ $pembelian->created_at }}</td>
                                        <td>{{ $pembelian->nama_produk }}</td>
                                        <td>{{ $pembelian->harga_satuan }}</td>
                                        <td>{{ $pembelian->jumlah_dibeli }}</td>
                                        <td>{{ $pembelian->diskon }}</td>
                                        <td>{{ $pembelian->total }}</td>
                                        <td></td>
                                            <div class="dropdown dropstart">
                                                <label role="button" class="text-info dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Pilih Opsi"><i class="fa fa-gear"></i> Opsi</label>
                                                <div class="dropdown-menu fs-sm" aria-labelledby="btnAksi">
                                                    <a role="button" class="dropdown-item text-warning btnEditData" data-bs-toggle="modal" data-bs-target="#modalUbah" data-bs-id="{{ $pembelian->id }}" onclick="ubah({{ json_encode($pembelian) }})"><i class="fa fa-pencil"></i> Ubah Data Produk</a>
                                                    <div class="dropdown-divider"></div>
                                                    <form id="delete-form-{{ $pembelian->id }}" action="{{ route('produk_hapus', $pembelian->id) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <input type="text" name="produkHps" value="{{ $pembelian->nama_produk }}" hidden>
                                                        <a role="button" class="dropdown-item text-danger delete-link" id="delete-link-{{ $pembelian->id }}" title="Hapus Produk"><i class="fa fa-trash"></i> Hapus Produk</a>
                                                    </form>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <div class="modal fade" id="modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form id="form-pembelian" method="POST" action="{{ route('pembelian_tambah') }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5">Form Tambah Pembelian</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md mb-3">
                                <label for="tanggal_pembelian" class="form-label">Tanggal Pembelian</label>
                                <input type="date" class="form-control" id="tanggal_pembelian" value="{{ date("Y-m-d") }}" name="tanggal_pembelian" required>
                            </div>
                            <div class="col-md mb-3">
                                <label for="nama_produk" class="form-label">Produk</label>
                                <select class="form-select" id="nama_produk" name="nama_produk" required>
                                    <option value="" selected disabled>- Pilih Produk -</option>
                                    @foreach ($produks as $produk)
                                        <option value="{{ $produk->id }}">{{ $produk->nama_produk }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Simpan Pembelian</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('.table').DataTable({
                order: [[0, 'desc']],
                columnDefs: [
                    { orderable: false, targets: [0, 1, 2, 3, 4, 5, 6, 7] },
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
                }
            });
        });
    </script>
@endsection