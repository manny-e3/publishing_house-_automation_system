@extends('layouts.admin-auth')

@section('title', 'Sign In')

@section('content')
<div class="auth-split-wrapper">
    <!-- Visual Side -->
    <div class="auth-split-visual">
        <div class="auth-split-content">
            <h1 class="brand-logo-text font-serif">The Archive</h1>
            <p class="fs-18px italic font-serif mb-4">"Curating the essence of thought, preserving the elegance of expression."</p>
            <div style="width: 40px; hieght: 4px; background: #eab308; height: 4px;"></div>
        </div>
    </div>

    <!-- Form Side -->
    <div class="auth-split-form-side">
        <div class="auth-form-inner">
            <div class="nk-block-head">
                <div class="nk-block-head-content">
                    <h2 class="nk-block-title font-serif">Sign In</h2>
                    <p class="text-soft fs-15px">Access your curated collections and essays.</p>
                </div>
            </div>
            
            <form action="{{ route('login.post') }}" method="POST" class="mt-5">
                @csrf
                <div class="form-group mb-4">
                    <label class="form-label">Email Address</label>
                    <div class="form-control-wrap">
                        <div class="form-icon form-icon-right">
                            <em class="icon ni ni-mail"></em>
                        </div>
                        <input type="email" name="email" class="form-control form-control-lg" placeholder="Enter your email" value="{{ old('email') }}" required>
                    </div>
                    @error('email') <span class="text-danger mt-1 d-block fs-12px">{{ $message }}</span> @enderror
                </div>

                <div class="form-group mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <label class="form-label mb-0">Password</label>
                        <a href="{{ route('password.request') }}" class="link link-primary fw-bold text-warning" style="color: #8a6d3b !important;">Forgot Password?</a>
                    </div>
                    <div class="form-control-wrap">
                        <a href="#" class="form-control-icon form-control-icon-right passcode-switch lg" data-target="password">
                            <em class="passcode-icon icon-show icon ni ni-eye"></em>
                            <em class="passcode-icon icon-hide icon ni ni-eye-off"></em>
                        </a>
                        <input type="password" name="password" id="password" class="form-control form-control-lg" placeholder="Enter your password" required>
                    </div>
                    @error('password') <span class="text-danger mt-1 d-block fs-12px">{{ $message }}</span> @enderror
                </div>

                <div class="form-group pt-2">
                    <button type="submit" class="btn btn-lg btn-primary btn-block">
                        <span>Sign In</span>
                        <em class="icon ni ni-arrow-right"></em>
                    </button>
                </div>
            </form>

            <div class="divider text-center my-5">
                <span class="bg-white px-3 fs-12px text-soft overline-title">OR</span>
            </div>

            <div class="text-center">
                <p class="fs-14px">Don't have an account? <a href="{{ route('register') }}" class="link link-primary fw-bold text-warning" style="color: #8a6d3b !important;">Register Now</a></p>
            </div>
        </div>
    </div>
</div>
@endsection
