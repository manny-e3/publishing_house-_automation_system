@extends('layouts.admin-auth')

@section('title', 'Request Access')

@section('content')
<style>
    .auth-split-visual { background-image: url('{{ asset('assets/images/register-hero.png') }}'); }
</style>
<div class="auth-split-wrapper">
    <!-- Visual Side -->
    <div class="auth-split-visual">
        <div class="auth-split-content">
            <h1 class="brand-logo-text font-serif">The Archive</h1>
            <p class="fs-18px italic font-serif mb-4">"Preserve the narratives that shape our history."</p>
            <div style="width: 40px; background: #eab308; height: 4px;"></div>
        </div>
    </div>

    <!-- Form Side -->
    <div class="auth-split-form-side">
        <div class="auth-form-inner">
            <div class="nk-block-head">
                <div class="nk-block-head-content">
                    <h2 class="nk-block-title font-serif">Request Access</h2>
                    <p class="text-soft fs-14px">Establish your credentials to join our curated network of historians, authors, and archivists.</p>
                </div>
            </div>
            
            <form action="{{ route('register') }}" method="POST" class="mt-4">
                @csrf
                <div class="form-group mb-3">
                    <label class="form-label">Full Name</label>
                    <div class="form-control-wrap">
                        <input type="text" name="name" class="form-control form-control-lg" placeholder="e.g., Jane Austen" value="{{ old('name') }}" required>
                    </div>
                    @error('name') <span class="text-danger mt-1 d-block fs-12px">{{ $message }}</span> @enderror
                </div>

                <div class="form-group mb-3">
                    <label class="form-label">Email</label>
                    <div class="form-control-wrap">
                        <input type="email" name="email" class="form-control form-control-lg" placeholder="name@domain.com" value="{{ old('email') }}" required>
                    </div>
                    @error('email') <span class="text-danger mt-1 d-block fs-12px">{{ $message }}</span> @enderror
                </div>

                <div class="form-group mb-3">
                    <label class="form-label">Password</label>
                    <div class="form-control-wrap">
                        <input type="password" name="password" class="form-control form-control-lg" placeholder="••••••••" required>
                    </div>
                    @error('password') <span class="text-danger mt-1 d-block fs-12px">{{ $message }}</span> @enderror
                </div>

                <div class="form-group mb-3">
                    <label class="form-label">Confirm Password</label>
                    <div class="form-control-wrap">
                        <input type="password" name="password_confirmation" class="form-control form-control-lg" placeholder="••••••••" required>
                    </div>
                </div>

                <div class="form-group">
                    <div class="custom-control custom-control-xs custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="terms" required>
                        <label class="custom-control-label fs-13px text-soft" for="terms">I agree to the <a href="#" class="link link-primary">Terms of Service</a> and acknowledge the Privacy Policy.</label>
                    </div>
                </div>

                <div class="form-group pt-4">
                    <button type="submit" class="btn btn-lg btn-primary btn-block">
                        <span>SUBMIT REQUEST</span>
                        <em class="icon ni ni-arrow-right"></em>
                    </button>
                </div>
            </form>

            <div class="text-center mt-5">
                <p class="fs-13px">Already hold an account? <a href="{{ route('login') }}" class="link link-primary fw-bold text-warning" style="color: #8a6d3b !important;">Log in here</a></p>
                <p class="fs-11px text-soft mt-5">&copy; {{ date('Y') }} THE CURATED ARCHIVE.</p>
            </div>
        </div>
    </div>
</div>
@endsection
