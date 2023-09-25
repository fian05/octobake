@extends('layouts.app')

@section('title')
    Data Produk
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
                            Manajemen Data Produk
                        </h1>
                        <h2 class="fs-base lh-base fw-medium text-muted mb-0">
                            Halaman untuk manajemen data produk Octobake.
                        </h2>
                    </div>
                    <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-alt">
                            <li class="breadcrumb-item">
                                <a href="{{ route('dashboard') }}" class="link-fx">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item" aria-current="page">
                                Manajemen Data Produk
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
                        List Data Produk
                    </h3>
                    <div class="block-options">
                        <a role="button" id="btnTambahData" class="btn text-primary btn-block-option" data-bs-toggle="modal" data-bs-target="#modal"><i class="fa fa-plus"></i> Tambah Produk</a>
                    </div>
                </div>
                <div class="block-content">
                    <div class="table-responsive mb-3">
                        <table class="table table-bordered table-striped table-vcenter">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Nama Produk</th>
                                    <th>Harga Produk</th>
                                    <th>Gambar Produk</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($produks as $produk)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $produk->nama_produk }}</td>
                                        <td>{{ $produk->harga_produk }}</td>
                                        <td><img src="{{ asset('media/photos/upload/'.$produk->gambar_produk) }}" width="150px" alt="Gambar produk {{ $produk->nama_produk }}"></td>
                                        <td>
                                            <div class="dropdown dropstart">
                                                <label role="button" class="text-info dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Pilih Opsi"><i class="fa fa-gear"></i> Opsi</label>
                                                <div class="dropdown-menu fs-sm" aria-labelledby="btnAksi">
                                                    <a role="button" class="dropdown-item text-warning btnEditData" data-bs-toggle="modal" data-bs-target="#modalUbah" data-bs-id="{{ $produk->id }}" onclick="ubah({{ json_encode($produk) }})"><i class="fa fa-pencil"></i> Ubah Data Produk</a>
                                                    <div class="dropdown-divider"></div>
                                                    <form id="delete-form-{{ $produk->id }}" action="{{ route('produk_hapus', $produk->id) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <input type="text" name="produkHps" value="{{ $produk->nama }}" hidden>
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
            <form id="form-tambah" method="POST" action="{{ route('produk_tambah') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5">Form Tambah Data Produk</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md mb-3">
                                <label>Nama Produk <small class="text-danger">*</small></label>
                                <input type="text" class="form-control form-control-alt form-control-lg" name="nama_produk" placeholder=" " autocomplete="off" required>
                            </div>
                            <div class="col-md mb-3">
                                <label>Harga Produk <small class="text-danger">*</small></label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="text" class="form-control form-control-alt form-control-lg" name="harga_produk" id="harga_produk" placeholder=" " autocomplete="off" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md mb-3">
                                <label>Gambar Produk <small class="text-danger">*</small></label>
                                <input type="file" class="form-control form-control-alt form-control-lg" accept="image/png, image/jpg, image/jpeg" name="gambar_produk" placeholder=" " autocomplete="off" required>
                            </div>
                        </div>
                        <small class="fst-italic"><span class="text-danger">*</span> Wajib Diisi</small>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="fa fa-x"></i> Batal</button>
                        <button type="submit" class="btn btn-success" id="btn-tambah"><i class="fa fa-save"></i> Tambah Produk</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="modalUbah" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form id="form-ubah" method="POST" action="{{ route('produk_ubah') }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5">Form Ubah Data Produk</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md mb-3">
                                <label>Nama Produk <small class="text-danger">*</small></label>
                                <input type="text" class="form-control form-control-alt form-control-lg" name="nama_produk" id="nama_produk" placeholder=" " autocomplete="off" required>
                            </div>
                            <div class="col-md mb-3">
                                <label>Harga Produk <small class="text-danger">*</small></label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="text" class="form-control form-control-alt form-control-lg" name="harga_produk" id="harga_produk2" placeholder=" " autocomplete="off" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md mb-3">
                                <label>Gambar Produk <small class="text-danger">*</small></label>
                                <input type="file" class="form-control form-control-alt form-control-lg" accept="image/png, image/jpg, image/jpeg" name="gambar_produk" placeholder=" " autocomplete="off">
                                <p><a href="" target="_blank" id="namaFile"></a></p>
                            </div>
                        </div>
                        <small class="fst-italic"><span class="text-danger">*</span> Wajib Diisi</small>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="fa fa-x"></i> Batal</button>
                        <button type="submit" class="btn btn-success" id="btn-ubah"><i class="fa fa-save"></i> Ubah Data Produk</button>
                    </div>
                </div>
                <input type="hidden" name="id" id="id" autocomplete="off" required>
                <input type="hidden" name="gambar" id="gambar" autocomplete="off" required>
            </form>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('.table').DataTable({
                columnDefs: [
                    { orderable: false, targets: [3, 4] },
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
        var inputHarga = document.getElementById('harga_produk');
        inputHarga.addEventListener('input', function () {
            this.value = formatAngka(this.value);
        });
        function formatAngka(angka) {
            if (!angka) return '';
            angka = angka.replace(/[^\d,]/g, ''); // Hapus karakter selain angka dan koma
            angka = angka.replace(/,/g, ''); // Hapus semua koma yang ada
            angka = angka.replace(/\B(?=(\d{3})+(?!\d))/g, '.'); // Tambahkan titik untuk ribuan
            return angka;
        }
        var btnTambahProduk = document.getElementById('btn-tambah');
        btnTambahProduk.addEventListener('click', function (e) {
            e.preventDefault(); // Mencegah pengiriman form langsung
            // Tampilkan SweetAlert untuk konfirmasi
            Swal.fire({
                title: 'Konfirmasi',
                text: 'Apakah data yang Anda input sudah benar?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
            }).then((result) => {
                if (result.isConfirmed) {
                    inputHarga.value = inputHarga.value.replace(/[^\d]/g, '');
                    document.getElementById('form-tambah').submit();
                }
            });
        });
        var inputHarga2 = document.getElementById('harga_produk2');
        function ubah(data) {
            document.getElementById('nama_produk').value = data['nama_produk'];
            inputHarga2.addEventListener('input', function () {
                this.value = formatAngka(this.value);
            });
            document.getElementById('harga_produk2').value = formatAngka(data['harga_asli']);
            document.getElementById('namaFile').href = "{{ asset('media/photos/upload') }}/"+data['gambar_produk'];
            document.getElementById('namaFile').innerHTML = data['gambar_produk'];
            document.getElementById('id').value = data['id'];
            document.getElementById('gambar').value = data['gambar_produk'];
        }
        var btnUbahProduk = document.getElementById('btn-ubah');
        btnUbahProduk.addEventListener('click', function (e) {
            e.preventDefault(); // Mencegah pengiriman form langsung
            // Tampilkan SweetAlert untuk konfirmasi
            Swal.fire({
                title: 'Konfirmasi',
                text: 'Apakah data yang Anda input sudah benar?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
            }).then((result) => {
                if (result.isConfirmed) {
                    inputHarga2.value = inputHarga2.value.replace(/[^\d]/g, '');
                    document.getElementById('form-ubah').submit();
                }
            });
        });
        $(document).on('click', '.delete-link', function(e) {
            e.preventDefault();
            var id = $(this).attr('id').split('-')[2];
            var name = $('#delete-form-' + id).find('input[name="produkHps"]').val();
            Swal.fire({
                title: 'Konfirmasi',
                html: 'Anda yakin ingin menghapus data produk <b>'+name+'</b> ?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus produk',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.value) {
                    $('#delete-form-' + id).submit();
                }
            });
        });
    </script>
@endsection