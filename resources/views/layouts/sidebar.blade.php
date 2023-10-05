<nav id="sidebar" aria-label="Main Navigation">
    <div class="content-header">
        <a class="fw-semibold text-dual" href={{ route('dashboard') }}">
            <span class="smini-visible">
                <i class="fa fa-circle-notch text-success"></i>
            </span>
            <span class="smini-hide fs-5 tracking-wider">Octobake</span>
        </a>
        <div>
            <a class="d-lg-none btn btn-sm btn-alt-secondary ms-1" data-toggle="layout" data-action="sidebar_close"
                href="javascript:void(0)">
                <i class="fa fa-fw fa-times"></i>
            </a>
        </div>
    </div>
    <div class="js-sidebar-scroll">
        <!-- Side Navigation -->
        <div class="content-side">
            <ul class="nav-main">
                <li class="nav-main-item">
                    <a class="nav-main-link" href="{{ route('dashboard') }}">
                        <i class="nav-main-link-icon si si-speedometer"></i>
                        <span class="nav-main-link-name">Dashboard</span>
                    </a>
                    <li class="nav-main-heading">Manajemen Data</li>
                    @if(Auth::user()->role == 'owner')
                    <a class="nav-main-link" href="{{ route('karyawan_view') }}">
                        <i class="nav-main-link-icon fa fa-users"></i>
                        <span class="nav-main-link-name">Karyawan</span>
                    </a>
                    @endif
                    <a class="nav-main-link" href="{{ route('produk_view') }}">
                        <i class="nav-main-link-icon fa fa-bread-slice"></i>
                        <span class="nav-main-link-name">Produk</span>
                    </a>
                    @if(Auth::user()->role == 'owner')
                    <li class="nav-main-heading">Transaksi</li>
                    <a class="nav-main-link" href="{{ route('pembelian_view') }}">
                        <i class="nav-main-link-icon fa fa-money-check-dollar"></i>
                        <span class="nav-main-link-name">Pembelian</span>
                    </a>
                    @endif
                </li>
            </ul>
        </div>
    </div>
</nav>
