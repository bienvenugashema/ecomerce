<?php
// Check if session is already started before calling session_start()
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Count the total number of items in the cart
$cart_count = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $product) {
        $cart_count += $product['quantity'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="bootstrap.css">
  <head>
    <title>Your Website Title</title>
    <!-- Link to the favicon -->
    <link rel="icon" type="image/x-icon" href="/logo.ico">
    <!-- For PNG favicon -->
    <!-- <link rel="icon" type="image/png" href="/favicon.png"> -->

  <style type="text/css">
body {
  margin: 0;
  font-family: Arial, sans-serif;
  background: linear-gradient(to bottom, #f6f6f6, #e9ecef);
  color: #333;
  min-height: 100vh;erl
  padding-top: 56px; /* To avoid navbar ovap */
}

.navbar {
  background-color: #232f3e; /* Amazon's dark header color */
  display: flex;
  justify-content: center;
  padding: 1rem 0;
}

.nav-links a {
  text-decoration: none;
  color: white;
  font-size: 1.2rem;
  transition: color 0.3s ease;
}
.logo{
  width: 5%;
  height: 10%;
}
.nav-links a:hover {
  color: #ff9800;
}

  </style>
</head>
<body>
   <!-- Navigation Bar -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
      <img src="logo.png" class="logo">
      <a class="navbar-brand" href="index.php">MT GLORY CO</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
          <li class="nav-item"><a class="nav-link" href="products.php">Products</a></li>
          <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
          <li class="nav-item">
            <a class="nav-link" href="cart.php">Cart <span class="cart-count"><?php echo $cart_count; ?></span></a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

</body>
</html>
