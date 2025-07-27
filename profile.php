<?php
session_start();
include 'db.php';

// Redirect to login if not authenticated
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Ensure database connection is successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch user info
$userId = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

// Determine which orders to fetch based on the query parameter
$statusFilter = isset($_GET['status']) ? $_GET['status'] : 'all';

// Prepare the SQL statement for orders
if ($statusFilter !== 'all') {
    $orderStmt = $conn->prepare("SELECT * FROM orders WHERE user_id = ? AND status = ?");
    $orderStmt->bind_param("is", $userId, $statusFilter);
} else {
    $orderStmt = $conn->prepare("SELECT * FROM orders WHERE user_id = ?");
    $orderStmt->bind_param("i", $userId);
}

// Execute the statement and handle errors
if ($orderStmt->execute()) {
    $orders = $orderStmt->get_result();
} else {
    echo "Error fetching orders: " . $orderStmt->error;
    $orders = null; // Define $orders as null to avoid further issues
}

// Fetch existing reviews
$reviewStmt = $conn->prepare("SELECT order_id FROM reviews WHERE user_id = ?");
$reviewStmt->bind_param("i", $userId);
$reviewStmt->execute();
$existingReviews = $reviewStmt->get_result();
$reviewedOrderIds = [];
while ($row = $existingReviews->fetch_assoc()) {
    $reviewedOrderIds[] = $row['order_id'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - 7 Brews and Bites</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #333; /* Dark background */
            color: #fff; /* White text */
            margin: 0;
            padding: 20px;
        }
        .navbar1 {
            display: flex;
            justify-content: space-around;
            background-color: #444; /* Background color */
            padding: 10px;
            border-radius: 5px; /* Rounded corners */
            margin-bottom: 20px; /* Space below the navbar */
        }
        .nav-link {
            font-size: 15px;
            color: #fff; /* White text */
            text-decoration: none; /* Remove underline */
            padding: 10px 15px; /* Padding for clickable area */
            border-radius: 5px; /* Rounded corners */
            transition: background-color 0.3s; /* Smooth transition for hover */
        }
        .nav-link:hover {
            background-color: #7c4d00; /* Highlight color on hover */
            color: white; /* Keep text white */
        }
        .order-card {
            border: 2px solid #7c4d00;
            width: 40%;
            padding: 15px;
            margin: 10px 0;
            font-size: 18px;
        }
        main {
            margin-top: 100px; /* Adjust based on navbar height */
        }
        h2 {
            font-size: 24px; /* Larger font size for the profile heading */
            color: #7c4d00; /* Dark brown */
        }
        p {
            font-size: 18px; /* Larger font size for profile details */
            margin: 5px 0; /* Space between paragraphs */
        }
    </style>
</head>
<body>

<!-- Header section starts -->
<header class="header">
    <a href="#" class="logo">
        <img src="images/logo1.png" alt="7 Brews and Bites logo">
    </a>

    <nav class="navbar">
        <a href="index.php">home</a>
        <a href="index.php#about">about</a>
        <a href="index.php#menu">menu</a>
        <a href="index.php#products">product</a>
        <a href="index.php#review">review</a>
        <a href="index.php#contact">contact</a>
        <a href="index.php#blogs">blogs</a>
        <a href="index.php#faqs">FAQ's</a>
        <?php if (isset($_SESSION['username'])): ?>
            <a href="profile.php">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></a>
            <a href="logout.php">Logout</a>
        <?php else: ?>
            <a href="login.php">Login</a>
            <a href="register.php">Register</a>
        <?php endif; ?>
    </nav>
</header>
<!-- Header section ends -->

<main>
    <h2>Profile</h2>
    
    <!-- Check if the keys exist in the $user array before printing -->
    <p>Username: <?php echo isset($user['username']) ? htmlspecialchars($user['username']) : 'Not available'; ?></p>
    <p>Email: <?php echo isset($user['email']) ? htmlspecialchars($user['email']) : 'Not available'; ?></p>
    <p>Address: <?php echo isset($user['address']) ? htmlspecialchars($user['address']) : 'Not available'; ?></p>

    <br><br>
    <h1>Your Orders</h1>
    <div class="navbar1">
        <a href="?status=pending" class="nav-link">View Pending Orders</a>
        <a href="?status=processed" class="nav-link">View Processed Orders</a>
        <a href="?status=out for delivery" class="nav-link">View Out for Delivery Orders</a>
        <a href="?status=delivered" class="nav-link">View Delivered Orders</a>
    </div>

    <?php if ($orders && $orders->num_rows > 0): ?>
        <?php while ($order = $orders->fetch_assoc()): ?>
            <div class="order-card">
                <strong>Order ID:</strong> <?php echo htmlspecialchars($order['id']); ?><br>
                <strong>Items:</strong><br>
                <?php
                // Fetch order items for the current order
                $orderId = $order['id'];
                $orderItemsStmt = $conn->prepare("SELECT * FROM order_items WHERE order_id = ?");
                $orderItemsStmt->bind_param("i", $orderId);
                $orderItemsStmt->execute();
                $orderItems = $orderItemsStmt->get_result();

                if ($orderItems->num_rows > 0) {
                    while ($orderItem = $orderItems->fetch_assoc()) {
                        echo htmlspecialchars($orderItem['product_name']) . " - x" . htmlspecialchars($orderItem['quantity']) . "<br>";
                    }
                } else {
                    echo "No items found for this order.";
                }
                ?>
                <strong>Total:</strong> ₱<?php echo number_format($order['total_price'], 2); ?><br>
                <strong>Order Status:</strong> <?php echo htmlspecialchars($order['status']); ?><br>
                
                <?php if (trim($order['status']) === 'Delivered'): ?>
                    <?php if (!in_array($order['id'], $reviewedOrderIds)): ?>
                        <form action="submit_review.php" method="POST">
                            <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($order['id']); ?>">

                            <label for="rating">Rating:</label>
                            <select name="rating" id="rating" required>
                                <option value="">Select a rating</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                            </select><br>

                            <textarea name="review" rows="4" placeholder="Write your review..." required></textarea><br>
                            <button type="submit">Submit Review</button>
                        </form>
                    <?php else: ?>
                        <center>
                            <p>You have already reviewed this order.</p>
                        </center>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No orders found.</p>
    <?php endif; ?>
</main>

</body>
</html>