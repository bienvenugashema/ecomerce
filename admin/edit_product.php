<?php
// Include the database configuration file
include 'config.php';


// Check if the product ID is set
if (isset($_GET['id'])) {
    $product_id = intval($_GET['id']);

    // Fetch the product details
    $sql = "SELECT * FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
    } else {
        die("Product not found!");
    }
}

// Handle form submission for updating the product
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category = $_POST['category'];
    $quantity = $_POST['quantity'];
    $image = $_FILES['image']['name'];

    // Handle image upload
    if (!empty($image)) {
        
        $target_file =basename($image);
        move_uploaded_file($_FILES['image']['tmp_name'], $target_file);
    } else {
        $target_file = $product['main_image'];
    }

    // Update the product in the database
    $update_sql = "UPDATE products SET NAME = ?, description = ?, price = ?, category = ?, quantity = ?, main_image = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("ssdsisi", $name, $description, $price, $category, $quantity, $target_file, $product_id);

    if ($update_stmt->execute()) {
        echo "<script>alert('Product updated successfully!'); window.location.href = 'view_products.php';</script>";
    } else {
        echo "<script>alert('Failed to update product.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Product</title>
  <link rel="stylesheet" href="bootstrap.css">
</head>
<body>
  <div class="container mt-5">
    <h2>Edit Product</h2>
    <form action="" method="POST" enctype="multipart/form-data">
      <div class="form-group">
        <label for="name">Product Name</label>
        <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($product['NAME']); ?>" required>
      </div>
      <div class="form-group">
        <label for="description">Description</label>
        <textarea class="form-control" id="description" name="description"><?php echo htmlspecialchars($product['description']); ?></textarea>
      </div>
      <div class="form-group">
        <label for="price">Price</label>
        <input type="number" step="0.01" class="form-control" id="price" name="price" value="<?php echo htmlspecialchars($product['price']); ?>" required>
      </div>
      <div class="form-group">
        <label for="category">Category</label>
        <input type="text" class="form-control" id="category" name="category" value="<?php echo htmlspecialchars($product['category']); ?>">
      </div>
      <div class="form-group">
        <label for="quantity">Quantity</label>
        <input type="number" class="form-control" id="quantity" name="quantity" value="<?php echo htmlspecialchars($product['quantity']); ?>">
      </div>
      <div class="form-group">
        <label for="image">Product Image</label>
        <input type="file" class="form-control" id="image" name="image">
        <?php if (!empty($product['main_image'])): ?>
          <p>Current Image: <img src="imgs/<?php echo $product['main_image']; ?>" alt="Product Image" width="100"></p>
        <?php endif; ?>
      </div>
      <button type="submit" class="btn btn-success">Update Product</button>
      <a href="view_products.php" class="btn btn-secondary">Cancel</a>
    </form>
  </div>
  <script src="bootstrap.js"></script>
</body>
</html>
