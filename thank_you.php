<?php
// Start the session to get the order data
session_start();

// Check if order_id is set in the session (after redirecting from checkout page)
if (!isset($_SESSION['order_id'])) {
    header('Location: index.php');  // Redirect to the home page if no order data is found
    exit();
}

// Get the order details (you can customize this based on what information you want to display)
$order_id = $_SESSION['order_id'];
$total_price = $_SESSION['total_price'];

// Optionally unset the order session data if it's no longer needed
unset($_SESSION['order_id']);
unset($_SESSION['total_price']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <div class="container mt-5">
        <div class="text-center">
            <h1 class="mb-4">Thank You for Your Order!</h1>
            <p>We appreciate your business and are processing your order.</p>
            <h3>Order Details:</h3>
            <p><strong>Order ID:</strong> <?php echo $order_id; ?></p>
            <p><strong>Total Price:</strong> $<?php echo number_format($total_price, 2); ?></p>
            <p>Your order is being processed, and you will receive a confirmation email shortly.</p>
            <p>If you have any questions, feel free to <a href="contact.php">contact us</a>.</p>
            <a href="index.php" class="btn btn-primary mt-4">Back to Home</a>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
