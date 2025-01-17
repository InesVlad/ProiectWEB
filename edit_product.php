<?php
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

// Include the database functions
require 'ConnectDb.php';

// Get the product title from the query string
$titleToEdit = $_GET['title'] ?? '';
$productToEdit = get_products_by_title($titleToEdit);

if (!$productToEdit) {
    die("Product not found.");
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Create an array to hold updated data
    $updatedData = [
        'title' => $_POST['title'],
        'description' => $_POST['description'],
        'category' => $_POST['category'],
        'price' => (float)$_POST['price'],
        'cover' => $productToEdit['cover'] // Keep existing cover as default
    ];

    // Handle file upload if a new file is provided
    if (isset($_FILES['cover']) && $_FILES['cover']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'covers/'; // Make sure this directory exists and is writable
        $fileExtension = pathinfo($_FILES['cover']['name'], PATHINFO_EXTENSION);
        $fileName = uniqid('product_', true) . '.' . $fileExtension;
        $targetPath = $uploadDir . $fileName;
        
        // Move uploaded file to target directory
        if (move_uploaded_file($_FILES['cover']['tmp_name'], $targetPath)) {
            $updatedData['cover'] = $targetPath;
        } else {
            echo "Error uploading the file.";
            exit;
        }
    }

    // Update the product in the database
    $updateSuccess = update_product($titleToEdit, $updatedData);

    if ($updateSuccess) {
        header("Location: index.php");
        exit;
    } else {
        echo "Error updating the product.";
    }
}

// Database connection
$host = "localhost"; // Your database host
$user = "root"; // Your database username
$password = ""; // Your database password
$database = "your_database_name"; // Your database name


// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if (isset($_POST['submit'])) {
    // Check if a file is uploaded
    if (!empty($_FILES['image']['name'])) {
        // Get the file path
        $fileName = $_FILES['image']['name'];
        $fileTmpName = $_FILES['image']['tmp_name'];
        $uploadDir = "uploads/"; // Directory to save uploaded files

        // Ensure the upload directory exists
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Move the uploaded file to the upload directory
        $filePath = $uploadDir . basename($fileName);
        if (move_uploaded_file($fileTmpName, $filePath)) {
            // Insert file path into the database
            $sql = "INSERT INTO images (file_path) VALUES ('$filePath')";
            if ($conn->query($sql) === TRUE) {
                echo "Image uploaded and saved to database successfully!";
            } else {
                echo "Error saving to database: " . $conn->error;
            }
        } else {
            echo "Failed to upload the image.";
        }
    } else {
        echo "Please select an image to upload.";
    }
}

// Close the database connection
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Product - <?= htmlspecialchars($productToEdit['title']); ?></title>
</head>
<body>
    <h1>Edit Product - <?= htmlspecialchars($productToEdit['title']); ?></h1>
    <form method="post" enctype="multipart/form-data">
        <label for="title">Title:</label>
        <input type="text" name="title" id="title" value="<?= htmlspecialchars($productToEdit['title']); ?>" required>
        <br>

        <label for="description">Description:</label>
        <textarea name="description" id="description" required><?= htmlspecialchars($productToEdit['description']); ?></textarea>
        <br>

        <label for="category">Category:</label>
        <input type="text" name="category" id="category" value="<?= htmlspecialchars($productToEdit['category']); ?>" required>
        <br>

        <label for="price">Price:</label>
        <input type="number" name="price" id="price" step="0.01" value="<?= htmlspecialchars($productToEdit['price']); ?>" required>
        <br>

        <label for="cover">Cover Image:</label>
        <input type="file" name="cover" id="cover">
        <br>

        <button type="submit">Save Changes</button>
    </form>
</body>
</html>