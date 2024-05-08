<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Koperasi Kota Padang</title>
    <link rel="icon" type="image/svg" href="{{ asset('assets/images/logo_icon.svg') }}">

    <!-- Global stylesheets -->
    <link href="https://fonts.cdnfonts.com/css/inter" rel="stylesheet">
    <link href="{{ asset('assets/icons/phosphor/styles.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/all.min.css') }}" id="stylesheet" rel="stylesheet" type="text/css">
    <!-- /global stylesheets -->

    <!-- Core JS files -->
    <script src="{{ asset('assets/demo/demo_configurator.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap/bootstrap.bundle.min.js') }}"></script>
    <!-- /core JS files -->

    <!-- Theme JS files -->
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/extensions/jquery_ui/core.min.js') }}"></script>
    <script src="{{ asset('assets/js/vendor/tables/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/js/vendor/notification/sweet_alert.min.js') }}"></script>
    <script src="{{ asset('assets/js/vendor/notification/noty.min.js') }}"></script>

    <script src="{{ asset('assets/js/app.js') }}"></script>
    <!-- /theme JS files -->

</head>

<body>

<!-- Page content -->
<div class="page-content">

    <!-- Main sidebar -->
    <div class="sidebar sidebar-dark sidebar-main sidebar-expand-lg">

        <!-- Sidebar header -->
        <div class="sidebar-section bg-black bg-opacity-10 border-bottom border-bottom-white border-opacity-10">
            <div class="sidebar-logo d-flex justify-content-center align-items-center">
                <a href="/" class="d-inline-flex align-items-center py-2">
                    <img src="{{ asset('assets/images/logo-padang.png') }}" class="sidebar-logo-icon" alt="">
{{--                    <span class="text-light ms-2 fw-bold" style="font-size: 1rem">Koperasi Padang</span>--}}
                </a>

                <div class="sidebar-resize-hide ms-auto">
                    <button type="button" class="btn btn-flat-white btn-icon btn-sm rounded-pill border-transparent sidebar-control sidebar-main-resize d-none d-lg-inline-flex">
                        <i class="ph-arrows-left-right"></i>
                    </button>

                    <button type="button" class="btn btn-flat-white btn-icon btn-sm rounded-pill border-transparent sidebar-mobile-main-toggle d-lg-none">
                        <i class="ph-x"></i>
                    </button>
                </div>
            </div>
        </div>
        <!-- /sidebar header -->


        <!-- Sidebar content -->
        <div class="sidebar-content">

            <!-- Main navigation -->
            <div class="sidebar-section">
                <ul class="nav nav-sidebar" data-nav-type="accordion">

                    <!-- Main -->
                    <li class="nav-item-header">
                        <div class="text-uppercase fs-sm lh-sm opacity-50 sidebar-resize-hide">Main</div>
                        <i class="ph-dots-three sidebar-resize-show"></i>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('dashboard') }}" class="nav-link" id="dashboard-menu">
                            <i class="ph-house"></i>
                            <span>
                                Dashboard
                                <!-- <span class="d-block fw-normal opacity-50">No pending orders</span> -->
                            </span>
                        </a>
                    </li>

                    @hasanyrole('super-admin|admin-opd')
                    <li class="nav-item">
                        <a href="{{ route('members.index') }}" id="memberMenu" class="nav-link">
                            <i class="ph-user-circle"></i>
                            <span>Anggota</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('balance.index') }}" id="balanceMenu" class="nav-link">
                            <i class="ph-credit-card"></i>
                            <span>Saldo</span>
                        </a>
                    </li>
                    @endhasanyrole

                    <li class="nav-item">
                        <a href="{{ route('payments.index') }}" id="reportMenu" class="nav-link">
                            <i class="ph-info"></i>
                            <span>Pembayaran</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('reports.index') }}" id="reportMenu" class="nav-link">
                            <i class="ph-info"></i>
                            <span>Laporan</span>
                        </a>
                    </li>



                    @hasanyrole('super-admin')
                    <!-- SETTING -->
                    <li class="nav-item-header">
                        <div class="text-uppercase fs-sm lh-sm opacity-50 sidebar-resize-hide">SETTING</div>
                        <i class="ph-dots-three sidebar-resize-show"></i>
                    </li>

                    <li class="nav-item">
                        <a href="{{ url('users') }}" id="user-menu" class="nav-link">
                            <i class="ph-users"></i>
                            <span>Kelola Users</span>
                        </a>
                    </li>
                    @endhasanyrole
                    <!-- /layout -->

                </ul>
            </div>
            <!-- /main navigation -->

        </div>
        <!-- /sidebar content -->

    </div>
    <!-- /main sidebar -->


    <!-- Main content -->
    <div class="content-wrapper">

        <!-- Main navbar -->
        <div class="navbar navbar-expand-lg navbar-static shadow">
            <div class="container-fluid">
                <div class="d-flex d-lg-none me-2">
                    <button type="button" class="navbar-toggler sidebar-mobile-main-toggle rounded-pill">
                        <i class="ph-list"></i>
                    </button>
                </div>

                <div class="navbar-collapse flex-lg-1 order-2 order-lg-1 collapse" id="navbar_search">
                    <div class="navbar-search flex-fill dropdown mt-2 mt-lg-0">
                        @yield('page-header')
                    </div>
                </div>

                <ul class="nav hstack gap-sm-1 flex-row justify-content-end order-1 order-lg-2">
                    <li class="nav-item">
                        <a href="#" class="navbar-nav-link navbar-nav-link-icon rounded-pill" data-bs-toggle="offcanvas" data-bs-target="#notifications">
                            <i class="ph-bell"></i>
                            <span class="badge bg-yellow text-black position-absolute top-0 end-0 translate-middle-top zindex-1 rounded-pill mt-1 me-1">2</span>
                        </a>
                    </li>

                    <li class="nav-item nav-item-dropdown-lg dropdown">
                        <a href="#" class="navbar-nav-link align-items-center rounded-pill p-1" data-bs-toggle="dropdown">
                            <div class="status-indicator-container">
                                <img src="{{ asset('assets/images/user.png') }}" class="w-32px h-32px rounded-pill" alt="">
                                <span class="status-indicator bg-success"></span>
                            </div>
                            <span class="d-none d-lg-inline-block mx-lg-2">
                                {{ auth()->user()->name }}
                            </span>
                        </a>

                        <div class="dropdown-menu dropdown-menu-end">
                            <a href="{{ url('profile') }}" class="dropdown-item">
                                <i class="ph-user-circle me-2"></i>
                                Ubah Password
                            </a>
                            <a class="dropdown-item" href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                <i class="ph-sign-out me-2"></i>{{ __('Logout') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        <!-- /main navbar -->

        <!-- Inner content -->
        <div class="content-inner">
            <!-- Page header -->
            <div class="page-header page-header-light shadow">
                {{--                <div class="page-header-content d-lg-flex">--}}
                {{--                    @yield('page-header')--}}
                {{--                </div>--}}

                <div class="page-header-content d-lg-flex border-top">
                    <div class="d-flex">
                        <div class="breadcrumb py-2">
                            <a href="{{ url('dashboard') }}" class="breadcrumb-item"><i class="ph-house"></i></a>
                            @yield('breadcrumbs')
                        </div>

                        <a href="#" class="btn btn-light align-self-center collapsed d-lg-none border-transparent rounded-pill p-0 ms-auto" data-bs-toggle="collapse">
                            <i class="ph-caret-down collapsible-indicator ph-sm m-1"></i>
                        </a>
                    </div>
                </div>
            </div>
            <!-- /page header -->

            <!-- Content area -->
            <div class="content">

                <!-- Page content -->
                @yield('content')
                <!-- /page content -->

            </div>
            <!-- /content area -->

            <!-- Footer -->
            <div class="navbar navbar-sm navbar-footer border-top">
                <div class="container-fluid">
                    <span>&copy; 2024 <a href="#">Dinas Komunikasi dan Informatika Kota Padang</a></span>
                </div>
            </div>
            <!-- /footer -->

        </div>
        <!-- /inner content -->

    </div>
    <!-- /main content -->

</div>
<!-- /page content -->


<!-- Config -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="demo_config">
    <div class="position-absolute top-50 end-100 visible">
        <button type="button" class="btn btn-primary btn-icon translate-middle-y rounded-end-0" data-bs-toggle="offcanvas" data-bs-target="#demo_config">
            <i class="ph-gear"></i>
        </button>
    </div>

    <div class="offcanvas-header border-bottom py-0">
        <h5 class="offcanvas-title py-3">Configuration</h5>
        <button type="button" class="btn btn-light btn-sm btn-icon border-transparent rounded-pill" data-bs-dismiss="offcanvas">
            <i class="ph-x"></i>
        </button>
    </div>

    <div class="offcanvas-body">
        <div class="fw-semibold mb-2">Color mode</div>
        <div class="list-group mb-3">
            <label class="list-group-item list-group-item-action form-check border-width-1 rounded mb-2">
                <div class="d-flex flex-fill my-1">
                    <div class="form-check-label d-flex me-2">
                        <i class="ph-sun ph-lg me-3"></i>
                        <div>
                            <span class="fw-bold">Light theme</span>
                            <div class="fs-sm text-muted">Set light theme or reset to default</div>
                        </div>
                    </div>
                    <input type="radio" class="form-check-input cursor-pointer ms-auto" name="main-theme" value="light" checked>
                </div>
            </label>

            <label class="list-group-item list-group-item-action form-check border-width-1 rounded mb-2">
                <div class="d-flex flex-fill my-1">
                    <div class="form-check-label d-flex me-2">
                        <i class="ph-moon ph-lg me-3"></i>
                        <div>
                            <span class="fw-bold">Dark theme</span>
                            <div class="fs-sm text-muted">Switch to dark theme</div>
                        </div>
                    </div>
                    <input type="radio" class="form-check-input cursor-pointer ms-auto" name="main-theme" value="dark">
                </div>
            </label>
        </div>
    </div>
</div>
<!-- /config -->

<script>
    Noty.overrideDefaults({
        theme: 'limitless',
        layout: 'topRight',
        type: 'alert',
        timeout: 2500
    });
</script>

@stack('scripts')


</body>
</html>
