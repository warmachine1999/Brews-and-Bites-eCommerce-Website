<?php
session_start(); // Start the session

$conn = new mysqli('localhost', 'root', '', 'brewsnbites');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle login
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Fetch admin info from the database
    $stmt = $conn->prepare("SELECT * FROM admin WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $admin = $result->fetch_assoc();
        $_SESSION['user_id'] = $admin['id'];
        $_SESSION['username'] = $admin['username'];
    } else {
        $loginError = "Invalid username or password.";
    }
}

// Logout functionality
if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: admin.php"); // Redirect to admin login page
    exit;
}

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Display login form if not logged in
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Admin Login</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #333;
                color: #fff;
                margin: 0;
                padding: 20px;
                text-align: center;
            }
            input[type="text"], input[type="password"] {
                padding: 10px;
                margin: 10px 0;
                width: 200px;
            }
            input[type="submit"] {
                padding: 10px;
                background-color: #7c4d00;
                color: white;
                border: none;
                cursor: pointer;
            }
            input[type="submit"]:hover {
                background-color: #5b3d00;
            }
            .error {
                color: red;
            }
        </style>
    </head>
    <body>
        <h2>Admin Login</h2>
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="submit" name="login" value="Login">
        </form>
        <?php if (isset($loginError)) echo "<p class='error'>$loginError</p>"; ?>
    </body>
    </html>
    <?php
    exit;
}

// Handle order update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];
    $status = $_POST['status'];
    $conn->query("UPDATE orders SET status='$status' WHERE id='$id'");
}

// Fetch all orders
$result = $conn->query("SELECT * FROM orders WHERE status='Pending'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Brews and Bites</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
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

<h2>Admin Panel</h2>
<center>
<div class="navbar">
<a href="add_product.php" target="_blank">Add product</a>
    <a href="admin.php">View Pending Orders</a>
	<a href="processed.php">View Processed Orders</a>
    <a href="out_for_delivery.php">View Out for Delivery Orders</a>
    <a href="delivered.php">View Delivered Orders</a>
    <form method="POST" style="display:inline;">
        <input type="submit" name="logout" value="Logout">
    </form>
</div>
</center>
<h3>All Orders</h3>
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
            <td><?php echo $row['customer_name']; ?></td>
            <td><?php echo $row['customer_address']; ?></td>
            <td><?php echo $row['customer_number']; ?></td>
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
            <td><?php echo $row['payment_method']; ?></td>
            <td><?php echo $row['total_price']; ?></td>
            <td><?php echo $row['reference_number']; ?></td>
            <td><?php echo $row['status']; ?></td>
            <td>
                <form method="POST">
                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                    <select name="status">
                        <option value="Processed">Processed</option>
                        <option value="Out for Delivery">Out for Delivery</option>
                        <option value="Delivered">Delivered</option>
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
