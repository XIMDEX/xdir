<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to {{ $organizationName }}</title>
    <style>
        /* Base styles */
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
        }
        .header {
            text-align: center;
            padding: 20px 0;
            border-bottom: 1px solid #eaeaea;
        }
        .logo {
            max-width: 150px;
            height: auto;
        }
        .content {
            padding: 30px 20px;
        }
        h1 {
            color: #2c3e50;
            font-size: 24px;
            margin-bottom: 20px;
        }
        h2 {
            color: #3498db;
            font-size: 20px;
            margin-top: 25px;
            margin-bottom: 15px;
        }
        p {
            margin-bottom: 15px;
            font-size: 16px;
        }
        .button {
            display: inline-block;
            background-color: #3498db;
            color: #ffffff !important;
            text-decoration: none;
            padding: 12px 25px;
            border-radius: 4px;
            font-weight: bold;
            margin: 20px 0;
            text-align: center;
        }
        .button:hover {
            background-color: #2980b9;
        }
        .footer {
            text-align: center;
            padding: 20px;
            font-size: 14px;
            color: #7f8c8d;
            border-top: 1px solid #eaeaea;
        }
        .social-links {
            margin: 15px 0;
        }
        .social-links a {
            display: inline-block;
            margin: 0 10px;
            color: #3498db;
            text-decoration: none;
        }
        .details {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 4px;
            margin: 20px 0;
        }
        .highlight {
            font-weight: bold;
            color: #3498db;
        }
        @media only screen and (max-width: 600px) {
            .container {
                width: 100%;
            }
            h1 {
                font-size: 22px;
            }
            .content {
                padding: 20px 15px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <!-- If you have a logo, uncomment this line and add your logo URL -->
            <!-- <img src="{{ $logoUrl ?? '' }}" alt="{{ $organizationName }}" class="logo"> -->
            <h1>Welcome to {{ $organizationName }}!</h1>
        </div>
        
        <div class="content">
            <p>Hello {{ $name }},</p>
            
            <p>We're thrilled to welcome you to <span class="highlight">{{ $organizationName }}</span>! You've been invited to join our platform, and we're excited to have you on board.</p>
            
            <h2>Getting Started</h2>
            <p>To complete your registration and access your account, please click the button below:</p>
            
            <div style="text-align: center;">
                <a href="{{ $invitationLink }}" class="button">Accept Invitation</a>
            </div>
            
            <p>This invitation link will expire in <span class="highlight">{{ $expirationDays ?? 7 }} days</span>, so please make sure to register soon.</p>
            
            <div class="details">
                <h2>Your Account Details</h2>
                <p><strong>Email:</strong> {{ $email }}</p>
                <p><strong>Organization:</strong> {{ $organizationName }}</p>
                @if(isset($role))
                <p><strong>Role:</strong> {{ $role }}</p>
                @endif
            </div>
            
            <h2>Need Help?</h2>
            <p>If you have any questions or need assistance, please don't hesitate to contact our support team at <a href="mailto:{{ $supportEmail ?? 'support@example.com' }}">{{ $supportEmail ?? 'support@example.com' }}</a>.</p>
            
            <p>We look forward to seeing you inside!</p>
            
            <p>Best regards,<br>The {{ $organizationName }} Team</p>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ $organizationName }}. All rights reserved.</p>
            
            @if(isset($unsubscribeLink))
            <p><small><a href="{{ $unsubscribeLink }}">Unsubscribe</a> from these emails.</small></p>
            @endif
            
            @if(isset($privacyPolicyLink))
            <p><small>View our <a href="{{ $privacyPolicyLink }}">Privacy Policy</a>.</small></p>
            @endif
        </div>
    </div>
</body>
</html>
