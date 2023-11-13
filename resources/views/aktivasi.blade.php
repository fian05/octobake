<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Aktivasi Toko</title>
    <link rel="stylesheet" id="css-main" href="{{ asset('css/oneui.min.css') }}">
    <script src="{{ asset("js/lib/jquery.min.js") }}"></script>
    <link rel="stylesheet" href="{{ asset("js/plugins/sweetalert2/sweetalert2.min.css") }}">
    <script src="{{ asset("js/plugins/sweetalert2/sweetalert2.min.js") }}"></script>
</head>
<body>
    <!-- Page Container -->
    <div id="page-container">
        <!-- Main Container -->
        <main id="main-container">
            <!-- Page Content -->
            <div class="bg-secondary">
                <div class="hero-static d-flex align-items-center bg-primary-dark-op">
                    <div class="content">
                        <div class="row justify-content-center push">
                            <div class="col-md-8 col-lg-12 col-xl-8">
                                <!-- Unlock Block -->
                                <div class="block block-rounded shadow-none mb-0">
                                    <div class="block-header block-header-default">
                                        <label>
                                            <h3 class="block-title">Aktivasi Toko</h3>
                                        </label>
                                    </div>
                                    <div class="block-content">
                                        <div>
                                            <p>Selamat datang! Silahkan lengkapi data toko untuk aktivasi web.</p>
                                            <form id="formAktivasi" action="{{ route('aktivasi_proses') }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <div class="row">
                                                    <div class="col-md mb-3">
                                                        <label>Nama Owner <small class="text-danger">*</small></label>
                                                        <input type="text"
                                                            class="form-control form-control-alt form-control-lg" name="nama_owner" id="nama_owner" autocomplete="off" required>
                                                    </div>
                                                    <div class="col-md mb-3">
                                                        <label>Username Owner <small class="text-danger">*</small></label>
                                                        <input type="text"
                                                            class="form-control form-control-alt form-control-lg" name="username_owner" id="username_owner" autocomplete="off" required>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md mb-3">
                                                        <label>Password Akun <small class="text-danger">*</small></label>
                                                        <div class="input-group">
                                                            <input type="password" class="form-control form-control-lg form-control-alt" id="password1" name="password1" placeholder="Password">
                                                            <span class="input-group-text"><i class="fa fa-eye" id="password1-eye" title="Lihat Password"></i></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md mb-3">
                                                        <label>Ketik Ulang Password <small class="text-danger">*</small></label>
                                                        <div class="input-group">
                                                            <input type="password" class="form-control form-control-lg form-control-alt" id="password2" name="password2" placeholder="Password">
                                                            <span class="input-group-text"><i class="fa fa-eye" id="password2-eye" title="Lihat Password"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md mb-3">
                                                        <label>Nama Toko <small class="text-danger">*</small></label>
                                                        <input type="text"
                                                            class="form-control form-control-alt form-control-lg" name="nama_toko" id="nama_toko" autocomplete="off" required>
                                                    </div>
                                                    <div class="col-md mb-3">
                                                        <label>Logo Toko <small class="text-danger">*</small></label>
                                                        <input type="file"
                                                            class="form-control form-control-alt form-control-lg" name="logo_toko" id="logo_toko" accept="image/png, image/jpg, image/jpeg" required>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md mb-3">
                                                        <label>Nomor Telepon Toko <small class="text-danger">*</small></label>
                                                        <div class="input-group">
                                                            <span class="input-group-text">+62</span>
                                                            <input type="text" class="form-control form-control-alt form-control-lg" name="nohp_toko" id="nohp_toko" pattern="[0-9]+" autocomplete="off" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md mb-3">
                                                        <label>Instagram Toko</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text">@</span>
                                                            <input type="text" class="form-control form-control-alt form-control-lg" id="ig_toko" name="ig_toko" autocomplete="off">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md mb-3">
                                                        <label>Alamat Toko <small class="text-danger">*</small></label>
                                                        <textarea class="form-control form-control-alt form-control-lg" name="alamat_toko" id="alamat_toko" required></textarea>
                                                    </div>
                                                </div>
                                                <small class="fst-italic"><span class="text-danger">*</span> Wajib Diisi</small>
                                                <div class="row justify-content-center mb-4">
                                                    <div class="col-md-6 col-xl-5">
                                                        <button type="submit" class="btn w-100 btn-alt-success" id="btnAktivasi">
                                                            <i class="fa fa-sign-in-alt me-1 opacity-50"></i> Aktivasi
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <!-- END Unlock Block -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END Page Content -->
            </div>
        </main>
        <!-- END Main Container -->
    </div>
    <!-- END Page Container -->
    <script src="{{ asset('js/oneui.app.min.js') }}"></script>
    <script src="{{ asset('js/lib/jquery.min.js') }}"></script>
    <script>
        document.getElementById('password1-eye').addEventListener('click', function() {
            var passwordInput = document.getElementById('password1');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                this.classList.remove('fa-eye');
                this.classList.add('fa-eye-slash');
                this.title = 'Sembunyikan Password';
            } else {
                passwordInput.type = 'password';
                this.classList.remove('fa-eye-slash');
                this.classList.add('fa-eye');
                this.title = 'Lihat Password';
            }
        });

        document.getElementById('password2-eye').addEventListener('click', function() {
            var passwordInput = document.getElementById('password2');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                this.classList.remove('fa-eye');
                this.classList.add('fa-eye-slash');
                this.title = 'Sembunyikan Password';
            } else {
                passwordInput.type = 'password';
                this.classList.remove('fa-eye-slash');
                this.classList.add('fa-eye');
                this.title = 'Lihat Password';
            }
        });
        
        $('#btnAktivasi').on('click', function(e) {
            e.preventDefault();
            var password1 = $('#password1').val();
            var password2 = $('#password2').val();
            if (password1 != password2) {
                Swal.fire({
                    icon: 'error',
                    title: 'Password Error',
                    text: 'Password tidak sama',
                });
                return;
            }
            // Dapatkan semua input dalam form
            var inputs = $('#formAktivasi input[required]');
            // Inisialisasi variabel untuk melacak apakah semua validasi telah berhasil
            var isValid = true;
            // Loop melalui setiap input
            inputs.each(function() {
                // Validasi input yang diperlukan (required)
                if (!$(this).val()) {
                    isValid = false;
                    Swal.fire({
                        title: 'Error',
                        text: 'Harap lengkapi semua data yang diperlukan.',
                        icon: 'error',
                    });
                    return false; // Berhenti jika ada yang tidak valid
                }
                // Validasi sesuai dengan pattern
                var pattern = $(this).attr('pattern');
                if (pattern) {
                    var regex = new RegExp(pattern);
                    if (!regex.test($(this).val())) {
                        isValid = false;
                        Swal.fire({
                            title: 'Error',
                            text: 'Data yang Anda masukkan tidak sesuai dengan format yang benar.',
                            icon: 'error',
                        });
                        return false; // Berhenti jika ada yang tidak valid
                    }
                }
            });
            if (isValid) {
                // Jika semua validasi berhasil, kirim form
                Swal.fire({
                    title: 'Konfirmasi',
                    text: 'Apakah data yang Anda input sudah benar?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya',
                    cancelButtonText: 'Tidak'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#formAktivasi').submit();
                    }
                });
            }
        });
    </script>

    @if(Session::has('alert'))
    <script>
        let timerInterval
        Swal.fire({
            icon: '{{ Session::get('alert')['type'] }}',
            title: '{{ Session::get('alert')['title'] }}',
            html: '{{ Session::get('alert')['message'] }}',
            showConfirmButton: false,
            timer: 2500,
            timerProgressBar: true,
            willClose: () => {
                clearInterval(timerInterval)
            }
        });
    </script>
    @endif
</body>
</html>
