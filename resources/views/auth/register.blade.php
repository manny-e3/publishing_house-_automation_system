@extends('layouts.admin-auth')

@section('title', 'Author Registration - Griots Lounge Publishing')

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
                    <span class="badge bg-light text-muted uppercase tracking-wider mb-2 font-mono">Author Registration</span>
                    <h2 class="nk-block-title font-serif">Create Author Account</h2>
                    <p class="text-soft fs-15px">Register your author profile to submit manuscripts, track editorial assessments, and manage contracts.</p>
                </div>
            </div>
            
            <form action="{{ route('register') }}" method="POST" class="mt-4">
                @csrf
                <div class="form-group mb-3">
                    <label class="form-label">Full Name</label>
                    <div class="form-control-wrap">
                        <div class="form-icon form-icon-right">
                            <em class="icon ni ni-user"></em>
                        </div>
                        <input type="text" name="name" class="form-control form-control-lg" placeholder="e.g. Chimamanda Ngozi Adichie" value="{{ old('name') }}" required autofocus>
                    </div>
                    @error('name') <span class="text-danger mt-1 d-block fs-12px">{{ $message }}</span> @enderror
                </div>

                <div class="form-group mb-3">
                    <label class="form-label">Email Address</label>
                    <div class="form-control-wrap">
                        <div class="form-icon form-icon-right">
                            <em class="icon ni ni-mail"></em>
                        </div>
                        <input type="email" name="email" class="form-control form-control-lg" placeholder="author@domain.com" value="{{ old('email') }}" required>
                    </div>
                    @error('email') <span class="text-danger mt-1 d-block fs-12px">{{ $message }}</span> @enderror
                </div>

                <div class="form-group mb-3">
                    <label class="form-label">Password</label>
                    <div class="form-control-wrap">
                        <a href="javascript:void(0);" class="form-icon form-icon-right passcode-switch lg" data-target="password" aria-label="Toggle password visibility">
                            <em class="passcode-icon icon-show icon ni ni-eye"></em>
                            <em class="passcode-icon icon-hide icon ni ni-eye-off"></em>
                        </a>
                        <input type="password" name="password" id="password" class="form-control form-control-lg" placeholder="••••••••" required>
                    </div>
                    @error('password') <span class="text-danger mt-1 d-block fs-12px">{{ $message }}</span> @enderror
                </div>

                <div class="form-group mb-3">
                    <label class="form-label">Confirm Password</label>
                    <div class="form-control-wrap">
                        <a href="javascript:void(0);" class="form-icon form-icon-right passcode-switch lg" data-target="password_confirmation" aria-label="Toggle confirm password visibility">
                            <em class="passcode-icon icon-show icon ni ni-eye"></em>
                            <em class="passcode-icon icon-hide icon ni ni-eye-off"></em>
                        </a>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control form-control-lg" placeholder="••••••••" required>
                    </div>
                </div>

                <div class="form-group">
                    <div class="custom-control custom-control-xs custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="terms" required checked>
                        <label class="custom-control-label fs-13px text-soft" for="terms">I agree to the <a href="#" class="link link-primary">Terms of Service</a> and submission privacy policy.</label>
                    </div>
                </div>

                <div class="form-group pt-3">
                    <button type="submit" class="btn btn-lg btn-primary btn-block w-100" data-loading-text="Creating Account...">
                        <span>Create Account</span>
                        <em class="icon ni ni-arrow-right ms-1"></em>
                    </button>
                </div>
            </form>

            <div class="divider text-center my-4">
                <span class="bg-white px-3 fs-12px text-soft overline-title">OR</span>
            </div>

            <div class="text-center">
                <p class="fs-14px">Already have an author account? <a href="{{ route('login') }}" class="link link-primary fw-bold">Sign In Here</a></p>
                <p class="fs-12px text-soft mt-4">&copy; {{ date('Y') }} Griots Lounge Publishing (Nigeria & Canada).</p>
            </div>
        </div>
    </div>
</div>
@endsection
