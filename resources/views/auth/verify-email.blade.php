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
            <div class="text-center">
                <h2 class="text-2xl font-black">Verify your email</h2>
                <p class="mt-4 opacity-70">
                    Thanks for signing up! Before getting started, please verify your email address by clicking on the link we just emailed to you.
                </p>

                <form method="POST" action="{{ route('verification.send') }}" class="mt-6">
                    @csrf
                    <x-button primary label="Resend Verification Email" type="submit" />
                </form>
            </div>
            <div class="footer">
                &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
            </div>
        </div>
        
    </div>
</body>
</html>