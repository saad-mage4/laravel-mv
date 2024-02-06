<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wishlist Discount Notification</title>
</head>
<body>
<h1>Great News!</h1>

<p>Dear valued customer,</p>

<p>We are excited to inform you that one of the products in your wishlist is now discounted!</p>

<p>Product: <strong>{{ $details['product_name'] }}</strong></p>
<p>Original Price: ${{ $details['old_price'] }}</p>
<p>New Discounted Price: ${{ $details['new_price'] }}</p>

<p>Hurry, take advantage of this special offer now!</p>

<p>For any questions or to make a purchase, please visit our website or contact our customer support.</p>

<p>Best regards,<br>
    TradeMag</p>
</body>
</html>
