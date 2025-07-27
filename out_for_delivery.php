<?php
session_start(); // Start the session

$conn = new mysqli('localhost', 'root', '', 'brewsnbites');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle logout
if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: admin.php"); // Redirect to admin login page
    exit;
}

// Handle order status update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];
    $status = $_POST['status'];
    $conn->query("UPDATE orders SET status='$status' WHERE id='$id'");
}

// Fetch orders with "Out for Delivery" status
$result = $conn->query("SELECT * FROM orders WHERE status = 'Out for Delivery'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Out for Delivery - Brews and Bites</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #333;
            color: #fff;
            margin: 0;
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #444;
        }
        th {
            background-color: #222;
        }
        tr:hover {
            background-color: #444;
        }
        form {
            display: inline;
        }
        select, input[type="submit"] {
            padding: 5px;
            margin-left: 5px;
            border-radius: 5px;
            border: none;
        }
        input[type="submit"] {
            background-color: #7c4d00;
            color: white;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #5b3d00;
        }
        .navbar {
            margin-bottom: 20px;
        }
        .navbar a {
            color: #fff;
            margin-right: 20px;
            text-decoration: none;
        }
        .navbar a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<h2>Out for Delivery Orders</h2>
<center>
<div class="navbar">
<a href="add_product.php" target="_blank">Add product</a>
    <a href="admin.php">View Pending Orders</a>
    <a href="processed.php">View Processed Orders</a>
    <a href="out_for_delivery.php">View Out for Delivery Orders</a>
    <a href="delivered.php">View Delivered Orders</a>
    <form method="POST" style="display:inline;">
        <input type="submit" name="logout" value="Logout" style="background-color: #b22222;">
    </form>
</div>


</center>
<table>
    <tr>
        <th>Order ID</th>
        <th>Customer Name</th>
        <th>Delivery Address</th>
        <th>Contact</th>
        <th>Orders</th>
        <th>Payment Method</th>
        <th>Total Amount</th>
        <th>InstaPay Reference Number</th>
        <th>Status</th>
        <th>Actions</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo htmlspecialchars($row['customer_name']); ?></td>
            <td><?php echo htmlspecialchars($row['customer_address']); ?></td>
            <td><?php echo htmlspecialchars($row['customer_number']); ?></td>
            <td>
            <?php
                $orderId = $row['id'];
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
            </td>
            <td><?php echo htmlspecialchars($row['payment_method']); ?></td>
            <td><?php echo htmlspecialchars($row['total_price']); ?></td>
            <td><?php echo htmlspecialchars($row['reference_number']); ?></td>
            <td><?php echo htmlspecialchars($row['status']); ?></td>
            <td>
                <form method="POST">
                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                    <select name="status">
                        <option value="Delivered">Deliver</option>
                    </select>
                    <input type="submit" value="Update">
                </form>
            </td>
        </tr>
    <?php endwhile; ?>
</table>



<?php $conn->close(); ?>
</body>
</html>
