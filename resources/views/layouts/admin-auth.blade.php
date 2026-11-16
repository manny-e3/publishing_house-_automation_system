<!DOCTYPE html>
<html lang="en" class="js">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>@yield('title') | Griots Lounge Publishing</title>
    <link rel="stylesheet" href="{{ asset('assets/css/dashlite.css') }}">
    <link id="skin-default" rel="stylesheet" href="{{ asset('assets/css/theme.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=EB+Garamond:ital,wght@0,400..800;1,400..800&family=Jost:ital,wght@0,300..700;1,300..700&family=Inter:wght@400;600;700&family=Playfair+Display:ital,wght@0,700;1,700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Jost', 'Inter', sans-serif; background-color: #FCFCFC; }
        .font-serif { font-family: 'EB Garamond', 'Playfair Display', Georgia, serif; }
        .auth-split-wrapper { display: flex; min-height: 100vh; overflow: hidden; }
        .auth-split-visual { flex: 1; background: #00241C url('{{ asset('assets/images/register-hero.png') }}') no-repeat center center; background-size: cover; display: flex; align-items: flex-end; padding: 4rem; position: relative; }
        .auth-split-visual::after { content: ''; position: absolute; inset: 0; background: linear-gradient(to top, rgba(0, 36, 28, 0.95) 0%, rgba(0, 36, 28, 0.78) 50%, rgba(0, 36, 28, 0.45) 100%); }
        .auth-split-content { position: relative; z-index: 2; color: white; max-width: 450px; }
        .auth-split-form-side { flex: 1; background: white; display: flex; align-items: center; justify-content: center; padding: 3rem; }
        .auth-form-inner { width: 100%; max-width: 400px; }
        .form-label { font-weight: 600; color: #00382B; margin-bottom: 0.5rem; }
        .form-control-wrap { position: relative; }
        .form-icon-right, .form-control-icon-right { position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); z-index: 10; color: #8091a7; pointer-events: auto; display: inline-flex; align-items: center; justify-content: center; width: 28px; height: 28px; }
        .passcode-switch { cursor: pointer; user-select: none; }
        .passcode-switch .icon { font-size: 1.15rem; color: #8091a7; transition: color 0.2s ease; }
        .passcode-switch:hover .icon { color: #009688; }
        .passcode-switch .icon-hide { display: none; }
        .passcode-switch.is-shown .icon-show { display: none; }
        .passcode-switch.is-shown .icon-hide { display: inline-block; }
        .form-control-lg { border-color: #DCE5E0; background-color: #FCFCFC; padding: 0.85rem 2.85rem 0.85rem 1rem; border-radius: 4px; }
        .form-control-lg:focus { background-color: white; border-color: #00382B; box-shadow: 0 0 0 3px rgba(0, 56, 43, 0.16); }
        .btn-primary { background-color: #00382B !important; border-color: #00382B !important; border-radius: 4px; padding: 0.75rem; font-weight: 600; display: flex; align-items: center; justify-content: center; gap: 0.5rem; transition: all 0.3s ease; color: #FFFFFF !important; }
        .btn-primary:hover { background-color: #00241C !important; border-color: #00241C !important; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(0, 56, 43, 0.25); color: #FFFFFF !important; }
        .brand-logo-text { font-size: 2.75rem; color: white; margin-bottom: 2rem; display: block; font-family: 'EB Garamond', Georgia, serif; letter-spacing: -0.01em; }
        .link-primary { color: #00382B !important; }
        .link-primary:hover { color: #009688 !important; }
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
    <script src="{{ asset('assets/js/form-submit-spinner.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.passcode-switch').forEach(function(btn) {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    var targetId = btn.getAttribute('data-target');
                    var input = document.getElementById(targetId);
                    if (!input) return;
                    var isPassword = (input.getAttribute('type') === 'password');
                    input.setAttribute('type', isPassword ? 'text' : 'password');
                    btn.classList.toggle('is-shown', isPassword);
                    btn.classList.toggle('is-hidden', !isPassword);
                    var showIcon = btn.querySelector('.icon-show');
                    var hideIcon = btn.querySelector('.icon-hide');
                    if (showIcon && hideIcon) {
                        showIcon.style.display = isPassword ? 'none' : 'inline-block';
                        hideIcon.style.display = isPassword ? 'inline-block' : 'none';
                    }
                });
            });
            // 2. Form submission spinner and disable button handler
            document.querySelectorAll('form').forEach(function(form) {
                form.addEventListener('submit', function(e) {
                    if (form.checkValidity && !form.checkValidity()) {
                        return; // Let native browser HTML5 validation messages display
                    }

                    var submitBtn = form.querySelector('button[type="submit"]');
                    if (!submitBtn || submitBtn.disabled) return;

                    var loadingText = submitBtn.getAttribute('data-loading-text');
                    if (!loadingText) {
                        var currentText = submitBtn.innerText.trim();
                        if (/sign in|log in/i.test(currentText)) {
                            loadingText = 'Signing in...';
                        } else if (/create account|register|submit request/i.test(currentText)) {
                            loadingText = 'Creating account...';
                        } else if (/recovery|reset|send/i.test(currentText)) {
                            loadingText = 'Sending link...';
                        } else if (/update password/i.test(currentText)) {
                            loadingText = 'Updating password...';
                        } else if (/verify/i.test(currentText)) {
                            loadingText = 'Verifying...';
                        } else {
                            loadingText = 'Processing...';
                        }
                    }

                    // 10ms delay ensures form data serialization & submission starts
                    setTimeout(function() {
                        submitBtn.disabled = true;
                        submitBtn.classList.add('disabled');
                        submitBtn.style.pointerEvents = 'none';
                        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span><span>' + loadingText + '</span>';
                    }, 10);
                });
            });
        });
    </script>
</body>
</html>
