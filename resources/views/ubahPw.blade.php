@extends('layouts.app')

@section('title')
    Ubah Password Akun
@endsection

@section('content')
    <main id="main-container">
        <div class="bg-body-light">
            <div class="content content-full">
                <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
                    <div class="flex-grow-1">
                        <h1 class="h3 fw-bold mb-2">
                            Ubah Password Akun
                        </h1>
                        <h2 class="fs-base lh-base fw-medium text-muted mb-0">
                            Silahkan ubah password untuk keamanan akun Anda.
                        </h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="content content-full">
            <form id="form" method="POST" action="{{ route('karyawan_updatePw') }}">
                @csrf
                @method('PUT')
                <div class="col-12 block block-rounded h-100 mb-0">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">Form Ubah Password Akun</h3>
                    </div>
                    <div class="block-content">
                        <div class="row">
                            <div class="col-md mb-3">
                                <div class="form-floating">
                                    <input type="password" class="form-control form-control-alt form-control-lg" id="password_old" name="password_old" placeholder=" " autocomplete="off" required>
                                    <label for="password_old">Password Lama Anda <small class="text-danger">*</small></label>
                                </div>
                            </div>
                            <div class="col-md mb-3">
                                <div class="form-floating">
                                    <input type="password" class="form-control form-control-alt form-control-lg" id="newPw" name="newPw" placeholder=" " autocomplete="off" required>
                                    <label for="newPw">Password Baru Anda <small class="text-danger">*</small></label>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="username" value="{{ Auth::user()->username }}" required>
                        <button type="submit" class="btn btn-primary mt-3 mb-3" id="btn-simpan"><i class="fa fa-save"></i> Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </main>
@endsection

@section('script')
    <script>
        $('#btn-simpan').on('click', function(e) {
            e.preventDefault();
            if ($('#form').find('input[name="password_old"]').val() == "" || $('#form').find('input[name="newPw"]').val() == "") {
                Swal.fire('Isian wajib diisi!', '', 'error');
                $('#form').find('input[type="password"]').each(function() {
                    if ($(this).val() == "") {
                        $(this).css('border-color', '#ff0000');
                        $(this).on('focus', function() {
                            $(this).css('border-color', '#ccc');
                        });
                    }
                });
            } else {
                Swal.fire({
                    title: 'Konfirmasi',
                    text: 'Apakah password yang Anda input sudah benar?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, benar',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.value) {
                        $('#form').submit();
                    }
                });
            }
        });
    </script>
@endsection
