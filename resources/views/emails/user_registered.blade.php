<x-mail::message>
# Welcome to <?php config('app.name') ?>, {{ $company->name }}!

Your company account has been successfully created on {{ $user->created_at->format('jS F, Y') }}.

## Company Details
**Company Name:** {{ $company->name }}
**Official Email:** {{ $company->official_email }}

## Account Information
**Account Holder:** {{ $user->firstname }} {{ $user->lastname }}
**Email:** {{ $user->email }}

We're excited to have your company join our platform! You now have access to connect with talented developers and find the perfect candidates for your team.

## Next Steps
1. **Complete your company profile** - Add your logo, description, and company details
2. **Verify your company account** - Ensure full access to all features
3. **Post your first job listing** - Start attracting top talent
4. **Browse developer profiles** - Discover skilled candidates

To get started, please sign in to your account using the button below:

<x-mail::button :url="config('services.frontend.login')">
Access Company Dashboard
</x-mail::button>

If you need any assistance setting up your account or posting jobs, our support team is here to help at {{ config('mail.from.address') }}.

We look forward to helping you build an amazing team!

Best regards,<br>
The {{ config('app.name') }} Team
</x-mail::message>
