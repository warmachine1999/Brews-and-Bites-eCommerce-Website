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

// Get the order ID from the POST request
$orderId = isset($_POST['order_id']) ? $_POST['order_id'] : null;

// Fetch the order details
if ($orderId) {
    $orderStmt = $conn->prepare("SELECT * FROM orders WHERE id = ?");
    $orderStmt->bind_param("i", $orderId);
    $orderStmt->execute();
    $order = $orderStmt->get_result()->fetch_assoc();

    // Check if the order exists
    if (!$order) {
        die("Order not found.");
    }
}

// Check if the order status is 'Delivered'
if (isset($order) && $order['status'] === 'Delivered') {
    // Handle the review submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['review'], $_POST['rating'])) {
        $userId = $_SESSION['user_id'];
        $reviewText = trim($_POST['review']); // Trim to remove any extra spaces
        $rating = $_POST['rating']; // Get the rating from the dropdown

        // Check if review text and rating are not empty
        if (!empty($reviewText) && !empty($rating)) {
            // Check if the user has already reviewed this order
            $checkReviewStmt = $conn->prepare("SELECT * FROM reviews WHERE order_id = ? AND user_id = ?");
            $checkReviewStmt->bind_param("ii", $orderId, $userId);
            $checkReviewStmt->execute();
            $existingReview = $checkReviewStmt->get_result()->fetch_assoc();

            if ($existingReview) {
                echo "";
            } else {
                // Insert the new review with the selected rating
                $insertReviewStmt = $conn->prepare("INSERT INTO reviews (order_id, user_id, review, rating) VALUES (?, ?, ?, ?)");
                $insertReviewStmt->bind_param("iisi", $orderId, $userId, $reviewText, $rating); // Use the rating from the form

                if ($insertReviewStmt->execute()) {
                    echo "Review submitted successfully!";
                } else {
                    echo "Error submitting review: " . $insertReviewStmt->error;
                }
            }
        } else {
            echo "";
        }
    }
} else {
    echo "";
}

// Optional: Display existing reviews
$reviewsStmt = $conn->prepare("SELECT * FROM reviews WHERE order_id = ?");
$reviewsStmt->bind_param("i", $orderId);
$reviewsStmt->execute();
$reviews = $reviewsStmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Review</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            background-color: #f5f5dc; /* Light beige background */
            color: #3e2723; /* Dark brown text */
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            text-align: center;
        }

        h1 {
            font-size: 2.5em;
            margin-bottom: 20px;
            color: #6d4c41; /* Coffee color */
        }

        p {
            font-size: 1.2em;
            margin-bottom: 30px;
            max-width: 600px; /* Limiting paragraph width for better readability */
        }

        a {
            text-decoration: none;
            background-color: #4e342e; /* Dark brown button */
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        a:hover {
            background-color: #5d4037; /* Darker brown on hover */
        }
    </style>
</head>
<body>
<h1>Thank You for Your Review!</h1>
    <h2>We truly appreciate you taking the time to share your thoughts and feedback with us.</h2> 
	<h2>Your insights are invaluable in helping us enhance our services and ensure we continue to meet your expectations. </h2>
	<h2>We're glad to hear your experience and look forward to serving you again soon!</h2>
	<br>
    <a href="profile.php">Go back to your profile</a>
</body>
</html>
