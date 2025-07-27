<?php
session_start();
include 'db.php';

// Check if cart data is sent via POST
if (isset($_POST['cart'])) {
    $cart = json_decode($_POST['cart'], true);
    $totalPrice = 0;

    if (empty($cart)) {
        header('Location: index.php');
        exit();
    }

    // Ensure cart items are aggregated correctly
    $aggregatedCart = [];
    foreach ($cart as $item) {
        $name = $item['name'];
        if (isset($aggregatedCart[$name])) {
            $aggregatedCart[$name]['quantity'] += $item['quantity'];
        } else {
            $aggregatedCart[$name] = $item;
        }
    }
    $cart = array_values($aggregatedCart);

    // Calculate total price
    foreach ($cart as &$item) {
        $totalPrice += $item['price'] * $item['quantity'];
    }
} else {
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - 7 Brews and Bites</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #333;
            color: #333;
            margin: 0;
            padding: 0;
            font-size: 16px;
        }
        .header {
            background: #222;
            padding: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: fixed;
            width: 100%;
            z-index: 1000;
        }
        .header a {
            color: white;
            text-decoration: none;
            margin: 0 15px;
            font-size: 20px;
        }
        .checkout {
            max-width: 800px;
            margin: 40px auto;
            padding: 20px;
            background: #f2f2f2;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .checkout h1 {
            text-align: center;
            color: #4caf50;
            font-size: 28px;
        }
        .cart-summary {
            margin-bottom: 30px;
            padding: 15px;
            background: #ffffff;
            border-radius: 5px;
            box-shadow: 0 1px 5px rgba(0, 0, 0, 0.1);
        }
        .cart-summary h2 {
            font-size: 24px;
        }
        .cart-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #ddd;
        }
        .cart-item h3 {
            margin: 0;
            color: #333;
            font-size: 20px;
        }
        .cart-item .price {
            color: #4caf50;
            font-weight: bold;
            font-size: 18px;
        }
        .btn {
            display: block;
            width: 100%;
            padding: 12px;
            background: #4caf50;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 18px;
            cursor: pointer;
            text-align: center;
            transition: background 0.3s;
        }
        .btn:hover {
            background: #45a049;
        }
        .notification {
            display: none;
            background-color: #e7f3fe;
            color: #31708f;
            padding: 15px;
            border: 1px solid #b3e0ff;
            border-radius: 5px;
            margin-top: 10px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-size: 18px;
        }
        .form-group input {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
    </style>
</head>
<body>

<header class="header">
    <a href="#" class="logo">
        <img src="images/logo1.png" alt="7 Brews and Bites logo" style="width: 50px;">
    </a>
    <nav class="navbar">
        <a href="index.php">home</a>
        <a href="#about">about</a>
        <a href="#menu">menu</a>
        <a href="#contact">contact</a>
    </nav>
</header>
<br><br><br><br><br><br>

<section class="checkout" id="checkout">
    <h1>Checkout</h1>
    <div class="cart-summary">
        <h2>Your Cart Items</h2>
        <form action="process_checkout.php" method="POST" id="checkout-form">
            <input type="hidden" name="cart" value='<?php echo htmlspecialchars(json_encode($cart)); ?>' />
            <div id="cart-items">
                <?php foreach ($cart as $item): ?>
                    <div class="cart-item">
                        <h3><?php echo htmlspecialchars($item['name']); ?></h3>
                        <div class="price">₱<?php echo htmlspecialchars($item['price']); ?> x 
                            <span><?php echo htmlspecialchars($item['quantity']); ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <h5>Order Price: ₱<span id="total-price"><?php echo number_format($totalPrice, 2); ?></span></h3>
			<h5>Shipping fee: ₱<span>120</span></h3>
			<h3>Total Price ₱<span id="total-price"><?php 
			$overall = $totalPrice + 120;
			echo number_format($overall, 2); ?></span></h3>
            <h2>Delivery Information</h2>
            <div class="form-group">
                <label for="customer_name">Customer Name:</label>
                <input type="text" id="customer_name" name="customer_name" required>
            </div>
            <div class="form-group">
                <label for="customer_address">Customer Address:</label>
                <input type="text" id="customer_address" name="customer_address" required>
            </div>
            <div class="form-group">
                <label for="customer_number">Customer Number:</label>
                <input type="text" id="customer_number" name="customer_number" required>
            </div>

            <h2>Select Payment Method</h2>
            <div>
                <label>
                    <input type="radio" name="payment_method" value="cash_on_delivery">
                    Cash on Delivery
                </label>
                <br>
                <label>
                    <input type="radio" name="payment_method" value="instapay" id="instapay-option">
                    Instapay
                </label>
                <div class="notification" id="instapay-notification" style="display:none;">
                    <center>
                        <p>Please Scan the QR code to pay your order.</p>
                        <img src="images/qr.png" style="width:40%; height:50%;">
                    </center>
                    <div class="form-group">
                        <label for="reference_number">Reference Number:</label>
                        <input type="text" id="reference_number" name="reference_number">
                    </div>	
                </div>
            </div>

            <button type="submit" class="btn">Complete Purchase</button>
        </form>
    </div>
</section>

<script>
    const instapayOption = document.getElementById('instapay-option');
    const instapayNotification = document.getElementById('instapay-notification');

    instapayOption.addEventListener('change', function() {
        instapayNotification.style.display = "block";
    });

    const otherOptions = document.querySelectorAll('input[name="payment_method"]:not(#instapay-option)');
    otherOptions.forEach(option => {
        option.addEventListener('change', function() {
            instapayNotification.style.display = "none";
        });
    });
</script>

</body>
</html>
