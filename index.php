<?php
session_start();
if(!isset($_SESSION['username'])) {
  header('Location: login.php');
  exit;
}

  // Set variables for dynamic content
  $storeName = "Balerines";
  $categories = ["Balet", "Gimnastica", "Patinaj"];
  require 'ConnectDb.php';
  $products = get_all_products();
  ?>

  <!DOCTYPE html>
  <html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Welcome to <?php echo $storeName; ?></title>
    <link rel="stylesheet" href="style.css">
    </head>
  <body>
    <h1>
      <?php
        echo " Welcome to " . $storeName;
      ?>!
    </h1>
    <p>Here are some of our product categories:</p>
    <ul>
      <?php
      // Loop through categories array
      // and display each one
      foreach ($categories as $category) {
        echo "<li>" . $category . "</li>";
      }
      ?>
    </ul>
    
    <h2>Select a Category</h2>
<form action="category.php" method="get">
    <label for="category">Choose a category:</label>
    <select name="category" id="category">
        <option value="Balet">Balet</option>
        <option value="Gimnastica">Gimnastica</option>
        <option value="Patinaj">Patinaj</option>
        <!-- Add more categories as needed -->
    </select>
    <button type="submit">Filter Products</button>
</form>

<h2>Our Featured Products</h2>
    <div class="product-list">
      <?php foreach ($products as $product): ?>
      <div class='product'>
        <h3>
          <?= htmlspecialchars($product['title']); ?>
        </h3>
        <img src="<?= htmlspecialchars($product['cover']); ?>" alt="Product Image" style="width:200px;">
        <p><strong>Description:</strong>
          <?= htmlspecialchars($product['description']); ?>
        </p>
        <p><strong>Category:</strong>
            <a href="category.php?category=<?= urlencode($product['category']); ?>">
                <?= htmlspecialchars($product['category']); ?>
            </a>
        </p>
        <?php if (isset($product['discount_price'])): ?>
        <p><strong>Price:</strong> <span style='text-decoration: line-through;'>$
            <?= number_format($product['price'], 2); ?>
          </span>
          <strong>Discounted Price:</strong> $
          <?= number_format($product['discount_price'], 2); ?>
        </p>
        <?php else: ?>
        <p><strong>Price:</strong> $
          <?= number_format($product['price'], 2); ?>
        </p>
        <?php endif; ?>
        <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true): ?>
            <p><a href="edit_product.php?title=<?= urlencode($product['title']); ?>">Edit</a></p>
        <?php endif; ?>
      </div>
      <?php endforeach; ?>
    </div>
    <a href="logout.php">Logout</a>
  </body>
  </html>

   