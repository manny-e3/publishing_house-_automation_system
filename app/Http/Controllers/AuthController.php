<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Mail\ResetPasswordMail;
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

        $user = User::where('email', trim($request->email))->first();

        if (!$user) {
            return back()->withErrors(['email' => 'We could not find an account associated with this email address.'])->withInput();
        }

        try {
            $token = Password::broker()->createToken($user);
            $resetUrl = route('password.reset', [
                'token' => $token,
                'email' => $user->email,
            ]);

            Mail::to($user->email)->send(new ResetPasswordMail($user, $resetUrl));

            return back()->with('success', 'A secure password recovery link has been sent to your email.');
        } catch (\Throwable $e) {
            Log::error('Password reset email failed: ' . $e->getMessage(), [
                'email' => $user->email,
                'trace' => $e->getTraceAsString(),
            ]);

            return back()->with('error', 'Unable to send recovery email. Please check your mail configuration or try again later.')->withInput();
        }
    }

    public function showResetPassword(Request $request, $token)
    {
        return view('auth.reset-password', [
            'request' => $request,
            'token' => $token,
            'email' => $request->query('email', old('email', '')),
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);
        
        $result = $this->authService->resetPasswordWithToken($request->email, $request->password, $request->token);

        if ($result['status']) {
            return redirect()->route('login')->with('success', $result['message']);
        }

        return back()->withErrors(['email' => $result['message']])->withInput($request->only('email'));
    }
}
