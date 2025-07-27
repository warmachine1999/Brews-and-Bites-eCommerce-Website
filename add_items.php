<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include 'db.php'; // Include your database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_name = $_POST['product_name'];
    $price = $_POST['price'];
    $product_code = $_POST['product_code'];
    $image = $_FILES['image'];

    // Validate input
    if (empty($product_name) || empty($price) || empty($product_code) || $image['error'] !== UPLOAD_ERR_OK) {
        echo "All fields are required!";
        exit;
    }

    // Handle image upload
    $targetDir = "uploads/"; // Directory to save uploaded images
    $targetFile = $targetDir . basename($image["name"]);
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Check if image file is a valid image
    $check = getimagesize($image["tmp_name"]);
    if ($check === false) {
        echo "File is not an image.";
        exit;
    }

    // Allow certain file formats
    $allowedFormats = ['jpg', 'png', 'jpeg', 'gif'];
    if (!in_array($imageFileType, $allowedFormats)) {
        echo "Only JPG, JPEG, PNG & GIF files are allowed.";
        exit;
    }

    // Move uploaded file to the target directory
    if (!move_uploaded_file($image["tmp_name"], $targetFile)) {
        echo "Sorry, there was an error uploading your file.";
        exit;
    }

    // Insert product into database
    $stmt = $conn->prepare("INSERT INTO beverage (product_name, price, product_code, image) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sds", $product_name, $price, $product_code, $targetFile);

    if ($stmt->execute()) {
        echo "Product added successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Product</title>
</head>
<body>
    <h1>Add a New Product</h1>
    <form action="add_product.php" method="post" enctype="multipart/form-data">
        <label for="product_name">Product Name:</label>
        <input type="text" name="product_name" required><br>

        <label for="price">Product Price:</label>
        <input type="number" step="0.01" name="price" required><br>
		
        <label for="product_code">Product Code:</label>
        <select name="product_code" required>
            <option value="">Select Product Code</option>
            <option value="drinks">Drinks</option>
            <option value="cake">Cake</option>
            <option value="bread">Bread</option>
            <option value="product">Product</option>
        </select><br>

        <label for="image">Product Image:</label>
        <input type="file" name="image" accept="image/*" required><br>

        <button type="submit">Add Product</button>
    </form>
</body>
</html>
