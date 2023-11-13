@extends('layouts.app')

@section('title')
    Manajemen Data Toko
@endsection

@section('content')
    <main id="main-container">
        <div class="bg-body-light">
            <div class="content content-full">
                <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
                    <div class="flex-grow-1">
                        <h1 class="h3 fw-bold mb-2">
                            Manajemen Data Toko
                        </h1>
                        <h2 class="fs-base lh-base fw-medium text-muted mb-0">
                            Halaman untuk manajemen data toko.
                        </h2>
                    </div>
                    <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-alt">
                            <li class="breadcrumb-item">
                                <a href="{{ route('dashboard') }}" class="link-fx">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item" aria-current="page">
                                Manajemen Data Toko
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
                        Data Toko
                    </h3>
                    <div class="block-options">
                        <a role="button" id="btnUbahData" class="btn text-primary btn-block-option" data-bs-toggle="modal" data-bs-target="#modalUbah"><i class="fa fa-pencil"></i> Ubah Data</a>
                    </div>
                </div>
                <div class="block-content">
                    <div class="table-responsive mb-3">
                        @foreach ($toko as $data)
                            <table class="table table-bordered table-vcenter">
                                <tr>
                                    <td>Nama Toko</td>
                                    <td>{{ $data->nama_toko }}</td>
                                </tr>
                                <tr>
                                    <td>Logo Toko</td>
                                    <td><img src="{{ asset('media/photos/upload/'.$data->logo_toko) }}" width="100px" alt="Logo"></td>
                                </tr>
                                <tr>
                                    <td>Nomor Telepon Toko</td>
                                    <td><a href="http://wa.me/62{{ $data->nohp_toko }}" target="_blank">0{{ $data->nohp_toko }}</a></td>
                                </tr>
                                <tr>
                                    <td>Instagram Toko</td>
                                    <td><a href="https://www.instagram.com/{{ $data->ig_toko }}" target="_blank">{{ '@'.$data->ig_toko }}</a></td>
                                </tr>
                                <tr>
                                    <td>Alamat Toko</td>
                                    <td><a href="https://www.google.com/maps/search/{{ $data->nama_toko }} {{ $data->alamat_toko }}" target="_blank">{{ $data->alamat_toko }}</a></td>
                                </tr>
                            </table>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </main>

    <div class="modal fade" id="modalUbah" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-lg">
            @foreach ($toko as $data)
                <form id="form-ubah" method="POST" action="{{ route('toko_ubah') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="logo" value="{{ $data->logo_toko }}" required>
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5">Form Ubah Data Toko</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md mb-3">
                                    <label>Nama Toko <small class="text-danger">*</small></label>
                                    <input type="text" class="form-control form-control-alt form-control-lg" name="nama_toko" id="nama_toko" autocomplete="off" value="{{ $data->nama_toko }}" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md mb-3">
                                    <label>Nomor Telepon Toko <small class="text-danger">*</small></label>
                                    <div class="input-group">
                                        <span class="input-group-text">+62</span>
                                        <input type="text" class="form-control form-control-alt form-control-lg" name="nohp_toko" id="nohp_toko" pattern="[0-9]+" autocomplete="off" value="{{ $data->nohp_toko }}" required>
                                    </div>
                                </div>
                                <div class="col-md mb-3">
                                    <label>Instagram Toko</label>
                                    <div class="input-group">
                                        <span class="input-group-text">@</span>
                                        <input type="text" class="form-control form-control-alt form-control-lg" id="ig_toko" name="ig_toko" autocomplete="off" value="{{ $data->ig_toko }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md mb-3">
                                    <label>Alamat Toko <small class="text-danger">*</small></label>
                                    <textarea class="form-control form-control-alt form-control-lg" name="alamat_toko" id="alamat_toko" required>{{ $data->alamat_toko }}</textarea>
                                </div>
                                <div class="col-md mb-3">
                                    <label>Logo Toko</label>
                                    <input type="file" class="form-control form-control-alt form-control-lg mb-3" name="logo_toko" id="logo_toko" accept="image/png, image/jpg, image/jpeg">
                                    <a href="{{ asset('media/photos/upload/'.$data->logo_toko) }}" target="_blank" class="text-info fst-italic" title="Lihat Logo"><img src="{{ asset('media/photos/upload/'.$data->logo_toko) }}" width="25px" alt="Logo"> {{ $data->logo_toko }}</a>
                                </div>
                            </div>
                            <small class="fst-italic"><span class="text-danger">*</span> Wajib Diisi</small>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="fa fa-x"></i> Batal</button>
                            <button type="submit" class="btn btn-success" id="btn-ubah"><i class="fa fa-save"></i> Ubah Data Toko</button>
                        </div>
                    </div>
                </form>
            @endforeach
        </div>
    </div>
@endsection