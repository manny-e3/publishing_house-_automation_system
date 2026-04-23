<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\OTPMailable;
use Carbon\Carbon;

class AuthController extends Controller
{
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

        $user = User::where('email', $credentials['email'])->first();

        if ($user && Hash::check($credentials['password'], $user->password)) {
            $otpEnabled = \App\Models\Setting::get('enable_otp', '1') == '1';

            if (!$otpEnabled) {
                // Bypass OTP
                Auth::login($user);
                $request->session()->regenerate();
                return $this->redirectUser($user);
            }

            // Generate OTP
            $otp = rand(100000, 999999);
            $user->otp = $otp;
            $user->otp_expires_at = Carbon::now()->addMinutes(10);
            $user->save();

            // Send OTP Email
            Mail::to($user->email)->send(new OTPMailable($otp));

            // Store user ID in session temporarily
            $request->session()->put('otp_user_id', $user->id);

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

        $user = User::find(session('otp_user_id'));

        if ($user && $user->otp == $request->otp && Carbon::now()->isBefore($user->otp_expires_at)) {
            // Clear OTP
            $user->otp = null;
            $user->otp_expires_at = null;
            $user->save();

            // Login
            Auth::login($user);
            session()->forget('otp_user_id');
            $request->session()->regenerate();

            return $this->redirectUser($user);
        }

        return back()->withErrors(['otp' => 'Invalid or expired OTP.']);
    }

    public function resendOTP(Request $request)
    {
        if (!session()->has('otp_user_id')) {
            return redirect()->route('login');
        }

        $user = User::find(session('otp_user_id'));
        if ($user) {
            $otp = rand(100000, 999999);
            $user->otp = $otp;
            $user->otp_expires_at = Carbon::now()->addMinutes(10);
            $user->save();

            Mail::to($user->email)->send(new OTPMailable($otp));
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
        // For simulation, just return success. 
        // Real logic would use Password::broker()
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
        
        $user = User::where('email', $request->email)->first();
        if ($user) {
            $user->password = Hash::make($request->password);
            $user->save();
            return redirect()->route('login')->with('success', 'Your password has been reset successfully.');
        }

        return back()->withErrors(['email' => 'User not found.']);
    }

    private function redirectUser($user)
    {
        // Redirect to appropriate dashboard based on role
        if ($user->hasRole('admin') || $user->hasRole('acquisitions')) {
            return redirect()->intended(route('admin.dashboard'));
        } elseif ($user->hasRole('finance')) {
            return redirect()->intended(route('admin.invoices.index'));
        } elseif ($user->hasRole('editorial')) {
            return redirect()->intended(route('admin.projects.index'));
        } elseif ($user->hasRole('prospect')) {
            return redirect()->intended(route('author.dashboard'));
        }

        return redirect()->intended(route('admin.dashboard'));
    }
}
