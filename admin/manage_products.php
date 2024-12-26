<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle the file upload for the main image
    $main_image = $_FILES['product-image'];
    $main_image_name = basename($main_image['name']);  // Keep the original file name
    $target_directory = 'imgs/';
    $target_main_image = $target_directory . $main_image_name;

    // Check if the directory exists, if not, create it
    if (!file_exists($target_directory)) {
        mkdir($target_directory, 0777, true); // Create directory if it doesn't exist
    }

    // Ensure the image doesn't already exist in the target directory
    if (file_exists($target_main_image)) {
        // If the image already exists, append a unique identifier (timestamp or random number)
        $main_image_name =$main_image_name;
        $target_main_image = $target_directory . $main_image_name;
    }

    // Upload the main image
    if (move_uploaded_file($main_image['tmp_name'], $target_main_image)) {
        echo "<script>alert('Main image uploaded successfully.')</script>";
    } else {
        echo "Failed to upload main image.<br>";
        exit;
    }

    // Handle the file upload for additional images (if any)
    $additional_images = $_FILES['additional_images'];
    $additional_images_names = [];

    if (isset($additional_images['name'])) {
        foreach ($additional_images['name'] as $key => $image_name) {
            $image_tmp_name = $additional_images['tmp_name'][$key];
            $image_new_name = basename($image_name); // Keep the original file name for additional images
            $target_image = $target_directory . $image_new_name;

            // Ensure unique file name for additional images
            if (file_exists($target_image)) {
                $image_new_name = $image_new_name;  // Append timestamp for uniqueness
                $target_image = $target_directory . $image_new_name;
            }

            if (move_uploaded_file($image_tmp_name, $target_image)) {
                $additional_images_names[] = $image_new_name;
            } else {
                echo "Failed to upload additional image: $image_name<br>";
            }
        }
    }

    // Convert the additional images names to a JSON string
    $additional_images_json = json_encode($additional_images_names);

    // Collect other form inputs
    $product_name = $_POST['product-name'];
    $price = $_POST['price'];
    $category = $_POST['category'];
    $quantity = $_POST['quantity'];
    $description = $_POST['product-description']; // Product description input

    // Prepare the SQL query
    $stmt = $conn->prepare("INSERT INTO products (name, price, category, quantity, description, main_image, additional_images) VALUES (?, ?, ?, ?, ?, ?, ?)");

    // Correct the bind_param string to match the 7 variables
    $stmt->bind_param("sdsssss", $product_name, $price, $category, $quantity, $description, $main_image_name, $additional_images_json);

    // Execute the query
    if ($stmt->execute()) {
        echo "<script>alert('Product uploaded successfully.')</script>";
    } else {
        echo "Failed to add product: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Products</title>
  <link rel="stylesheet" href="bootstrap.css">
</head>
<body>
  <?php include 'dashboard.php'; ?>
  <div class="content">
    <h2>Manage Products</h2>
    <div class="container">
      <h2 class="text-center mb-4">Manage Electronics Products</h2>
      <div class="form-container">
        <form action="#" method="POST" enctype="multipart/form-data">
          <div class="form-group">
            <label for="product-image">Upload Main Image</label>
            <input type="file" class="form-control-file" id="product-image" name="product-image" accept=".jpg, .png, .webp" required>
          </div>
          <div class="form-group">
            <label for="additional_images[]">Additional Images (multiple files allowed):</label>
            <input type="file" name="additional_images[]" id="additional_images" multiple>
          </div>
          <div class="form-group">
            <label for="product-name">Product Name</label>
            <input type="text" class="form-control" id="product-name" name="product-name" placeholder="e.g., iPhone 14 Pro" required>
          </div>
          <div class="form-group">
            <label for="price">Price (USD)</label>
            <input type="number" class="form-control" id="price" name="price" placeholder="e.g., 999" required>
          </div>
          <div class="form-group">
            <label for="category">Category</label>
            <select class="form-control" id="category" name="category" required>
              <option value="">Select a category</option>
              <option value="phones">Phones</option>
              <option value="computers">Computers</option>
              <option value="tablets">Tablets</option>
              <option value="accessories">Accessories</option>
              <option value="others">Others</option>
            </select>
          </div>
          <div class="form-group">
            <label for="quantity">Quantity</label>
            <input type="number" class="form-control" id="quantity" name="quantity" placeholder="Enter quantity in stock" required>
          </div>
          <div class="form-group">
            <label for="product-description">Product Description</label>
            <textarea class="form-control" id="product-description" name="product-description" rows="3" placeholder="Enter product description" required></textarea>
          </div>
          <button type="submit" class="btn btn-primary btn-block">Add Product</button>
        </form>
      </div>
    </div>
  </div>
  <script src="bootstrap.js"></script>
</body>
</html>
