<!DOCTYPE html>
<html lang="en" class="js">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>@yield('title') | The Curated Archive</title>
    <link rel="stylesheet" href="{{ asset('assets/css/dashlite.css') }}">
    <link id="skin-default" rel="stylesheet" href="{{ asset('assets/css/theme.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Playfair+Display:ital,wght@0,700;1,700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-serif { font-family: 'Playfair Display', serif; }
        .auth-split-wrapper { display: flex; min-height: 100vh; overflow: hidden; }
        .auth-split-visual { flex: 1; background: #0c1017 url('{{ asset('assets/images/login-hero.png') }}') no-repeat center center; background-size: cover; display: flex; align-items: flex-end; padding: 4rem; position: relative; }
        .auth-split-visual::after { content: ''; position: absolute; inset: 0; background: linear-gradient(to top, rgba(12, 16, 23, 0.9), transparent); }
        .auth-split-content { position: relative; z-index: 2; color: white; max-width: 450px; }
        .auth-split-form-side { flex: 1; background: white; display: flex; align-items: center; justify-content: center; padding: 3rem; }
        .auth-form-inner { width: 100%; max-width: 400px; }
        .form-label { font-weight: 600; color: #344357; margin-bottom: 0.5rem; }
        .form-control-wrap { position: relative; }
        .form-control-icon-right, .form-icon-right { position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); z-index: 5; color: #8091a7; pointer-events: auto; }
        .btn-primary { background-color: #001f3f; border-color: #001f3f; border-radius: 4px; padding: 0.75rem; font-weight: 600; display: flex; align-items: center; justify-content: center; gap: 0.5rem; transition: all 0.3s ease; }
        .btn-primary:hover { background-color: #002d5c; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(0, 31, 63, 0.2); }
        .form-control-lg { border-color: #e5e9f2; background-color: #f5f6fa; padding: 0.85rem 1rem; border-radius: 4px; }
        .form-control-lg:focus { background-color: white; border-color: #001f3f; box-shadow: none; }
        .brand-logo-text { font-size: 2.5rem; color: white; margin-bottom: 2rem; display: block; }
        @media (max-width: 991px) { .auth-split-visual { display: none; } }
    </style>
</head>

<body class="nk-body npc-general pg-auth">
    <div class="nk-app-root">
        <div class="nk-main">
            <div class="nk-wrap nk-wrap-nosidbar">
                <div class="nk-content p-0">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('assets/js/bundle.js') }}"></script>
    <script src="{{ asset('assets/js/scripts.js') }}"></script>
</body>
</html>
