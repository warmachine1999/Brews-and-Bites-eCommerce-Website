<?php
session_start();
include 'db.php'; // Include your database connection

$notificationMessage = '';
$isSuccess = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_name = $_POST['product_name'];
    $price = $_POST['price'];
    $product_code = $_POST['product_code'];
    $image = $_FILES['image'];

    // Validate input
    if (empty($product_name) || empty($price) || empty($product_code) || $image['error'] !== UPLOAD_ERR_OK) {
        $notificationMessage = "All fields are required!";
    } else {
        // Check for duplicate products
        $stmt = $conn->prepare("SELECT * FROM beverage WHERE product_name = ? AND price = ? AND product_code = ?");
        $stmt->bind_param("sds", $product_name, $price, $product_code);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $notificationMessage = "This product has already been added.";
        } else {
            // Handle image upload
            $targetDir = "uploads/"; // Directory to save uploaded images

            // Debugging: Check if uploads directory exists and is writable
            if (!is_dir($targetDir)) {
                $notificationMessage = "Uploads directory does not exist.";
            } elseif (!is_writable($targetDir)) {
                $notificationMessage = "Uploads directory is not writable.";
            } else {
                $targetFile = $targetDir . basename($image["name"]);
                $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

                // Check if image file is a valid image
                $check = getimagesize($image["tmp_name"]);
                if ($check === false) {
                    $notificationMessage = "File is not an image.";
                } elseif (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
                    $notificationMessage = "Only JPG, JPEG, PNG & GIF files are allowed.";
                } elseif (!move_uploaded_file($image["tmp_name"], $targetFile)) {
                    $notificationMessage = "Sorry, there was an error uploading your file.";
                } else {
                    // Insert product into database
                    $stmt = $conn->prepare("INSERT INTO beverage (product_name, price, product_code, image) VALUES (?, ?, ?, ?)");
                    $stmt->bind_param("sdss", $product_name, $price, $product_code, $targetFile);

                    if ($stmt->execute()) {
                        $notificationMessage = "Product added successfully!";
                        $isSuccess = true;
                    } else {
                        $notificationMessage = "Error: " . $stmt->error;
                    }
                }
            }
        }

        // Close the SELECT statement, only once at the end
        if ($stmt) {
            $stmt->close();
        }
    }

    // Close the database connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #7c4d00; /* Dark brown color */
        }
        label {
            margin-top: 10px;
            font-weight: bold;
        }
        input[type="text"], input[type="number"], select, input[type="file"] {
            width: 100%;
            padding: 10px;
            margin: 5px 0 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #7c4d00; /* Dark brown color */
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #a65e2e; /* Lighter brown on hover */
        }
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 10px 20px;
            border-radius: 5px;
            z-index: 1000;
            display: none; /* Hidden by default */
        }
        .notification.success {
            background-color: green;
            color: white;
        }
        .notification.error {
            background-color: red;
            color: white;
        }
    </style>
    <script>
        function showNotification(message, isSuccess) {
            const notification = document.createElement('div');
            notification.className = 'notification ' + (isSuccess ? 'success' : 'error');
            notification.innerText = message;
            document.body.appendChild(notification);
            notification.style.display = 'block';

            setTimeout(() => {
                notification.style.opacity = '0';
                setTimeout(() => {
                    document.body.removeChild(notification);
                }, 500);
            }, 3000);
        }

        window.onload = function() {
            const message = "<?php echo addslashes($notificationMessage); ?>";
            const isSuccess = <?php echo json_encode($isSuccess); ?>;
            if (message) {
                showNotification(message, isSuccess);
            }
        };
    </script>
</head>
<body>
    <div class="container">
        <h1>Add a New Product</h1>
        <form action="add_product.php" method="post" enctype="multipart/form-data">
            <label for="product_name">Product Name:</label>
            <input type="text" name="product_name" required>

            <label for="price">Product Price:</label>
            <input type="number" step="0.01" name="price" required>
            
            <label for="product_code">Product Code:</label>
            <select name="product_code" required>
                <option value="">Select Product Code</option>
                <option value="drinks">Drinks</option>
                <option value="cake">Cake</option>
                <option value="bread">Bread</option>
                <option value="product">Product</option>
            </select>

            <label for="image">Product Image:</label>
            <input type="file" name="image" accept="image/*" required>

            <button type="submit">Add Product</button>
        </form>
    </div>
</body>
</html>