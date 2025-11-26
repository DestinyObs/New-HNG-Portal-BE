<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>OTP Verification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            background-color: #ffffff;
            max-width: 600px;
            margin: 30px auto;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px #ccc;
        }

        .header {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            color: #333333;
        }

        .body {
            margin-top: 20px;
            font-size: 16px;
            color: #555555;
        }

        .otp {
            display: inline-block;
            margin: 20px 0;
            font-size: 28px;
            font-weight: bold;
            color: #ffffff;
            background-color: #007bff;
            padding: 10px 20px;
            border-radius: 5px;
            letter-spacing: 5px;
        }

        .footer {
            margin-top: 30px;
            font-size: 14px;
            color: #999999;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">OTP Verification</div>
        <div class="body">
            <p>Hello {{ $user->first_name . ' ' . $user->last_name }},</p>
            <p>Your OTP code is:</p>
            <div class="otp">{{ $otpCode }}</div>
            <p>This code will expire in 10 minutes. Please do not share it with anyone.</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} {{ config('app.url') }}. All rights reserved.
        </div>
    </div>
</body>

</html>
