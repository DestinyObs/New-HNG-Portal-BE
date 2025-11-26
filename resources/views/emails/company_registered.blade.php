<x-mail::message>
# Welcome to {{ config('app.name')  }}, {{ $company->name }}!

Your company account has been successfully created on {{ $user->created_at->format('jS \o\f F, Y') }}.

## Company Details

**Company Name:** {{ $company->name }}
**Official Email:** {{ $company->official_email }}

## Account Information

**Account Holder:** {{ $user->firstname }} {{ $user->lastname }}
**Email:** {{ $user->email }}

We are glad to have your company on our platform and we will give you all the support you need.

## Next Steps

1. Complete your company profile
2. Verify your company account
3. Start posting job listings
4. Connect with talented professionals

Please click the button below to sign in to your account and begin your wonderful experience with us.

<x-mail::button :url="config('services.frontend.login')">
Sign in to my account
</x-mail::button>

Thank You,<br>
{{ config('app.name') }} Team.
</x-mail::message>

