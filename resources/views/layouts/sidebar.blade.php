<nav id="sidebar" aria-label="Main Navigation">
    <div class="content-header">
        <a class="fw-semibold text-dual" href={{ route('dashboard') }}">
            <span class="smini-visible">
                <i class="fa fa-circle-notch text-success"></i>
            </span>
            <span class="smini-hide fs-5 tracking-wider">{{ app('App\Models\Toko')::first()->nama_toko }}</span>
        </a>
        <div>
            <a class="d-lg-none btn btn-sm btn-alt-secondary ms-1" data-toggle="layout" data-action="sidebar_close" href="javascript:void(0)">
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
                    @if (Auth::user()->role == 'owner')
                        <a class="nav-main-link" href="{{ route('toko_view') }}">
                            <i class="nav-main-link-icon fa fa-store"></i>
                            <span class="nav-main-link-name">Toko</span>
                        </a>
                        <a class="nav-main-link" href="{{ route('karyawan_view') }}">
                            <i class="nav-main-link-icon fa fa-users"></i>
                            <span class="nav-main-link-name">Karyawan</span>
                        </a>
                    @endif
                    <a class="nav-main-link" href="{{ route('produk_view') }}">
                        <i class="nav-main-link-icon fa fa-bread-slice"></i>
                        <span class="nav-main-link-name">Produk</span>
                    </a>
                    <li class="nav-main-heading">Transaksi</li>
                    <a class="nav-main-link" href="{{ route('pembelian_view') }}">
                        <i class="nav-main-link-icon fa fa-money-check-dollar"></i>
                        <span class="nav-main-link-name">Pembelian</span>
                    </a>
                    <li class="nav-main-heading">Laporan</li>
                    <li class="nav-main-item">
                        <a role="button" class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false">
                            <i class="nav-main-link-icon fa fa-money-check-dollar"></i>
                            <span class="nav-main-link-name">Keuangan</span>
                        </a>
                        <ul class="nav-main-submenu">
                            <li class="nav-main-item">
                                <a class="nav-main-link" href="{{ route('laporan_view') }}">
                                    <span class="nav-main-link-name">Harian</span>
                                </a>
                            </li>
                            <li class="nav-main-item">
                                <a class="nav-main-link" href="{{ route('laporan_view_mingguan') }}">
                                    <span class="nav-main-link-name">Mingguan</span>
                                </a>
                            </li>
                            <li class="nav-main-item">
                                <a class="nav-main-link" href="{{ route('laporan_view_bulanan') }}">
                                    <span class="nav-main-link-name">Bulanan</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                </li>
            </ul>
        </div>
    </div>
</nav>
