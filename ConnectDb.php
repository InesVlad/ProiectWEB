<?php
// Database connection parameters
$host = 'localhost';
$username = 'root';
$password = '';
$dbname ='product';

// Establish the connection
$conn = mysqli_connect($host, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
// Function to retrieve all products
function get_all_products(){
    global $conn;
    $sql = "SELECT title, description, cover, price, discount_price, category FROM products";
    $result = mysqli_query($conn, $sql);

    $products = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $product = [
            "title" => $row['title'],
            "description" => $row['description'],
            "cover" => $row['cover'],
            "price" => (float)$row['price'],
            "discount_price" => isset($row['discount_price']) ? (float)$row['discount_price'] : null,
            "category" => $row['category']
        ];
        $products[]= $product;
    }
    return $products;
}

// Function to retrieve products by category
function get_products_by_category($category) {
    global $conn;
    $sql = "SELECT title, description, cover, price, discount_price, category FROM products WHERE category = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $category);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $products = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $product = [
            "title" => $row['title'],
            "description" => $row['description'],
            "cover" => '<img src="' . $row['cover'] . '" alt="Product Image" style="width:200px;">',
            "price" => (float)$row['price'],
            "discount_price" => isset($row['discount_price']) ? (float)$row['discount_price'] : null,
            "category" => $row['category']
        ];
        $products[]= $product;
    }

    mysqli_stmt_close($stmt);
    return $products;
}

// Function to retrieve a single product by title
function get_products_by_title($title) {
    global $conn;
    $sql = "SELECT title, description, cover, price, discount_price, category FROM products WHERE title = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $title);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $product = mysqli_fetch_assoc($result);
    if ($product) {
     $product['price'] = (float)$product['price'];
     $product['discount_price'] = isset($product['discount_price']) ? (float)$product['discount_price'] : null;
    }

    mysqli_stmt_close($stmt);
    return $product;
}

// Function to update a product's details by title
function update_product($title, $newData) {
    global $conn;

    // Prepare update statement
    $sql = "UPDATE products SET title = ?, description = ?, category = ?, price = ?, cover = ? WHERE title = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param(
        $stmt,
        "sssdss",
        $newData['title'],
        $newData['description'],
        $newData['category'],
        $newData['price'],
        $coverPath,
        $title
    );

    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    return $success;
}

?>