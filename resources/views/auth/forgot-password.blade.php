@extends('layouts.admin-auth')

@section('title', 'Forgot Password')

@section('content')
<div class="nk-block nk-block-middle wide-xs mx-auto pt-5">
    <div class="brand-logo pb-4 text-center">
        <a href="/" class="logo-link">
             <h4 class="font-serif">The Archive</h4>
        </a>
    </div>
    <div class="card card-bordered">
        <div class="card-inner card-inner-lg">
            <div class="nk-block-head">
                <div class="nk-block-head-content">
                    <h2 class="nk-block-title font-serif">Forgot Password</h2>
                    <div class="nk-block-des">
                        <p class="fs-15px">Enter the email address associated with your account, and we will send you a secure link to reset your password.</p>
                    </div>
                </div>
            </div>
            <form action="{{ route('password.email') }}" method="POST" class="mt-4">
                @csrf
                <div class="form-group">
                    <div class="form-label-group">
                        <label class="form-label">Email Address</label>
                    </div>
                    <div class="form-control-wrap">
                        <div class="form-icon form-icon-right">
                            <em class="icon ni ni-mail"></em>
                        </div>
                        <input type="email" name="email" class="form-control form-control-lg" placeholder="name@example.com" value="{{ old('email') }}" required>
                    </div>
                    @error('email') <span class="text-danger mt-1 d-block fs-12px">{{ $message }}</span> @enderror
                </div>
                <div class="form-group pt-2">
                    <button type="submit" class="btn btn-lg btn-primary btn-block">Send Recovery Link</button>
                </div>
            </form>
            <div class="form-note-s2 text-center pt-4">
                <a href="{{ route('login') }}" class="link link-primary d-flex align-items-center justify-content-center gap-1" style="color: #8a6d3b !important;">
                    <em class="icon ni ni-arrow-left"></em>
                    <span>Return to Sign In</span>
                </a>
            </div>
        </div>
    </div>
    <div class="text-center mt-5">
        <p class="fs-13px text-soft">Having trouble? <a href="#" class="link link-dark fw-bold">Contact Reader Support</a></p>
    </div>
</div>
@endsection
