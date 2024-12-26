<?php
// Start the session to manage cart
session_start();

// Assuming you already have a connection to the database
include 'config.php';

// Check if the search query is set (for filtering products)
$search_query = isset($_GET['search']) ? $_GET['search'] : '';

// Modify the SQL query to filter products based on the search input (product name)
$query = "SELECT id, name, price, main_image FROM products WHERE name LIKE ?";
$stmt = $conn->prepare($query);
$search_term = "%" . $search_query . "%";  // Add wildcards for partial matching
$stmt->bind_param('s', $search_term);
$stmt->execute();
$result = $stmt->get_result();

// Handle adding product to cart
if (isset($_POST['add_to_cart'])) {
    // Get the product details from the form
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_image = $_POST['product_image'];

    // If the cart is not set, initialize it
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Check if the product is already in the cart
    if (isset($_SESSION['cart'][$product_id])) {
        // If the product is already in the cart, update its quantity
        $_SESSION['cart'][$product_id]['quantity']++;
    } else {
        // If the product is not in the cart, add it
        $_SESSION['cart'][$product_id] = [
            'name' => $product_name,
            'price' => $product_price,
            'image' => $product_image,
            'quantity' => 1
        ];
    }

    // Redirect to the cart page or show a success message
    echo "<script>alert('Product added to cart');</script>";
    echo "<script>window.location.href='products.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Products</title>
  <link rel="stylesheet" href="bootstrap.css">
  <style>
    .card {
      width: 20rem;
      margin: auto;
    }

    .card img {
      width: 100%; /* Ensures the image stretches across the container */
      height: 300px; /* Increased height for better visibility */
      object-fit: cover; /* Ensures the image scales and fills the space proportionally */
      border-radius: 8px; /* Rounded corners for a modern look */
    }

    .search-bar {
      margin-bottom: 20px;
    }

    .no-products {
      text-align: center;
      font-size: 18px;
      color: #888;
      margin-top: 20px;
    }

    .card-title {
      font-size: 18px;
      font-weight: bold;
    }

    .card-text {
      font-size: 16px;
    }

    .btn {
      font-size: 14px;
      padding: 10px 15px;
    }
  </style>
</head>
<body>
  <?php include 'header.php'; ?>

  <!-- Search Form -->
  <div class="container my-4">
    <form action="" method="GET">
      <div class="input-group search-bar">
        <input type="text" class="form-control" name="search" placeholder="Search products..." value="<?php echo htmlspecialchars($search_query); ?>">
        <div class="input-group-append">
          <button class="btn btn-outline-secondary" type="submit">Search</button>
        </div>
      </div>
    </form>
  </div>

  <div class="container my-4">
    <div class="row">
      <!-- Loop through the products and display them -->
      <?php if ($result->num_rows > 0) { ?>
        <?php while ($product = $result->fetch_assoc()) { ?>
          <div class="col-md-4 mb-4">
            <div class="card">
              <a href="product_detail.php?id=<?php echo $product['id']; ?>">
                <img class="card-img-top" src="imgs/<?php echo $product['main_image']; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
              </a>
              <div class="card-body text-center">
                <h5 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h5>
                <p class="card-text">$<?php echo number_format($product['price'], 2); ?></p>
                
                <!-- Add to Cart Form -->
                <form action="products.php" method="POST">
                  <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                  <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($product['name']); ?>">
                  <input type="hidden" name="product_price" value="<?php echo $product['price']; ?>">
                  <input type="hidden" name="product_image" value="<?php echo $product['main_image']; ?>">
                  <button type="submit" name="add_to_cart" class="btn btn-success">Add to Cart</button>
                </form>
              </div>
            </div>
          </div>
        <?php } ?>
      <?php } else { ?>
        <p class="no-products">No products found matching your search.</p>
      <?php } ?>
    </div>
  </div>

  <script src="bootstrap.js"></script>
</body>
<?php 

include 'footer.php';

?>
</html>
