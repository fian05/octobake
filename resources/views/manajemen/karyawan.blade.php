@extends('layouts.app')

@section('title')
    Data Karyawan
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
                            Manajemen Data Karyawan
                        </h1>
                        <h2 class="fs-base lh-base fw-medium text-muted mb-0">
                            Halaman untuk manajemen data karyawan di {{ app('App\Models\Toko')::first()->nama_toko }}.
                        </h2>
                    </div>
                    <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-alt">
                            <li class="breadcrumb-item">
                                <a href="{{ route('dashboard') }}" class="link-fx">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item" aria-current="page">
                                Manajemen Data Karyawan
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
                        List Data Karyawan
                    </h3>
                    <div class="block-options">
                        <a role="button" id="btnTambahData" class="btn text-primary btn-block-option" data-bs-toggle="modal" data-bs-target="#modal"><i class="fa fa-plus"></i> Tambah Karyawan</a>
                    </div>
                </div>
                <div class="block-content">
                    <div class="table-responsive mb-3">
                        <table class="table table-bordered table-striped table-vcenter">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Nama Karyawan</th>
                                    <th>Username</th>
                                    <th>Role</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($karyawans as $karyawan)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $karyawan->nama }}</td>
                                        <td>{{ $karyawan->username }}</td>
                                        <td>{{ ucfirst($karyawan->role) }}</td>
                                        <td>
                                            <div class="dropdown dropstart">
                                                <label role="button" class="text-info dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Pilih Opsi"><i class="fa fa-gear"></i> Opsi</label>
                                                <div class="dropdown-menu fs-sm">
                                                    <a role="button" class="dropdown-item text-warning btnEditData" data-bs-toggle="modal" data-bs-target="#modalUbah" data-bs-id="{{ $karyawan->id }}" onclick="ubah({{ json_encode($karyawan) }})"><i class="fa fa-pencil"></i> Ubah Data</a>
                                                    <div class="dropdown-divider"></div>
                                                    <form id="reset-form-{{ $karyawan->id }}" action="{{ route('karyawan_reset', $karyawan->id) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="text" name="karyawanRst" value="{{ $karyawan->username }}" hidden>
                                                        <a role="button" class="dropdown-item text-secondary reset-link" id="reset-link-{{ $karyawan->id }}" title="Reset Password"><i class="fa fa-key"></i> Reset Password</a>
                                                    </form>
                                                    @if ($karyawan->role != 'owner')
                                                    <form id="delete-form-{{ $karyawan->id }}" action="{{ route('karyawan_hapus', $karyawan->id) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <input type="text" name="karyawanHps" value="{{ $karyawan->nama }}" hidden>
                                                        <a role="button" class="dropdown-item text-danger delete-link" id="delete-link-{{ $karyawan->id }}" title="Hapus Data"><i class="fa fa-trash"></i> Hapus Karyawan</a>
                                                    </form>
                                                    @endif
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
            <form id="form-tambah" method="POST" action="{{ route('karyawan_tambah') }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5">Form Tambah Data Karyawan</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md mb-3">
                                <label>Nama Karyawan <small class="text-danger">*</small></label>
                                <input type="text" class="form-control form-control-alt form-control-lg" name="nama_karyawan" autocomplete="off" required>
                            </div>
                            <div class="col-md mb-3">
                                <label>Username <small class="text-danger">*</small></label>
                                <input type="text" class="form-control form-control-alt form-control-lg" name="username_karyawan" autocomplete="off" required>
                            </div>
                        </div>
                        <small class="fst-italic"><span class="text-danger">*</span> Wajib Diisi</small>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="fa fa-x"></i> Batal</button>
                        <button type="submit" class="btn btn-success" id="btn-tambah"><i class="fa fa-save"></i> Tambah Karyawan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="modalUbah" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form id="form-ubah" method="POST" action="{{ route('karyawan_ubah') }}">
                @csrf
                @method('PUT')
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5">Form Ubah Data Karyawan</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md mb-3">
                                <label>Nama Karyawan <small class="text-danger">*</small></label>
                                <input type="text" class="form-control form-control-alt form-control-lg" name="nama_karyawan" id="nama_karyawan" autocomplete="off" required>
                            </div>
                            <div class="col-md mb-3">
                                <label>Username <small class="text-danger">*</small></label>
                                <input type="text" class="form-control form-control-alt form-control-lg" name="username_karyawan" id="username_karyawan" placeholder=" " autocomplete="off" required>
                            </div>
                        </div>
                        <small class="fst-italic"><span class="text-danger">*</span> Wajib Diisi</small>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="fa fa-x"></i> Batal</button>
                        <button type="submit" class="btn btn-success" id="btn-ubah"><i class="fa fa-save"></i> Ubah Data Karyawan</button>
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
                    { orderable: false, targets: [4] },
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
        function ubah(data) {
            document.getElementById('nama_karyawan').value = data['nama'];
            document.getElementById('username_karyawan').value = data['username'];
            document.getElementById('id').value = data['id'];
        }
        $(document).on('click', '.delete-link', function(e) {
            e.preventDefault();
            var id = $(this).attr('id').split('-')[2];
            var name = $('#delete-form-' + id).find('input[name="karyawanHps"]').val();
            Swal.fire({
                title: 'Konfirmasi',
                html: 'Anda yakin ingin menghapus data karyawan <b>'+name+'</b> ?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus data',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.value) {
                    $('#delete-form-' + id).submit();
                }
            });
        });
        $(document).on('click', '.reset-link', function(e) {
            e.preventDefault();
            var id = $(this).attr('id').split('-')[2];
            var email = $('#reset-form-' + id).find('input[name="karyawanRst"]').val();
            Swal.fire({
                title: 'Konfirmasi',
                html: 'Password <b>'+email+'</b> akan diset ulang menjadi "12345678". Apakah Anda yakin?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, reset password',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.value) {
                    $('#reset-form-' + id).submit();
                }
            });
        });
    </script>
@endsection