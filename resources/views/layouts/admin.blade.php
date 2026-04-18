<!DOCTYPE html>
<html lang="en" class="js">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>@yield('title', 'Admin Dashboard') | The Curated Archive</title>
    <link rel="stylesheet" href="{{ asset('assets/css/dashlite.css') }}">
    <link id="skin-default" rel="stylesheet" href="{{ asset('assets/css/theme.css') }}">
</head>

<body class="nk-body bg-lighter npc-general has-sidebar">
    <div class="nk-app-root">
        <!-- main @s -->
        <div class="nk-main">
            <!-- sidebar @s -->
            <div class="nk-sidebar nk-sidebar-fixed is-dark" data-content="sidebarMenu">
                <div class="nk-sidebar-element nk-sidebar-head">
                    <div class="nk-sidebar-brand">
                        <a href="{{ route('admin.dashboard') }}" class="logo-link nk-sidebar-logo">
                            <span class="text-white font-bold" style="font-size:1.25rem; font-family: serif;">Curated Archive</span>
                        </a>
                    </div>
                </div>
                <div class="nk-sidebar-element nk-sidebar-body">
                    <div class="nk-sidebar-content">
                        <div class="nk-sidebar-menu" data-simplebar>
                            <ul class="nk-menu">
                                <li class="nk-menu-heading">
                                    <h6 class="overline-title text-primary-alt">Overview</h6>
                                </li>
                                <li class="nk-menu-item">
                                    <a href="{{ route('admin.dashboard') }}" class="nk-menu-link">
                                        <span class="nk-menu-icon"><em class="icon ni ni-dashboard"></em></span>
                                        <span class="nk-menu-text">Dashboard</span>
                                    </a>
                                </li>
                                <li class="nk-menu-heading">
                                    <h6 class="overline-title text-primary-alt">Modules</h6>
                                </li>
                                @hasanyrole('admin|acquisitions')
                                <li class="nk-menu-item">
                                    <a href="{{ route('admin.prospects.index') }}" class="nk-menu-link">
                                        <span class="nk-menu-icon"><em class="icon ni ni-inbox"></em></span>
                                        <span class="nk-menu-text">Prospect Tracker</span>
                                        <span class="nk-menu-badge bg-danger">New</span>
                                    </a>
                                </li>
                                @endrole

                                @hasanyrole('admin|finance')
                                <li class="nk-menu-item has-sub">
                                    <a href="#" class="nk-menu-link nk-menu-toggle">
                                        <span class="nk-menu-icon"><em class="icon ni ni-file-docs"></em></span>
                                        <span class="nk-menu-text">Billing & Invoices</span>
                                    </a>
                                    <ul class="nk-menu-sub">
                                        <li class="nk-menu-item">
                                            <a href="{{ route('admin.invoices.index') }}" class="nk-menu-link"><span class="nk-menu-text">Invoices</span></a>
                                        </li>
                                        <li class="nk-menu-item">
                                            <a href="{{ route('admin.receipts.index') }}" class="nk-menu-link"><span class="nk-menu-text">Receipts</span></a>
                                        </li>
                                        <li class="nk-menu-item">
                                            <a href="{{ route('admin.transactions.index') }}" class="nk-menu-link"><span class="nk-menu-text">Transactions Log</span></a>
                                        </li>
                                    </ul>
                                </li>
                                @endrole

                                @hasanyrole('admin|editorial')
                                <li class="nk-menu-item">
                                    <a href="{{ route('admin.projects.index') }}" class="nk-menu-link">
                                        <span class="nk-menu-icon"><em class="icon ni ni-briefcase"></em></span>
                                        <span class="nk-menu-text">Active Projects</span>
                                    </a>
                                </li>
                                @endrole

                                @hasanyrole('admin|finance')
                                <li class="nk-menu-heading">
                                    <h6 class="overline-title text-soft">Configuration</h6>
                                </li>
                                <li class="nk-menu-item">
                                    <a href="{{ route('admin.settings.gateways') }}" class="nk-menu-link">
                                        <span class="nk-menu-icon"><em class="icon ni ni-setting-alt"></em></span>
                                        <span class="nk-menu-text">Payment Settings</span>
                                    </a>
                                </li>
                                <li class="nk-menu-item has-sub">
                                    <a href="#" class="nk-menu-link nk-menu-toggle">
                                        <span class="nk-menu-icon"><em class="icon ni ni-config"></em></span>
                                        <span class="nk-menu-text">System Settings</span>
                                    </a>
                                    <ul class="nk-menu-sub">
                                        <li class="nk-menu-item">
                                            <a href="{{ route('admin.settings.pricing') }}" class="nk-menu-link"><span class="nk-menu-text">Pricing Matrix</span></a>
                                        </li>
                                        <li class="nk-menu-item">
                                            <a href="{{ route('admin.settings.criteria') }}" class="nk-menu-link"><span class="nk-menu-text">Review Criteria</span></a>
                                        </li>
                                        <li class="nk-menu-item">
                                            <a href="{{ route('admin.settings.global') }}" class="nk-menu-link"><span class="nk-menu-text">Global Branding</span></a>
                                        </li>
                                        <li class="nk-menu-item">
                                            <a href="{{ route('admin.settings.templates') }}" class="nk-menu-link"><span class="nk-menu-text">Email Templates</span></a>
                                        </li>
                                    </ul>
                                </li>
                                @endrole
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!-- sidebar @e -->
            
            <!-- wrap @s -->
            <div class="nk-wrap">
                <!-- main header @s -->
                <div class="nk-header nk-header-fixed is-light">
                    <div class="container-fluid">
                        <div class="nk-header-wrap">
                            <div class="nk-header-brand d-xl-none">
                                <a href="{{ route('admin.dashboard') }}" class="logo-link">
                                    <span class="font-bold text-lg">Curated Archive</span>
                                </a>
                            </div>
                            <div class="nk-header-tools">
                                <ul class="nk-quick-nav">
                                    <li class="dropdown user-dropdown">
                                        <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown">
                                            <div class="user-toggle">
                                                <div class="user-avatar sm bg-primary">
                                                    <span>{{ substr(Auth::user()->name ?? 'AD', 0, 2) }}</span>
                                                </div>
                                                <div class="user-info d-none d-md-block">
                                                    <div class="user-status text-capitalize">{{ Auth::user()->roles->first()->name ?? 'User' }}</div>
                                                    <div class="user-name dropdown-indicator">{{ Auth::user()->name }}</div>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- main header @e -->

                <!-- content @s -->
                @yield('content')
                <!-- content @e -->
                
                <!-- footer @s -->
                <div class="nk-footer">
                    <div class="container-fluid">
                        <div class="nk-footer-wrap">
                            <div class="nk-footer-copyright"> &copy; {{ date('Y') }} The Curated Archive.</div>
                        </div>
                    </div>
                </div>
                <!-- footer @e -->
            </div>
            <!-- wrap @e -->
        </div>
        <!-- main @e -->
    </div>
    <script src="{{ asset('assets/js/bundle.js') }}"></script>
    <script src="{{ asset('assets/js/scripts.js') }}"></script>
    <script src="{{ asset('assets/js/charts/gd-default.js') }}"></script>

    @if(session('success'))
    <script>
        NioApp.Toast('<h5>Success</h5><p>{{ session('success') }}</p>', 'success', {position: 'top-right'});
    </script>
    @endif

    @if(session('error'))
    <script>
        NioApp.Toast('<h5>Error</h5><p>{{ session('error') }}</p>', 'error', {position: 'top-right'});
    </script>
    @endif

    @if(session('info'))
    <script>
        NioApp.Toast('<h5>Info</h5><p>{{ session('info') }}</p>', 'info', {position: 'top-right'});
    </script>
    @endif

    @stack('scripts')
</body>
</html>
