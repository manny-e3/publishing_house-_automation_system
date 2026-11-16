@extends('layouts.admin-auth')

@section('title', 'Forgot Password - Griots Lounge Publishing')

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
        <div class="auth-form-inner">
            <div class="nk-block-head">
                <div class="nk-block-head-content">
                    <span class="badge bg-light text-muted uppercase tracking-wider mb-2 font-mono">Password Recovery</span>
                    <h2 class="nk-block-title font-serif">Forgot Password</h2>
                    <p class="text-soft fs-15px">Enter the email address associated with your author or staff account, and we will send you a secure link to reset your password.</p>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-icon alert-dismissible bg-success-dim border-0 mb-4 mt-3">
                    <em class="icon ni ni-check-circle"></em> {{ session('success') }}
                    <button class="close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('status'))
                <div class="alert alert-success alert-icon alert-dismissible bg-success-dim border-0 mb-4 mt-3">
                    <em class="icon ni ni-check-circle"></em> {{ session('status') }}
                    <button class="close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-icon alert-dismissible bg-danger-dim border-0 mb-4 mt-3">
                    <em class="icon ni ni-cross-circle"></em> {{ session('error') }}
                    <button class="close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            <form action="{{ route('password.email') }}" method="POST" class="mt-4">
                @csrf
                <div class="form-group mb-4">
                    <label class="form-label">Email Address</label>
                    <div class="form-control-wrap">
                        <div class="form-icon form-icon-right">
                            <em class="icon ni ni-mail"></em>
                        </div>
                        <input type="email" name="email" class="form-control form-control-lg" placeholder="author@domain.com" value="{{ old('email') }}" required autofocus>
                    </div>
                    @error('email') <span class="text-danger mt-1 d-block fs-12px">{{ $message }}</span> @enderror
                </div>

                <div class="form-group pt-2">
                    <button type="submit" class="btn btn-lg btn-primary btn-block w-100" data-loading-text="Sending Recovery Link...">
                        <span>Send Recovery Link</span>
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
@endsection
