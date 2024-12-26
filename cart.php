<?php
// Start the session to manage the cart
session_start();

// Check if the cart is empty
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    $cart_empty = true;
} else {
    $cart_empty = false;
}

// Handle updating quantity
if (isset($_POST['update_quantity'])) {
    $product_id = $_POST['product_id'];
    $new_quantity = $_POST['quantity'];

    if ($new_quantity > 0) {
        // Update quantity
        $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
    } else {
        // Remove product if quantity is less than 1
        unset($_SESSION['cart'][$product_id]);
    }
}

// Handle removing product
if (isset($_GET['remove'])) {
    $product_id = $_GET['remove'];
    unset($_SESSION['cart'][$product_id]);
    echo "<script>alert('Product removed from cart');</script>";
    echo "<script>window.location.href='cart.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cart</title>
  <link rel="stylesheet" href="styles.css">
  <style>
    .cart-image {
      width: 100px; /* Resize image to 100px */
      height: auto;
    }

    .cart-table {
      width: 100%;
      border-collapse: collapse;
    }

    .cart-table th, .cart-table td {
      padding: 10px;
      text-align: left;
      border: 1px solid #ddd;
    }

    .cart-summary {
      margin-top: 20px;
      font-size: 1.5rem;
    }

    .btn-update, .btn-remove, .btn-checkout {
      padding: 5px 10px;
      background-color: #28a745;
      color: white;
      border: none;
      cursor: pointer;
    }

    .btn-remove {
      background-color: #dc3545;
    }

    .btn-checkout {
      background-color: #007bff;
    }
  </style>
</head>
<body>
  <?php include 'header.php'; ?>

  <div class="container">
    <h1>Your Cart</h1>
    <p>View the items you have added to your cart and proceed to checkout.</p>

    <?php if ($cart_empty): ?>
        <p>Your cart is empty. <a href="products.php">Browse products</a></p>
    <?php else: ?>
        <table class="cart-table">
          <thead>
            <tr>
              <th>Image</th>
              <th>Product Name</th>
              <th>Price</th>
              <th>Quantity</th>
              <th>Total</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php 
            $total_price = 0;
            foreach ($_SESSION['cart'] as $product_id => $product): 
                $total_price += $product['price'] * $product['quantity'];
            ?>
            <tr>
              <td><img src="imgs/<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" class="cart-image"></td>
              <td><?php echo $product['name']; ?></td>
              <td>$<?php echo number_format($product['price'], 2); ?></td>
              <td>
                <form action="cart.php" method="POST">
                  <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                  <input type="number" name="quantity" value="<?php echo $product['quantity']; ?>" min="1" required>
                  <button type="submit" name="update_quantity" class="btn-update">Update</button>
                </form>
              </td>
              <td>$<?php echo number_format($product['price'] * $product['quantity'], 2); ?></td>
              <td><a href="cart.php?remove=<?php echo $product_id; ?>" class="btn-remove">Remove</a></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>

        <div class="cart-summary">
          <h3>Total: $<?php echo number_format($total_price, 2); ?></h3>
          <a href="checkout.php" class="btn-checkout">Proceed to Checkout</a>
        </div>
    <?php endif; ?>
  </div>

  <script src="scripts.js"></script>
</body>
</html>
