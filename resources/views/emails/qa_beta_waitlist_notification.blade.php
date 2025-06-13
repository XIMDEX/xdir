<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🚀 Welcome to CogniTrek Beta Waitlist!</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* Base styles */
        body {
            font-family: 'Poppins', 'Arial', sans-serif;
            line-height: 1.8;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f8f9ff;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background: linear-gradient(135deg, #ffffff 0%, #f8f9ff 100%);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(74, 108, 247, 0.1);
            border: 1px solid #e0e7ff;
        }
        .header {
            background: linear-gradient(135deg, #4a6cf7 0%, #6c5ce7 100%);
            color: white;
            padding: 40px 20px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
            position: relative;
            z-index: 2;
        }
        .header::after {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200px;
            height: 200px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
            z-index: 1;
        }
        .content {
            padding: 40px;
            color: #4a5568;
        }
        .welcome-text {
            font-size: 18px;
            margin-bottom: 30px;
            line-height: 1.7;
        }
        .highlight-box {
            background: #f0f4ff;
            border-left: 4px solid #4a6cf7;
            padding: 20px;
            margin: 25px 0;
            border-radius: 0 8px 8px 0;
        }
        .emoji {
            font-size: 24px;
            margin-right: 10px;
            vertical-align: middle;
        }
        .cta-button {
            display: inline-block;
            padding: 14px 32px;
            margin: 25px 0;
            background: linear-gradient(135deg, #4a6cf7 0%, #6c5ce7 100%);
            color: white !important;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 600;
            text-align: center;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(74, 108, 247, 0.3);
        }
        .cta-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(74, 108, 247, 0.4);
        }
        .footer {
            text-align: center;
            padding: 25px 20px;
            font-size: 13px;
            color: #718096;
            background-color: #f8f9ff;
            border-top: 1px solid #e2e8f0;
        }
        .social-links {
            margin: 20px 0;
        }
        .social-links a {
            margin: 0 10px;
            color: #4a6cf7;
            text-decoration: none;
            font-size: 20px;
            transition: all 0.3s ease;
        }
        .social-links a:hover {
            color: #3b50ce;
        }
        @media only screen and (max-width: 600px) {
            .container {
                width: 100% !important;
                margin: 0;
                border-radius: 0;
            }
            .content {
                padding: 25px 20px;
            }
            .header {
                padding: 30px 15px;
            }
            .header h1 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎉 Welcome to CogniTrek Beta Waitlist!</h1>
        </div>
        
        <div class="content">
            <p class="welcome-text">
                <span class="emoji">👋</span> <strong>Hello {{ $name }},</strong>
            </p>
            
            <p>Thank you for your interest in the <strong>CogniTrek Beta program</strong>! We're thrilled to have you join our waitlist and can't wait for you to experience what we've been building. 🚀</p>
            
            <div class="highlight-box">
                <p style="margin: 0;">
                    <span class="emoji">✨</span> <strong>Great news!</strong> Your application has been received and you've been successfully added to our waitlist.
                </p>
            </div>
            
            
            <p><span class="emoji">⏳</span> <strong>What's next?</strong> Our team is currently reviewing applications, and you'll receive a notification as soon as your access is approved. We appreciate your patience!</p>
            
            <p style="text-align: center;">
                <a href="mailto:{{ $supportEmail }}" class="cta-button">
                    <span class="emoji">💬</span> Need Help? Contact Us
                </a>
            </p>
            <p>Thank you for being part of this exciting journey! We can't wait to have you on board. 🎊</p>
            
            <p>Best regards,<br><strong>Ximdex Team</strong> 👋</p>
        </div>
        
        <div class="footer">
            <p>© {{ date('Y') }} Ximdex. All rights reserved.</p>
            <p>You're receiving this email because you've joined the waitlist for the CogniTrek Beta program.</p>
            <p style="margin-top: 15px;">
                <a href="{{ $unsubscribeLink }}" style="color: #4a6cf7; text-decoration: none; margin: 0 10px;">Unsubscribe</a> • 
                <a href="{{ $privacyPolicyLink }}" style="color: #4a6cf7; text-decoration: none; margin: 0 10px;">Privacy Policy</a>
            </p>
        </div>
    </div>
</body>
</html>
