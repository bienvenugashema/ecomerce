<?php
include 'config.php';

// Start the session to manage the cart
session_start();

// Check if the cart is empty
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header('Location: products.php');
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate and sanitize input
    $buyer_name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $buyer_email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $buyer_phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING);
    $buyer_address = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_STRING);
    $buyer_city = filter_input(INPUT_POST, 'city', FILTER_SANITIZE_STRING);
    $buyer_state = filter_input(INPUT_POST, 'state', FILTER_SANITIZE_STRING);
    $buyer_zip = filter_input(INPUT_POST, 'zip', FILTER_SANITIZE_STRING);
    $buyer_country = filter_input(INPUT_POST, 'country', FILTER_SANITIZE_STRING);
    $payment_method = filter_input(INPUT_POST, 'payment_method', FILTER_SANITIZE_STRING);

    // Validate required fields
    if (!$buyer_name || !$buyer_email || !$buyer_phone || !$buyer_address || 
        !$buyer_city || !$buyer_state || !$buyer_zip || !$buyer_country || !$payment_method) {
        die('Please fill in all required fields');
    }

    // Validate email format
    if (!filter_var($buyer_email, FILTER_VALIDATE_EMAIL)) {
        die('Invalid email format');
    }

    try {
        // Calculate total price from the cart
        $total_price = 0;
        foreach ($_SESSION['cart'] as $product_id => $product) {
            $total_price += $product['price'] * $product['quantity'];
        }

        // Prepare the order insertion statement
        $stmt = $conn->prepare("INSERT INTO orders (buyer_name, buyer_email, buyer_phone, buyer_address, 
                               buyer_city, buyer_state, buyer_zip, buyer_country, payment_method, 
                               total_price, status) 
                               VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Pending')");
        
        $stmt->bind_param("sssssssssd", $buyer_name, $buyer_email, $buyer_phone, $buyer_address,
                         $buyer_city, $buyer_state, $buyer_zip, $buyer_country, $payment_method, 
                         $total_price);
        
        // Execute the order insertion
        if ($stmt->execute()) {
            $order_id = $conn->insert_id;

            // Prepare the order items insertion statement
            $stmt_items = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, total_price) 
                                        VALUES (?, ?, ?, ?)");

            // Insert each product in the cart
            foreach ($_SESSION['cart'] as $product_id => $product) {
                $quantity = $product['quantity'];
                $product_total_price = $product['price'] * $quantity;
                
                $stmt_items->bind_param("iiid", $order_id, $product_id, $quantity, $product_total_price);
                $stmt_items->execute();
            }

            // Prepare email content with proper formatting
            $email_content = "New Order #$order_id\n\n";
            $email_content .= "Order Details:\n";
            $email_content .= str_repeat("-", 40) . "\n";
            
            foreach ($_SESSION['cart'] as $product_id => $product) {
                $email_content .= sprintf("%-30s $%8.2f x %2d = $%8.2f\n",
                    substr($product['name'], 0, 30),
                    $product['price'],
                    $product['quantity'],
                    $product['price'] * $product['quantity']
                );
            }
            
            $email_content .= str_repeat("-", 40) . "\n";
            $email_content .= sprintf("%30s $%8.2f\n", "Total:", $total_price);
            $email_content .= "\nShipping Details:\n";
            $email_content .= "$buyer_name\n$buyer_address\n$buyer_city, $buyer_state $buyer_zip\n$buyer_country\n";
            $email_content .= "\nContact Information:\n";
            $email_content .= "Email: $buyer_email\nPhone: $buyer_phone\n";
            $email_content .= "\nPayment Method: $payment_method\n";

            // Send email using proper headers
            $to = 'bienvenugashema@gmail.com';
            $subject = "New Order #$order_id - E-commerce Site";
            $headers = [
                'From' => 'noreply@ecommerce.com',
                'Reply-To' => 'noreply@ecommerce.com',
                'X-Mailer' => 'PHP/' . phpversion(),
                'Content-Type' => 'text/plain; charset=UTF-8'
            ];

            mail($to, $subject, $email_content, $headers);

            // Clear the cart
            unset($_SESSION['cart']);

            // Redirect to thank you page
            header('Location: thank_you.php');
            exit();
        }
    } catch (Exception $e) {
        // Log the error and show a user-friendly message
        error_log($e->getMessage());
        die('An error occurred while processing your order. Please try again later.');
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Checkout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .checkout-container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 1rem;
        }
        .cart-summary {
            background-color: #f8f9fa;
            padding: 1rem;
            border-radius: 0.5rem;
            margin-bottom: 2rem;
        }
        .checkout-form {
            background-color: #fff;
            padding: 2rem;
            border-radius: 0.5rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
        }
        .total-price {
            font-size: 1.25rem;
            font-weight: bold;
            color: #198754;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="checkout-container">
        <h1 class="mb-4">Secure Checkout</h1>

        <div class="cart-summary">
            <h3>Order Summary</h3>
            <?php if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])): ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th class="text-end">Price</th>
                            <th class="text-center">Quantity</th>
                            <th class="text-end">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $total_price = 0;
                        foreach ($_SESSION['cart'] as $product_id => $product):
                            $product_total = $product['price'] * $product['quantity'];
                            $total_price += $product_total;
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($product['name']); ?></td>
                            <td class="text-end">$<?php echo number_format($product['price'], 2); ?></td>
                            <td class="text-center"><?php echo $product['quantity']; ?></td>
                            <td class="text-end">$<?php echo number_format($product_total, 2); ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <tr>
                            <td colspan="3" class="text-end"><strong>Total:</strong></td>
                            <td class="text-end total-price">$<?php echo number_format($total_price, 2); ?></td>
                        </tr>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>

        <form action="checkout.php" method="POST" class="checkout-form needs-validation" novalidate>
            <div class="row g-3">
                <div class="col-12">
                    <h3>Billing Information</h3>
                </div>

                <div class="col-12">
                    <label for="name" class="form-label">Full Name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                    <div class="invalid-feedback">Please enter your full name.</div>
                </div>

                <div class="col-md-6">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                    <div class="invalid-feedback">Please enter a valid email address.</div>
                </div>

                <div class="col-md-6">
                    <label for="phone" class="form-label">Phone Number</label>
                    <input type="tel" class="form-control" id="phone" name="phone" required>
                    <div class="invalid-feedback">Please enter your phone number.</div>
                </div>

                <div class="col-12">
                    <label for="address" class="form-label">Street Address</label>
                    <input type="text" class="form-control" id="address" name="address" required>
                    <div class="invalid-feedback">Please enter your street address.</div>
                </div>

                <div class="col-md-6">
                    <label for="city" class="form-label">City</label>
                    <input type="text" class="form-control" id="city" name="city" required>
                    <div class="invalid-feedback">Please enter your city.</div>
                </div>

                <div class="col-md-6">
                    <label for="state" class="form-label">State/Province</label>
                    <input type="text" class="form-control" id="state" name="state" required>
                    <div class="invalid-feedback">Please enter your state/province.</div>
                </div>

                <div class="col-md-6">
                    <label for="zip" class="form-label">Zip/Postal Code</label>
                    <input type="text" class="form-control" id="zip" name="zip" required>
                    <div class="invalid-feedback">Please enter your zip/postal code.</div>
                </div>

                <div class="col-md-6">
                    <label for="country" class="form-label">Country</label>
                    <input type="text" class="form-control" id="country" name="country" required>
                    <div class="invalid-feedback">Please enter your country.</div>
                </div>

                <div class="col-12">
                    <label for="payment_method" class="form-label">Payment Method</label>
                    <select class="form-select" id="payment_method" name="payment_method" required>
                        <option value="">Choose a payment method</option>
                        <option value="credit_card">Credit Card</option>
                        <option value="debit_card">Debit Card</option>
                        <option value="paypal">PayPal</option>
                        <option value="bank_transfer">Bank Transfer</option>
                    </select>
                    <div class="invalid-feedback">Please select a payment method.</div>
                </div>

                <div class="col-12 mt-4">
                    <button class="btn btn-primary w-100" type="submit">Place Order</button>
                </div>
            </div>
        </form>
    </div>

    <script>
        // Form validation script
        (function () {
            'use strict'
            var forms = document.querySelectorAll('.needs-validation')
            Array.prototype.slice.call(forms)
                .forEach(function (form) {
                    form.addEventListener('submit', function (event) {
                        if (!form.checkValidity()) {
                            event.preventDefault()
                            event.stopPropagation()
                        }
                        form.classList.add('was-validated')
                    }, false)
                })
        })()
    </script>
</body>
</html>