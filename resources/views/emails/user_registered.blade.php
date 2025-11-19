<x-mail::message>
# Dear {{ $user->first_name }}

You are now been onboarded on our application on {{ $user->created_at->format('jS \o\f F, Y') }}.

We are glad to have you on our platform and we will give you all the support you need. Please find your login details below:


Please click the button below to sign in to your account, change your password and begin your wonderful experience with us.

<x-mail::button :url="config('services.frontend.login')">
Sign in to my account
</x-mail::button>

Thank You,<br>
{{ config('app.name') }} Team.
</x-mail::message>
