<x-mail::message>
# Hi {{ $user->first_name }},

We received a request to reset your password for your {{ config('app.name') }} account. Click the button below to create a new password.

<x-mail::button :url="$url">
Reset Password
</x-mail::button>

If you didn't request a password reset, please ignore this email. Your current password will remain unchanged.

For security reasons, this reset link will expire in {{ now()->diffInMinutes($duration) }} minutes.

If you have any questions or need assistance, please contact our support team.

Best regards,<br>
The {{ config('app.name') }} Team
</x-mail::message>
