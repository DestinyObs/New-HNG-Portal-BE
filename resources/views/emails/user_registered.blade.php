<x-mail::message>
    # Welcome to {{ config('app.name') }}, {{ $user->firstname }}!

    Your talent account has been successfully created on {{ $user->created_at->format('jS F, Y') }}.

    ## Account Information
    **Name:** {{ $user->firstname }} {{ $user->lastname }}
    **Email:** {{ $user->email }}

    We're excited to have you join our platform! You now have access to apply for jobs, build your profile, and connect
    with top companies.

    ## Next Steps
    1. **Complete your talent profile** – Add your skills, experience, and portfolio
    2. **Verify your email** – Unlock full account access
    3. **Upload your resume** – Stand out to employers
    4. **Start applying to job opportunities** – Your next role might be waiting!

    To get started, sign in using the button below:

    <x-mail::button :url="config('services.frontend.login')">
        Access Talent Dashboard
    </x-mail::button>

    If you need assistance setting up your profile or exploring job opportunities, our support team is here to help at
    {{ config('mail.from.address') }}.

    We’re excited to support your career journey!

    Best regards,
    The {{ config('app.name') }} Team
</x-mail::message>
