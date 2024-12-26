<?php
// Assuming you already have a connection to the database
include 'config.php';

// Start the session to manage the cart
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Get the product ID from the URL
$product_id = isset($_GET['id']) ? $_GET['id'] : 0;

// Fetch product details from the database
$query = "SELECT id, name, price, description, main_image, additional_images FROM products WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
  $product = $result->fetch_assoc();
} else {
  echo "Product not found!";
  exit;
}

// Handle Add to Cart
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_image = $_POST['product_image'];
    $quantity = 1;

    // Initialize the cart session if not set
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Check if the product is already in the cart
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] += $quantity; // Increase quantity
    } else {
        // Add new product to the cart
        $_SESSION['cart'][$product_id] = [
            'name' => $product_name,
            'price' => $product_price,
            'image' => $product_image,
            'quantity' => $quantity
        ];
    }

    echo "<script>alert('Product added to cart successfully!');</script>";
    echo "<script>window.location.href='cart.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $product['name']; ?> - Product Detail</title>
  <link rel="stylesheet" href="bootstrap.css">
  <style>
    /* General Styles */
    .product-images img {
      width: 200px; 
      height: auto;
      margin-bottom: 10px;
    }
    .product-details {
      display: flex;
      flex-wrap: wrap;
      justify-content: space-between;
    }
    .product-images {
      flex: 1;
      max-width: 45%;
      margin-right: 20px;
      text-align: center;
    }
    .product-description {
      flex: 1;
      max-width: 50%;
    }
    .product-price {
      font-size: 24px;
      font-weight: bold;
      color: green;
    }
    .btn-add-to-cart {
      background-color: #28a745;
      color: white;
      font-size: 16px;
      padding: 10px 20px;
      border: none;
      cursor: pointer;
    }
  </style>
</head>
<body>
  <div class="container my-4">
    <div class="product-details">
      <!-- Product Images Section -->
      <div class="product-images">
        <img src="imgs/<?php echo $product['main_image']; ?>" alt="Main Image">
        <?php
          // Decode the additional images from JSON
          $additional_images = json_decode($product['additional_images'], true);
          if (!empty($additional_images)) {
            foreach ($additional_images as $image) {
              echo "<img src='imgs/$image' alt='Additional Image'>";
            }
          }
        ?>
      </div>

      <!-- Product Information Section -->
      <div class="product-description">
        <h2><?php echo $product['name']; ?></h2>
        <p class="product-price">$<?php echo $product['price']; ?></p>
        <p><strong>Description:</strong></p>
        <p><?php echo nl2br($product['description']); ?></p>

        <!-- Add to Cart Button -->
        <form method="POST" action="">
          <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
          <input type="hidden" name="product_name" value="<?php echo $product['name']; ?>">
          <input type="hidden" name="product_price" value="<?php echo $product['price']; ?>">
          <input type="hidden" name="product_image" value="<?php echo $product['main_image']; ?>">
          <button type="submit" name="add_to_cart" class="btn-add-to-cart">Add to Cart</button>
        </form>
      </div>
    </div>
  </div>

  <script src="bootstrap.js"></script>
</body>
</html>
