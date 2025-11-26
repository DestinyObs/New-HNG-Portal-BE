<x-mail::message>
# Password Update Confirmation

This is a confirmation that your password has been successfully updated.

**Account:** {{ $user->email }}
**Date:** {{ now()->format('F j, Y \a\t g:i A T') }}

If you did not make this change, please contact our support team immediately to secure your account.

<x-mail::button :url="config('app.url')">
Access Your Account
</x-mail::button>

For security reasons, if you suspect any unauthorized activity, please reset your password immediately and notify our support team.

Best regards,<br>
The {{ config('app.name') }} Team

---
**Contact Support:** {{ config('mail.from.address') }}
</x-mail::message>
