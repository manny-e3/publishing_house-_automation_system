@extends('layouts.admin-auth')

@section('title', 'Verify OTP')

@section('content')
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
        <div class="auth-form-inner text-center">
            <div class="nk-block-head">
                <div class="nk-block-head-content">
                    <h2 class="nk-block-title font-serif">Security Check</h2>
                    <p class="text-soft fs-15px">Enter the 6-digit verification code sent to your email to continue.</p>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-icon alert-dismissible bg-success-dim border-0 mb-4 text-left">
                    <em class="icon ni ni-check-circle"></em> {{ session('success') }}
                    <button class="close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            <form action="{{ route('otp.verify') }}" method="POST" class="mt-5 text-left" style="text-align: left;">
                @csrf
                <div class="form-group mb-4">
                    <label class="form-label text-center d-block">Verification Code</label>
                    <div class="form-control-wrap text-center">
                        <input type="text" name="otp" class="form-control form-control-lg text-center fw-bold fs-24px" 
                               placeholder="000 000" maxlength="6" autofocus autocomplete="one-time-code" 
                               style="letter-spacing: 0.5rem; height: 70px;">
                    </div>
                    @error('otp') <span class="text-danger mt-2 d-block fs-12px text-center">{{ $message }}</span> @enderror
                </div>

                <div class="form-group pt-2">
                    <button type="submit" class="btn btn-lg btn-primary btn-block w-100" data-loading-text="Verifying Code...">
                        <span>Verify & Continue</span>
                        <em class="icon ni ni-shield-check ms-1"></em>
                    </button>
                </div>
            </form>

            <div class="form-note-s2 text-center pt-5">
                Didn't get the code? <br>
                <form id="resend-form" action="{{ route('otp.resend') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" id="resend-btn" class="btn btn-link link link-primary fw-bold p-0" style="text-decoration: none;" disabled>
                        Resend Code <span id="countdown-timer">(60s)</span>
                    </button>
                </form>
                <div class="mt-3">
                    <a href="{{ route('login') }}" class="link link-soft fs-13px">Sign in with another account</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let timeLeft = 60;
        const btn = document.getElementById('resend-btn');
        const timerSpan = document.getElementById('countdown-timer');

        const interval = setInterval(() => {
            timeLeft--;
            timerSpan.textContent = `(${timeLeft}s)`;

            if (timeLeft <= 0) {
                clearInterval(interval);
                btn.disabled = false;
                timerSpan.textContent = '';
                btn.style.opacity = '1';
            }
        }, 1000);
    });
</script>
@endsection
