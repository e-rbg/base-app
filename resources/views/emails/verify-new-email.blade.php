<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background-color: #f4f7f6; margin: 0; padding: 0; }
        .wrapper { padding: 40px 20px; }
        .container { max-width: 500px; margin: 0 auto; background: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        .header { background: #4f46e5; padding: 30px; text-align: center; color: white; }
        .content { padding: 40px; text-align: center; }
        .code-display { font-size: 32px; font-weight: 800; letter-spacing: 8px; color: #1f2937; background: #f3f4f6; padding: 20px; border-radius: 12px; margin: 20px 0; display: inline-block; width: 80%; }
        .footer { padding: 20px; text-align: center; font-size: 12px; color: #9ca3af; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container">
            <div class="header">
                <h1 style="margin:0; font-size: 20px;">Security Verification</h1>
            </div>
            <div class="content">
                <p style="color: #4b5563; font-size: 16px;">You recently requested to update your email address. Use the code below to confirm this change:</p>
                
                <div class="code-display">{{ $code }}</div>
                
                <p style="color: #9ca3af; font-size: 14px;">This code will expire shortly. If you did not request this change, please ignore this email.</p>
            </div>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        </div>
    </div>
</body>
</html>