<?php
session_start();
include 'db.php'; // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        echo "<script>alert('You must log in first'); window.location.href='login.php';</script>";
        exit();
    }

    // Check if cart is set and not empty
    if (isset($_POST['cart']) && !empty($_POST['cart'])) {
        $cart = json_decode($_POST['cart'], true); // Decode the cart data from JSON

        // Get user ID from session
        $userId = $_SESSION['user_id'];

        // Customer information
        $customerName = $_POST['customer_name'];
        $customerAddress = $_POST['customer_address'];
        $customerNumber = $_POST['customer_number'];
        $paymentMethod = $_POST['payment_method'];
        $referenceNumber = ($paymentMethod === 'cash_on_delivery') ? 'N/A' : $_POST['reference_number'];


        // Calculate total price
        $totalPrice = 0;
		foreach ($cart as $item) {
			if ($item['quantity'] > 0) {
				$totalPrice += $item['price'] * $item['quantity'];
    }
}

// Add shipping cost
$shippingCost = 120;
$totalPrice += $shippingCost;


        // Prepare an SQL statement to insert the order into the database
        $stmt = $conn->prepare("INSERT INTO orders (user_id, customer_name, customer_address, customer_number, payment_method, reference_number, total_price) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssssd", $userId, $customerName, $customerAddress, $customerNumber, $paymentMethod, $referenceNumber, $totalPrice);

        if ($stmt->execute()) {
            $orderId = $stmt->insert_id; // Get the last inserted order ID

            // Loop through cart items and insert each into order_items table
            foreach ($cart as $item) {
                if ($item['quantity'] > 0) {
                    $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_name, price, quantity) VALUES (?, ?, ?, ?)");
                    $stmt->bind_param("isdi", $orderId, $item['name'], $item['price'], $item['quantity']);
                    $stmt->execute();
                }
            }

            // Clear the cart
            unset($_SESSION['cart']);

            // Redirect to a thank you page or order confirmation page
            header('Location: thank_you.php'); // Create a thank you page for confirmation
            exit();
        } else {
            // Handle SQL execution error
            echo "Error: " . $stmt->error;
        }
    } else {
        // Handle empty cart case
        header('Location: index.php'); // Redirect to index if cart is empty
        exit();
    }
} else {
    // Redirect if accessed directly
    header('Location: index.php');
    exit();
}
?>
