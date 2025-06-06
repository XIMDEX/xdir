<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to {{ $organizationName }}</title>
    <style>
        /* Base styles */
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .logo-container {
            text-align: center;
            background-color: #f5f5f5;
            padding: 20px 0;
        }
        .logo {
            height: 40px;
            width: auto;
        }
        .header {
            text-align: center;
            padding: 30px 20px;
            background-color: #ffffff;
        }
        h1 {
            color: #0047AB;
            font-size: 24px;
            margin: 0;
            font-weight: bold;
        }
        .content {
            padding: 0 30px 30px;
            background-color: #ffffff;
        }
        p {
            margin: 15px 0;
            font-size: 16px;
            color: #333;
        }
        .section-title {
            color: #0047AB;
            font-size: 18px;
            font-weight: bold;
            margin: 25px 0 15px;
            text-align: center;
        }
        .button-container {
            text-align: center;
            margin: 25px 0;
        }
        .button {
            display: inline-block;
            background-color: #0047AB;
            color: #ffffff !important;
            text-decoration: none;
            padding: 12px 25px;
            border-radius: 4px;
            font-weight: bold;
            text-align: center;
            font-size: 16px;
        }
        .account-details {
            margin: 25px 0;
        }
        .account-item {
            display: flex;
            align-items: center;
            margin: 10px 0;
        }
        .account-icon {
            color: #0047AB;
            margin-right: 10px;
            font-size: 16px;
        }
        .expiration-notice {
            text-align: center;
            margin: 20px 0;
            font-size: 14px;
        }
        .help-section {
            margin: 25px 0;
        }
        .footer {
            text-align: center;
            padding: 20px;
            font-size: 12px;
            color: #666;
            border-top: 1px solid #eaeaea;
        }
        .social-links {
            margin: 15px 0;
            text-align: center;
        }
        .social-links a {
            display: inline-block;
            margin: 0 10px;
            color: #666;
            text-decoration: none;
        }
        .fallback-text {
            font-size: 12px;
            color: #999;
            margin-top: 15px;
            text-align: center;
        }
        @media only screen and (max-width: 600px) {
            .container {
                width: 100%;
            }
            .content {
                padding: 0 20px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Welcome to Cognitrek</h1>
        </div>
        
        <div class="content">
            <p>Hello {{ $name }},</p>
            
            <p>We're thrilled to welcome you to <strong>Cognitrek!</strong> 🚀 You've been invited by <strong>{{ $invitedBy ?? 'our team' }}</strong> to join our platform, and we're excited to have you on board.</p>
            
            <div class="section-title">Getting Started</div>
            
            <p>To complete your registration and access your account, please click the button below:</p>
            
            <div class="button-container">
                <a href="{{ $invitationLink ?? '#' }}" class="button">ACCEPT INVITATION</a>
            </div>
        </div>
        
        <div class="footer">
            <p>If the link above isn't working, you can also access the invitation link <a href="{{ $invitationLink ?? '#' }}">{{ $invitationLink ?? '#' }}</a>.</p>
            <p>Copyright © {{ date('Y') }} Ximdex. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
