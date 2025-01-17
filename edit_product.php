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
        'cover' => $productToEdit['cover'] 
    ];

    if (!empty($_POST['cover'])) {
        // Use the provided file path directly
        $updatedData['cover'] = $_POST['cover'];
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
        <input type="file" name="cover" id="cover" value="<?= htmlspecialchars($productToEdit['cover']); ?>">
        <br>

        <button type="submit">Save Changes</button>
    </form>
</body>
</html>