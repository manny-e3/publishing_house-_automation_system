<x-mail::message>
# Welcome, {{ $user->name }}!

Your payment was successful, and a new account has been automatically created for you to track your project.

**Login Details:**
- **Email:** {{ $user->email }}
- **Password:** {{ $password }}

Please log in and consider changing your password in your settings.

<x-mail::button :url="route('login')">
Login to Dashboard
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
