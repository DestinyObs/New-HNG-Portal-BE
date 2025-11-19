<x-mail::message>
# Hi {{ $user->first_name }},

We received a request to reset your password. Click the button below to reset your password.

<x-mail::button :url="$url">
Reset your password
</x-mail::button>

If you did not request a password reset, please ignore this email. Your password will remain unchanged.

For security purposes, this link will expire in {{ now()->diffInMinutes($duration) }} minutes.

If you have any questions or need further assistance, please contact our support team.

Warm Regards,<br>
{{ config('app.name') }} Team.
</x-mail::message>
