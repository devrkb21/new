<!DOCTYPE html>
<html>
<head>
    <title>Subscription Renewal Reminder</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6;">
    <h2>Subscription Renewal Reminder</h2>
    <p>Hello {{ $user->name }},</p>
    <p>
        This is a reminder that your <strong>{{ $site->plan->name }}</strong> plan for the site
        <strong>{{ $site->domain }}</strong> is scheduled to expire on 
        <strong>{{ $site->plan_expires_at->format('F d, Y') }}</strong>.
    </p>
    <p>
        To ensure uninterrupted service, please log in to your account to renew your subscription.
    </p>
    <p>
        <a href="{{ route('dashboard') }}" style="display: inline-block; padding: 10px 20px; background-color: #4f46e5; color: #ffffff; text-decoration: none; border-radius: 5px;">
            Go to My Dashboard
        </a>
    </p>
    <p>
        Thank you for being a valued customer!
    </p>
</body>
</html>