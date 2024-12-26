<?php
// Include the database configuration file
include 'config.php';

// Check if the 'id' parameter is present in the URL
if (isset($_GET['id'])) {
    $product_id = intval($_GET['id']); // Convert to an integer for security

    // Prepare the SQL query to delete the product
    $sql = "DELETE FROM products WHERE id = ?";

    // Use prepared statements to prevent SQL injection
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $product_id); // Bind the product ID
        if ($stmt->execute()) {
            // Redirect back to view_products.php with a success message
            echo "<script>alert('Product deleted well')</script>";
            header("Location: view_products.php");
            exit();
        } else {
            // Redirect back with an error message if the query fails
            header("Location: view_products.php?message=Error deleting product");
            exit();
        }
        $stmt->close();
    }
}

// Close the database connection
$conn->close();

// Redirect back if no 'id' is provided
header("Location: view_products.php");
exit();
?>
