<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Process main image
    $main_image = $_FILES['main_image'];
    $main_image_name = time() . '_' . basename($main_image['name']);
    $target_main_image = 'imgs/' . $main_image_name;

    if (move_uploaded_file($main_image['tmp_name'], $target_main_image)) {
        echo "Main image uploaded successfully.<br>";
    } else {
        echo "Failed to upload main image.<br>";
        exit;
    }

    // Process additional images
    $additional_images = $_FILES['additional_images'];
    $additional_images_names = [];

    foreach ($additional_images['name'] as $key => $image_name) {
        $image_tmp_name = $additional_images['tmp_name'][$key];
        $image_new_name = time() . '_' . basename($image_name);
        $target_image = 'imgs/' . $image_new_name;

        if (move_uploaded_file($image_tmp_name, $target_image)) {
            $additional_images_names[] = $image_new_name;
        } else {
            echo "Failed to upload additional image: $image_name<br>";
        }
    }

    // Store the additional images as a JSON string
    $additional_images_json = json_encode($additional_images_names);

    // Insert product details into the database
    $stmt = $conn->prepare("INSERT INTO products (name, description, price, category, quantity, main_image, additional_images) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdsiss", $product_name, $product_description, $product_price, $product_category, $product_quantity, $main_image_name, $additional_images_json);

    // Sample data for insertion
    $product_name = "Sample Product";
    $product_description = "This is a sample product description.";
    $product_price = 19.99;
    $product_category = "Electronics";
    $product_quantity = 10;

    if ($stmt->execute()) {
        echo "Product added successfully!";
    } else {
        echo "Failed to add product: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
