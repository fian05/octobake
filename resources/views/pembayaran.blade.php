@extends('layouts.app')

@section('title')
    Data Pembayaran
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
                            Data Pembayaran
                        </h1>
                        <h2 class="fs-base lh-base fw-medium text-muted mb-0">
                            Halaman untuk manajemen transaksi pembayaran di Octobake.
                        </h2>
                    </div>
                    <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-alt">
                            <li class="breadcrumb-item">
                                <a href="{{ route('dashboard') }}" class="link-fx">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item" aria-current="page">
                                Transaksi Pembayaran
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
                        List Transaksi Pembayaran
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
                                    <th>Invoice</th>
                                    <th>Produk</th>
                                    <th>DP</th>
                                    <th>Total Tagihan</th>
                                    <th>Atas Nama</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pembayarans as $pembayaran)
                                    <tr>
                                        <td>{{ $pembayaran->invoice }}</td>
                                        <td>{{ $pembayaran->produk }}</td>
                                        <td>{{ $pembayaran->dp }}</td>
                                        <td>{{ $pembayaran->total }}</td>
                                        <td>{{ $pembayaran->atas_nama }}</td>
                                        <td>
                                            <div class="dropdown dropstart">
                                                <label role="button" class="text-info dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Pilih Opsi"><i class="fa fa-gear"></i> Opsi</label>
                                                <div class="dropdown-menu fs-sm" aria-labelledby="btnAksi">
                                                    <a role="button" class="dropdown-item text-warning btnEditData" data-bs-toggle="modal" data-bs-target="#modalUbah" data-bs-id="{{ $pembayaran->id }}" onclick="ubah({{ json_encode($pembayaran) }})"><i class="fa fa-pencil"></i> Ubah Data Produk</a>
                                                    <div class="dropdown-divider"></div>
                                                    <form id="delete-form-{{ $pembayaran->id }}" action="{{ route('produk_hapus', $pembayaran->id) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <input type="text" name="produkHps" value="{{ $pembayaran->nama_produk }}" hidden>
                                                        <a role="button" class="dropdown-item text-danger delete-link" id="delete-link-{{ $produk->id }}" title="Hapus Produk"><i class="fa fa-trash"></i> Hapus Produk</a>
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
            <form id="form-pembayaran" method="POST" action="{{ route('pembayaran_tambah') }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5">Form Pembayaran Penjualan Roti</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md mb-3">
                                <label>Pelanggan <small class="text-danger">*</small></label>
                                <input type="text" class="form-control form-control-alt form-control-lg" name="nama_pelanggan" placeholder="Nama Pelanggan" autocomplete="off" required>
                            </div>
                            <div class="col-md mb-3">
                                <label>Tanggal Penjualan <small class="text-danger">*</small></label>
                                <input type="date" class="form-control form-control-alt form-control-lg" name="tanggal_penjualan" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md mb-3">
                                <label>Produk <small class="text-danger">*</small></label>
                                <input list="produk-list" class="form-control form-control-alt form-control-lg" name="produk" id="produk" placeholder="Pilih Produk" autocomplete="off" required>
                                <datalist id="produk-list">
                                    <option value="Roti Coklat">
                                    <option value="Roti Keju">
                                    <option value="Roti Gandum">
                                    <!-- Tambahkan opsi produk lainnya di sini -->
                                </datalist>
                            </div>
                            <div class="col-md mb-3">
                                <label>Jumlah Beli <small class="text-danger">*</small></label>
                                <input type="number" class="form-control form-control-alt form-control-lg" name="jumlah_beli" id="jumlah_beli" placeholder="Jumlah Beli" autocomplete="off" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md mb-3">
                                <label>Harga Satuan (Rp) <small class="text-danger">*</small></label>
                                <input type="text" class="form-control form-control-alt form-control-lg" name="harga_satuan" id="harga_satuan" placeholder="Harga Satuan" autocomplete="off" readonly required>
                            </div>
                            <div class="col-md mb-3">
                                <label>DP (Rp) <small class="text-danger">*</small></label>
                                <input type="text" class="form-control form-control-alt form-control-lg" name="dp" id="dp" placeholder="DP" autocomplete="off" readonly required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md mb-3">
                                <label>Total Tagihan (Rp) <small class="text-danger">*</small></label>
                                <input type="text" class="form-control form-control-alt form-control-lg" name="total_tagihan" id="total_tagihan" placeholder="Total Tagihan" autocomplete="off" readonly required>
                            </div>
                            <div class="col-md mb-3">
                                <label>Jumlah Dibayar (Rp) <small class="text-danger">*</small></label>
                                <input type="text" class="form-control form-control-alt form-control-lg" name="jumlah_dibayar" placeholder="Jumlah Dibayar" autocomplete="off" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="fa fa-times"></i> Batal</button>
                        <button type="submit" class="btn btn-success" id="btn-simpan"><i class="fa fa-save"></i> Simpan Pembayaran</button>
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
                columnDefs: [
                    { orderable: false, targets: [1, 5] },
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