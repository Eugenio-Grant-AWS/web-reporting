<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
</head>

<body>
    <h1>Hello {{ $user->firstname }} {{ $user->lastname }},</h1>
    <p>Thank you for registering with us! Please verify your email by clicking the link below:</p>
    <p><a href="{{ $verificationUrl }}">Verify Email</a></p>
</body>

</html>
