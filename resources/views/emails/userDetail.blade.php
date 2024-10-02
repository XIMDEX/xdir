<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Your Email</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .email-header {
            background-color: #007bff;
            color: #ffffff;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .email-content {
            padding: 20px;
        }
        .email-footer {
            background-color: #f4f4f4;
            color: #777777;
            padding: 10px;
            text-align: center;
            border-radius: 0 0 8px 8px;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin: 20px 0;
            font-size: 16px;
            color: #ffffff;
            background-color: #007bff;
            text-decoration: none;
            border-radius: 5px;
        }
        .btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h1>Confirm Your Email</h1>
        </div>
        <div class="email-content">
            <p>Dear {{ $base64User }},</p>
            <p>Thank you for registering with us. Please click the button below to confirm your email address:</p>
            <a href="{{ $link }}" class="btn">Confirm Email</a>
            <p>If you did not create an account, no further action is required.</p>
            <p>Best regards,</p>
            <p>The Team</p>
        </div>
        <div class="email-footer">
            <p>&copy; {{ date('Y') }} Your Company. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
  