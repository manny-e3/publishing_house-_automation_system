@extends('layouts.admin-auth')

@section('title', 'Reset Password')

@section('content')
<style>
    .auth-split-visual { background-image: url('{{ asset('assets/images/reset-hero.png') }}'); padding: 2.5rem; }
    .auth-split-visual::after { background: linear-gradient(to top, rgba(12, 16, 23, 0.7), transparent); }
    .password-strength-indicator { display: flex; gap: 4px; margin-top: 8px; }
    .strength-bar { height: 4px; flex: 1; background: #e5e9f2; border-radius: 2px; }
    .strength-bar.active { background: #eab308; }
</style>
<div class="auth-split-wrapper">
    <!-- Visual Side -->
    <div class="auth-split-visual">
        <div style="background: rgba(0,0,0,0.6); padding: 3rem; border-radius: 12px; backdrop-filter: blur(4px); z-index: 2; position: relative;">
             <h1 class="brand-logo-text font-serif">The Archive</h1>
             <p class="fs-16px font-serif">Preserving the weight of the written word for the generations to come.</p>
        </div>
    </div>

    <!-- Form Side -->
    <div class="auth-split-form-side">
        <div class="auth-form-inner">
            <div class="nk-block-head">
                <div class="d-flex align-items-center gap-1 mb-4">
                    <em class="icon ni ni-arrow-left"></em>
                    <a href="{{ route('login') }}" class="link link-soft fs-13px">Return to Login</a>
                </div>
                <div class="nk-block-head-content">
                    <h2 class="nk-block-title font-serif">Reset Password</h2>
                    <p class="text-soft fs-15px">Enter your new credentials below to secure your archive access.</p>
                </div>
            </div>
            
            <form action="{{ route('password.update') }}" method="POST" class="mt-4">
                @csrf
                <input type="hidden" name="token" value="{{ $request->route('token') }}">
                <input type="hidden" name="email" value="{{ $request->email }}">

                <div class="form-group mb-4">
                    <label class="form-label">New Password</label>
                    <div class="form-control-wrap">
                        <a href="#" class="form-control-icon form-control-icon-right passcode-switch" data-target="password">
                            <em class="passcode-icon icon-show icon ni ni-eye"></em>
                            <em class="passcode-icon icon-hide icon ni ni-eye-off"></em>
                        </a>
                        <input type="password" name="password" id="password" class="form-control form-control-lg" placeholder="Enter new password" required>
                    </div>
                    <div class="password-strength-indicator">
                        <div class="strength-bar active"></div>
                        <div class="strength-bar active"></div>
                        <div class="strength-bar"></div>
                        <div class="strength-bar"></div>
                    </div>
                    <div class="d-flex justify-content-between mt-1">
                        <span class="fs-11px text-soft">Password strength: <span class="text-warning fw-bold">Fair</span></span>
                    </div>
                    @error('password') <span class="text-danger mt-2 d-block fs-12px">{{ $message }}</span> @enderror
                </div>

                <div class="form-group mb-4">
                    <label class="form-label">Confirm Password</label>
                    <div class="form-control-wrap">
                        <input type="password" name="password_confirmation" class="form-control form-control-lg" placeholder="Confirm new password" required>
                    </div>
                </div>

                <div class="nk-block bg-lighter p-4 rounded-4 mb-4">
                    <h6 class="overline-title fs-10px mb-2 text-soft">Requirements</h6>
                    <ul class="list list-sm list-checked text-soft fs-13px">
                        <li class="d-flex align-items-center gap-2 mb-1">
                            <em class="icon ni ni-check-circle-fill text-success"></em> At least 8 characters
                        </li>
                        <li class="d-flex align-items-center gap-2 mb-1">
                            <em class="icon ni ni-check-circle-fill text-success"></em> One uppercase letter
                        </li>
                        <li class="d-flex align-items-center gap-2">
                             <em class="icon ni ni-circle text-soft"></em> One number or special character
                        </li>
                    </ul>
                </div>

                <div class="form-group pt-2">
                    <button type="submit" class="btn btn-lg btn-primary btn-block">
                        <span>Update Password</span>
                        <em class="icon ni ni-arrow-right"></em>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="nk-footer bg-lighter py-4 border-top">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center fs-10px overline-title text-soft">
            <div>&copy; {{ date('Y') }} THE CURATED ARCHIVE. ALL RIGHTS RESERVED.</div>
            <div class="d-flex gap-3">
                <a href="#">PRIVACY POLICY</a>
                <a href="#">TERMS OF SERVICE</a>
                <a href="#">COOKIE POLICY</a>
                <a href="#">ACCESSIBILITY</a>
            </div>
        </div>
    </div>
</div>
@endsection
