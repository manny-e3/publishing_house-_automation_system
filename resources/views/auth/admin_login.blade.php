@extends('layouts.admin-auth')

@section('title', 'Staff Sign In - Griots Lounge Publishing')

@section('content')
<div class="auth-split-wrapper">
    <!-- Visual Side -->
    <div class="auth-split-visual">
        <div class="auth-split-content">
            <span class="badge bg-white-10 text-white text-uppercase tracking-widest fs-11px mb-3">Staff & Management Gateway</span>
            <h1 class="brand-logo-text font-serif text-white">Griots Lounge</h1>
            <p class="fs-18px italic font-serif mb-4 text-white-50">"Editorial Excellence, Design Innovation & Production Integrity."</p>
            <div style="width: 48px; height: 4px; background: #96BBA4; border-radius: 2px;"></div>
        </div>
    </div>

    <!-- Form Side -->
    <div class="auth-split-form-side">
        <div class="auth-form-inner">
            <div class="nk-block-head">
                <div class="nk-block-head-content">
                    <span class="badge bg-warning-dim text-warning uppercase tracking-widest mb-2 font-mono">Staff & Management Gateway</span>
                    <h2 class="nk-block-title font-serif">Staff Sign In</h2>
                    <p class="text-soft fs-15px">Sign in to access editorial appraisals, acquisitions, design workstreams, and system administration.</p>
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
            
            <form action="{{ route('admin.login.post') }}" method="POST" class="mt-4">
                @csrf
                <div class="form-group mb-4">
                    <label class="form-label">Staff Work Email</label>
                    <div class="form-control-wrap">
                        <div class="form-icon form-icon-right">
                            <em class="icon ni ni-mail"></em>
                        </div>
                        <input type="email" name="email" class="form-control form-control-lg" placeholder="staff@griotslounge.com" value="{{ old('email') }}" required autofocus>
                    </div>
                    @error('email') <span class="text-danger mt-1 d-block fs-12px">{{ $message }}</span> @enderror
                </div>

                <div class="form-group mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <label class="form-label mb-0">Password</label>
                        <a href="{{ route('password.request') }}" class="link fs-13px text-primary">Forgot Password?</a>
                    </div>
                    <div class="form-control-wrap">
                        <a href="javascript:void(0);" class="form-icon form-icon-right passcode-switch lg" data-target="admin_password" aria-label="Toggle password visibility">
                            <em class="passcode-icon icon-show icon ni ni-eye"></em>
                            <em class="passcode-icon icon-hide icon ni ni-eye-off"></em>
                        </a>
                        <input type="password" name="password" id="admin_password" class="form-control form-control-lg" placeholder="Enter security passphrase" required>
                    </div>
                    @error('password') <span class="text-danger mt-1 d-block fs-12px">{{ $message }}</span> @enderror
                </div>

                <div class="form-group mb-4 d-flex justify-content-between align-items-center">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="remember" name="remember">
                        <label class="custom-control-label text-soft fs-13px" for="remember">Keep this workstation signed in</label>
                    </div>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-lg btn-primary btn-block w-100" data-loading-text="Authenticating Staff...">
                        <em class="icon ni ni-shield-check me-1"></em> <span>Secure Staff Authenticate</span>
                    </button>
                </div>
            </form>

            <div class="text-center pt-4 border-top mt-4">
                <span class="text-soft fs-13px">Author or Client? <a href="{{ route('login') }}" class="link text-primary fw-bold">Sign in via Author Portal</a></span>
            </div>
        </div>
    </div>
</div>
@endsection

