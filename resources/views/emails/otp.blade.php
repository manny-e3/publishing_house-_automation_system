@extends('layouts.email')

@section('content')
<p>Hello,</p>
<p>You are attempting to sign in to <strong>The Curated Archive</strong>. To complete your login, please use the 6-digit verification code below:</p>

<div style="background: #f4f6fa; padding: 20px; text-align: center; border-radius: 8px; margin: 25px 0;">
    <h1 style="letter-spacing: 8px; font-size: 32px; color: #1ee0ac; margin: 0;">{{ $otp }}</h1>
</div>

<p>This code will expire in 10 minutes. If you did not attempt to sign in, please ignore this email or contact support immediately.</p>

<p>Stay Secure,<br>System Security Team</p>
@endsection
