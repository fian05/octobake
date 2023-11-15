<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>@yield('title') - {{ app('App\Models\Toko')::first()->nama_toko }}</title>
    <link rel="shortcut icon" href="{{ asset('logo.png') }}">
    <link rel="stylesheet" id="css-main" href="{{ asset('css/oneui.min.css') }}">
    <script src="{{ asset("js/lib/jquery.min.js") }}"></script>
    <link rel="stylesheet" href="{{ asset("js/plugins/sweetalert2/sweetalert2.min.css") }}">
    <script src="{{ asset("js/plugins/sweetalert2/sweetalert2.min.js") }}"></script>
    @yield('head')
</head>
<body>
    <div id="page-container"class="sidebar-o sidebar-dark enable-page-overlay side-scroll page-header-fixed">
        @include('layouts.sidebar')
        @include('layouts.header')
        @yield('content')
        <footer id="page-footer" class="bg-body-light">
            <div class="content py-3">
                <div class="row fs-sm">
                    <div class="col-sm-6 order-sm-2 py-1 text-center text-sm-end">
                        Create with <i class="fa fa-heart text-danger"></i>
                    </div>
                    <div class="col-sm-6 order-sm-1 py-1 text-center text-sm-start">
                        <a class="fw-semibold" role="button">{{ app('App\Models\Toko')::first()->nama_toko }}</a> &copy; <span data-toggle="year-copy"></span>
                    </div>
                </div>
            </div>
        </footer>
    </div>
    <script src="{{ asset('js/oneui.app.min.js') }}"></script>
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
    <script>
        var currentUrl=window.location.origin+window.location.pathname;;var navLinks=document.querySelectorAll('a.nav-main-link');for(var i=0;i<navLinks.length;i++){if(navLinks[i].getAttribute('href')===currentUrl){navLinks[i].classList.add('active');}else{navLinks[i].classList.remove('active');}}
        var navItems = document.querySelectorAll('.nav-main-item');for(var i=0;i<navItems.length;i++){var submenuLinks=navItems[i].querySelectorAll('.nav-main-link-submenu');var links=navItems[i].querySelectorAll('a.nav-main-link');for(var j=0;j<links.length;j++){if(links[j].getAttribute('href')===currentUrl){links[j].classList.add('active');navItems[i].classList.add('open');}else{links[j].classList.remove('active');}}}
    </script>
    @yield('script')
</body>
</html>
