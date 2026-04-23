<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\AuthService;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $result = $this->authService->attemptLogin($credentials);

        if ($result['status'] === 'authenticated') {
            $request->session()->regenerate();
            return redirect()->intended($this->authService->getRedirectRoute($result['user']));
        }

        if ($result['status'] === 'otp_required') {
            $request->session()->put('otp_user_id', $result['user_id']);
            return redirect()->route('otp.show');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function showOTP()
    {
        if (!session()->has('otp_user_id')) {
            return redirect()->route('login');
        }
        return view('auth.otp');
    }

    public function verifyOTP(Request $request)
    {
        $request->validate(['otp' => 'required|numeric']);

        if (!session()->has('otp_user_id')) {
            return redirect()->route('login');
        }

        if ($this->authService->verifyOTP(session('otp_user_id'), $request->otp)) {
            $user = Auth::user();
            session()->forget('otp_user_id');
            $request->session()->regenerate();

            return redirect()->intended($this->authService->getRedirectRoute($user));
        }

        return back()->withErrors(['otp' => 'Invalid or expired OTP.']);
    }

    public function resendOTP(Request $request)
    {
        if (!session()->has('otp_user_id')) {
            return redirect()->route('login');
        }

        if ($this->authService->resendOTP(session('otp_user_id'))) {
            return back()->with('success', 'A new verification code has been sent to your email.');
        }

        return redirect()->route('login');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        return back()->with('success', 'A secure recovery link has been sent to your email.');
    }

    public function showResetPassword(Request $request, $token)
    {
        return view('auth.reset-password', ['request' => $request]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);
        
        if ($this->authService->resetPassword($request->email, $request->password)) {
            return redirect()->route('login')->with('success', 'Your password has been reset successfully.');
        }

        return back()->withErrors(['email' => 'User not found.']);
    }
}
