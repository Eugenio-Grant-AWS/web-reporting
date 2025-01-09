<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Password</title>
</head>

<body>
    <h1>Your OTP Password</h1>
    <p>Hello {{ $email }},</p>
    <p>Your OTP is: <strong>{{ $otp }}</strong></p>
    <p>Please use this OTP to complete your verification.</p>
</body>

</html>
