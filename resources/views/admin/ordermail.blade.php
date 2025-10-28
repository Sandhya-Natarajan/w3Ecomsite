<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>New Order Placed</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            padding: 20px;
        }
        .container {
            background: #fff;
            border-radius: 8px;
            padding: 25px;
            max-width: 600px;
            margin: 0 auto;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }
        h2 {
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px 10px;
            text-align: left;
        }
        th {
            background: #f8f8f8;
        }
        .total {
            font-weight: bold;
            color: #222;
        }
        .footer {
            text-align: center;
            margin-top: 25px;
            color: #777;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>New Order Received 🚀</h2>
        <p>Hello Admin,</p>
        <p>A new order has been placed by <strong>{{ $order->user->name }}</strong>.</p>

        <table>
            <tr>
                <th>Order ID</th>
                <td>#{{ $order->id }}</td>
            </tr>
            <tr>
                <th>Status</th>
                <td>{{ ucfirst($order->status) }}</td>
            </tr>
            <tr>
                <th>Total Amount</th>
                <td>₹{{ number_format($order->total_amount, 2) }}</td>
            </tr>
        </table>

        <h4 style="margin-top:20px;">Products:</h4>
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Qty</th>
                    <th>Price</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->products as $product)
                <tr>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->pivot->quantity }}</td>
                    <td>₹{{ number_format($product->pivot->price, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <p class="total">Grand Total: ₹{{ number_format($order->total_amount, 2) }}</p>

        <p>Click below to view the order details:</p>
        <p><a href="#">View Order</a></p>

        <div class="footer">
            &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        </div>
    </div>
</body>
</html>
