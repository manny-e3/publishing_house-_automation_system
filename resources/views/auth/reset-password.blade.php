@extends('layouts.admin-auth')

@section('title', 'Reset Password - Griots Lounge Publishing')

@section('content')
<style>
    .password-strength-indicator { display: flex; gap: 4px; margin-top: 8px; }
    .strength-bar { height: 4px; flex: 1; background: #e5e9f2; border-radius: 2px; }
    .strength-bar.active { background: #00382B; }
</style>
<div class="auth-split-wrapper">
    <!-- Visual Side -->
    <div class="auth-split-visual">
        <div class="auth-split-content">
            <span class="badge bg-white-10 text-white text-uppercase tracking-widest fs-11px mb-3">Pan-African Literary House &bull; Est. 2012</span>
            <h1 class="brand-logo-text font-serif text-white">Griots Lounge</h1>
            <p class="fs-18px italic font-serif mb-4 text-white-50">"Preserving narratives, celebrating culture, amplifying diverse stories."</p>
            <div style="width: 48px; height: 4px; background: #96BBA4; border-radius: 2px;"></div>
        </div>
    </div>

    <!-- Form Side -->
    <div class="auth-split-form-side">
        <div class="auth-form-inner">
            <div class="nk-block-head">
                <div class="d-flex align-items-center gap-1 mb-3">
                    <em class="icon ni ni-arrow-left"></em>
                    <a href="{{ route('login') }}" class="link link-primary fs-13px fw-bold">Return to Sign In</a>
                </div>
                <div class="nk-block-head-content">
                    <span class="badge bg-light text-muted uppercase tracking-wider mb-2 font-mono">Account Security</span>
                    <h2 class="nk-block-title font-serif">Reset Password</h2>
                    <p class="text-soft fs-15px">Enter your new credentials below to secure your author or staff account.</p>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-icon alert-dismissible bg-success-dim border-0 mb-4 mt-3">
                    <em class="icon ni ni-check-circle"></em> {{ session('success') }}
                    <button class="close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-icon alert-dismissible bg-danger-dim border-0 mb-4 mt-3">
                    <em class="icon ni ni-cross-circle"></em> {{ session('error') }}
                    <button class="close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->has('token'))
                <div class="alert alert-danger alert-icon alert-dismissible bg-danger-dim border-0 mb-4 mt-3">
                    <em class="icon ni ni-cross-circle"></em> {{ $errors->first('token') }}
                    <button class="close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            <form action="{{ route('password.update') }}" method="POST" class="mt-4">
                @csrf
                <input type="hidden" name="token" value="{{ $token ?? $request->route('token') }}">

                <div class="form-group mb-4">
                    <label class="form-label">Email Address</label>
                    <div class="form-control-wrap">
                        <div class="form-icon form-icon-right">
                            <em class="icon ni ni-mail"></em>
                        </div>
                        <input type="email" name="email" class="form-control form-control-lg" placeholder="Enter your email" value="{{ old('email', $email ?? $request->email ?? request('email')) }}" required>
                    </div>
                    @error('email') <span class="text-danger mt-1 d-block fs-12px">{{ $message }}</span> @enderror
                </div>

                <div class="form-group mb-4">
                    <label class="form-label">New Password</label>
                    <div class="form-control-wrap">
                        <a href="javascript:void(0);" class="form-icon form-icon-right passcode-switch lg" data-target="password" aria-label="Toggle password visibility">
                            <em class="passcode-icon icon-show icon ni ni-eye"></em>
                            <em class="passcode-icon icon-hide icon ni ni-eye-off"></em>
                        </a>
                        <input type="password" name="password" id="password" class="form-control form-control-lg" placeholder="Enter new password" required>
                    </div>
                    <div class="password-strength-indicator" id="password-strength">
                        <div class="strength-bar"></div>
                        <div class="strength-bar"></div>
                        <div class="strength-bar"></div>
                        <div class="strength-bar"></div>
                    </div>
                    @error('password') <span class="text-danger mt-2 d-block fs-12px">{{ $message }}</span> @enderror
                </div>

                <div class="form-group mb-4">
                    <label class="form-label">Confirm Password</label>
                    <div class="form-control-wrap">
                        <a href="javascript:void(0);" class="form-icon form-icon-right passcode-switch lg" data-target="password_confirmation" aria-label="Toggle confirm password visibility">
                            <em class="passcode-icon icon-show icon ni ni-eye"></em>
                            <em class="passcode-icon icon-hide icon ni ni-eye-off"></em>
                        </a>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control form-control-lg" placeholder="Confirm new password" required>
                    </div>
                </div>

                <div class="form-group pt-2">
                    <button type="submit" class="btn btn-lg btn-primary btn-block w-100" data-loading-text="Updating Password...">
                        <span>Update Password</span>
                        <em class="icon ni ni-arrow-right ms-1"></em>
                    </button>
                </div>
            </form>

            <div class="divider text-center my-4">
                <span class="bg-white px-3 fs-12px text-soft overline-title">OR</span>
            </div>

            <div class="text-center">
                <p class="fs-14px">Remember your credentials? <a href="{{ route('login') }}" class="link link-primary fw-bold">Return to Sign In</a></p>
                <p class="fs-12px text-soft mt-4">&copy; {{ date('Y') }} Griots Lounge Publishing (Nigeria & Canada).</p>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const pwdInput = document.getElementById('password');
        const bars = document.querySelectorAll('#password-strength .strength-bar');
        if (pwdInput && bars.length) {
            pwdInput.addEventListener('input', function() {
                const val = this.value;
                let strength = 0;
                if (val.length >= 8) strength++;
                if (/[A-Z]/.test(val) && /[a-z]/.test(val)) strength++;
                if (/[0-9]/.test(val)) strength++;
                if (/[^A-Za-z0-9]/.test(val)) strength++;
                bars.forEach((bar, idx) => {
                    if (idx < strength && val.length > 0) {
                        bar.classList.add('active');
                    } else {
                        bar.classList.remove('active');
                    }
                });
            });
        }
    });
</script>
@endsection
