<!DOCTYPE html>
<html>
<body>
    <h2>Hi {{ $waitlist->full_name }},</h2>

    <p>Thanks for joining our waitlist! 🎉</p>

    <p>
        We're excited to have you on board. We'll notify you once the platform goes live.
    </p>

    <p>
        Regards,<br>
        {{ config('app.name') }} Team
    </p>
</body>
</html>
