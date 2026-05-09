<!DOCTYPE html>
<html>
<head>
    <title>Welcome to Our Website</title>
</head>
<body>
    <h1>Hello {{ $user->firstname }} {{ $user->middlename }} {{ $user->lastname }}!</h1>
    <p>Thank you for registering on our website.</p>
    <p>
        <a href="{{ url('/activate-account/'.$user->email) }}"style="background-color:#4CAF50;color:white;padding:10px 20px;text-decoration:none;">
            Click to activate your account
        </a>
    </p>
    <p>We are happy to have you onboard!</p>
</body>
</html>
