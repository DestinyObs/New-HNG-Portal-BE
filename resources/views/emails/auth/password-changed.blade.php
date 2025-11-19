<x-mail::message>
# Hi {{ $user->firstname ?? $user->company->name }},

We wanted to let you know that your password was successfully changed.

If you did not make this change or if you believe an unauthorized person has accessed your account, please reset your password immediately and contact our support team.

Warm regards,<br>
{{ config('app.name') }} Team.
</x-mail::message>
