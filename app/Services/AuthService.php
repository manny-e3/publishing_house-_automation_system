<?php

namespace App\Services;

use App\Models\User;
use App\Models\Setting;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\OTPMailable;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AuthService
{
    /**
     * Attempt to authenticate a user and handle OTP if enabled.
     */
    public function attemptLogin(array $credentials): array
    {
        $user = User::where('email', $credentials['email'])->first();

        if ($user && Hash::check($credentials['password'], $user->password)) {
            $otpEnabled = Setting::get('enable_otp', '1') == '1';

            if (!$otpEnabled) {
                Auth::login($user);
                return ['status' => 'authenticated', 'user' => $user];
            }

            // Generate OTP
            $otp = rand(100000, 999999);
            $user->otp = $otp;
            $user->otp_expires_at = Carbon::now()->addMinutes(10);
            $user->save();

            // Send OTP Email
            Mail::to($user->email)->send(new OTPMailable($otp));

            return ['status' => 'otp_required', 'user_id' => $user->id];
        }

        return ['status' => 'failed'];
    }

    /**
     * Verify OTP and log in the user.
     */
    public function verifyOTP(int $userId, string $otp): bool
    {
        $user = User::find($userId);

        if ($user && $user->otp == $otp && Carbon::now()->isBefore($user->otp_expires_at)) {
            // Clear OTP
            $user->otp = null;
            $user->otp_expires_at = null;
            $user->save();

            // Login
            Auth::login($user);
            return true;
        }

        return false;
    }

    /**
     * Resend OTP to the user.
     */
    public function resendOTP(int $userId): bool
    {
        $user = User::find($userId);
        if ($user) {
            $otp = rand(100000, 999999);
            $user->otp = $otp;
            $user->otp_expires_at = Carbon::now()->addMinutes(10);
            $user->save();

            Mail::to($user->email)->send(new OTPMailable($otp));
            return true;
        }
        return false;
    }

    /**
     * Register a new user and assign a default role.
     */
    public function registerUser(array $data, string $role = 'prospect'): User
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $user->assignRole($role);
        
        Auth::login($user);

        return $user;
    }

    /**
     * Reset user password.
     */
    public function resetPassword(string $email, string $password): bool
    {
        $user = User::where('email', $email)->first();
        if ($user) {
            $user->password = Hash::make($password);
            $user->save();
            return true;
        }
        return false;
    }

    /**
     * Determine the redirect route based on user role.
     */
    public function getRedirectRoute(User $user): string
    {
        if ($user->hasRole('admin') || $user->hasRole('acquisitions')) {
            return route('admin.dashboard');
        } elseif ($user->hasRole('finance')) {
            return route('admin.invoices.index');
        } elseif ($user->hasRole('editorial')) {
            return route('admin.projects.index');
        } elseif ($user->hasRole('prospect')) {
            return route('author.dashboard');
        }

        return route('admin.dashboard');
    }
}
