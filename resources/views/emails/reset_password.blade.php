@extends('layouts.email')

@section('content')
<p>Hello {{ $user->name }},</p>
<p>You recently requested to reset your password for your <strong>The Curated Archive</strong> account. Click the button below to reset it:</p>

<div style="text-align: center; margin: 30px 0;">
    <a href="{{ $resetUrl }}" class="btn" style="background-color: #001f3f; color: #ffffff; padding: 12px 30px; text-decoration: none; border-radius: 4px; font-weight: bold; display: inline-block;">Reset Password</a>
</div>

<p>This password reset link will expire in 60 minutes.</p>

<p>If you did not request a password reset, no further action is required and you can safely ignore this email.</p>

<hr style="border: none; border-top: 1px solid #e5e9f2; margin: 30px 0;">

<p style="font-size: 12px; color: #8091a7;">
    If you are having trouble clicking the "Reset Password" button, copy and paste the URL below into your web browser:<br>
    <a href="{{ $resetUrl }}" style="color: #8a6d3b; word-break: break-all;">{{ $resetUrl }}</a>
</p>
@endsection

