<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Login - Octabake</title>
    <link rel="shortcut icon" href="{{ asset('logo.png') }}">
    <link rel="stylesheet" id="css-main" href="{{ asset('css/oneui.min.css') }}">
  </head>
  <body>
    <!-- Page Container -->
    <div id="page-container">
      <!-- Main Container -->
      <main id="main-container">
        <!-- Page Content -->
        <div class="bg-image" style="background-image: url({{ asset('media/photos/photo14@2x.jpg') }});">
          <div class="hero-static d-flex align-items-center bg-primary-dark-op">
            <div class="content">
              <div class="row justify-content-center push">
                <div class="col-md-8 col-lg-6 col-xl-4">
                  <!-- Unlock Block -->
                  <div class="block block-rounded shadow-none mb-0">
                    <div class="block-header block-header-default">
                      <label><h3 class="block-title">Login Akun</h3></label>
                    </div>
                    <div class="block-content">
                      <div class="p-sm-3 px-lg-4 px-xxl-5 py-lg-5 text-center">
                        <img class="img-avatar img-avatar96" src="{{ asset('Logo Octabake.png') }}" alt="">
                        <p class="fw-semibold my-2">
                          Octabake
                        </p>
                        <!-- Unlock Form -->
                        <form class="js-validation-lock mt-4" action="{{ route('login_proses') }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <input type="text" class="form-control form-control-lg form-control-alt" id="username" name="username" placeholder="Username.." autocomplete="off">
                              </div>
                          <div class="mb-4">
                            <input type="password" class="form-control form-control-lg form-control-alt" id="password" name="password" placeholder="Password..">
                          </div>
                          <div class="row justify-content-center mb-4">
                            <div class="col-md-6 col-xl-5">
                              <button type="submit" class="btn w-100 btn-alt-success">
                                <i class="fa fa-sign-in-alt me-1 opacity-50"></i> Login
                              </button>
                            </div>
                          </div>
                        </form>
                        <!-- END Unlock Form -->
                      </div>
                    </div>
                  </div>
                  <!-- END Unlock Block -->
                </div>
              </div>
              <div class="fs-sm text-center text-white">
                <span class="fw-medium">Octabake</span> &copy; <span data-toggle="year-copy"></span>
              </div>
            </div>
          </div>
        </div>
        <!-- END Page Content -->
      </main>
      <!-- END Main Container -->
    </div>
    <!-- END Page Container -->
    <script src="{{ asset('js/oneui.app.min.js') }}"></script>
    <script src="{{ asset('js/lib/jquery.min.js') }}"></script>
    <script src="{{ asset('js/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('js/pages/op_auth_lock.min.js') }}"></script>
  </body>
</html>
