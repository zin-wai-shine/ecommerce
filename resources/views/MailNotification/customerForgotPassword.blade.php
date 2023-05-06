<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
    <style>
        /* Custom styling for the page */
        body {
            background-color: #f2f2f2;
        }
        .container {
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
            text-align: center;
            font-family: Arial, sans-serif;
            color: #333;
            max-width: 500px;
        }
        h1 {
            font-size: 28px;
            margin-bottom: 20px;
        }
        p {
            font-size: 18px;
            line-height: 1.5;
            margin-bottom: 15px;
        }
        .alert {
            background-color: #e6e6e6;
            padding: 10px;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 20px;
        }
        span.verification-code {
            display: inline-block;
            background-color: #ff9900;
            color: #fff;
            font-size: 24px;
            padding: 5px 10px;
            border-radius: 5px;
            margin: 0 5px;
        }
        img.logo {
            max-width: 200px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Reset Password</h1>
    <p>Hey there,</p>
    <p>Someone requested a new password for your {{ $email }} account.</p>
    <p>Here is your reset code:</p>
    <span class="verification-code">{{ $resetCode }}</span>
    <p>If you want to change your password, use this reset code.</p>
    <div class="alert">Please do not share this code with anyone else.</div>
    <p>If you didn’t make this request, then you can ignore this email 🙂</p>
</div>
</body>
</html>
