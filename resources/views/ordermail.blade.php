<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Order Confirmation</title>
</head>
<body>
<h2>Hello {{ $order->user->name }},</h2>
<p>Your order <strong>#{{ $order->id }}</strong> has been placed successfully!</p>
<p><Strong>Total: </Strong>Rs {{ $order->total_amount }}</p>
<p>We will notify you once it ships.</p>
<p>Thank you for shopping with {{ config('app.name') }}!</p>
</body>
</html>
