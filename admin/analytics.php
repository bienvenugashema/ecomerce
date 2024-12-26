<?php
// Include the database configuration file
include 'config.php';

// Fetch total number of orders
$orderCountQuery = "SELECT COUNT(*) AS order_count FROM orders";
$orderCountResult = $conn->query($orderCountQuery);
$orderCount = $orderCountResult->fetch_assoc()['order_count'];

// Fetch total sales amount
$totalSalesQuery = "SELECT SUM(total_price) AS total_sales FROM orders";
$totalSalesResult = $conn->query($totalSalesQuery);
$totalSales = $totalSalesResult->fetch_assoc()['total_sales'];

// Fetch total number of products
$productCountQuery = "SELECT COUNT(*) AS product_count FROM products";
$productCountResult = $conn->query($productCountQuery);
$productCount = $productCountResult->fetch_assoc()['product_count'];

// Fetch the number of customers (unique buyers)
$customerCountQuery = "SELECT COUNT(DISTINCT buyer_email) AS customer_count FROM orders";
$customerCountResult = $conn->query($customerCountQuery);
$customerCount = $customerCountResult->fetch_assoc()['customer_count'];

// Fetch the number of orders shipped
$shippedOrderQuery = "SELECT COUNT(*) AS shipped_orders FROM orders WHERE order_status = 'shipped'";
$shippedOrderResult = $conn->query($shippedOrderQuery);
$shippedOrderCount = $shippedOrderResult->fetch_assoc()['shipped_orders'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Analytics - Admin Dashboard</title>
  <link rel="stylesheet" href="bootstrap.css">
</head>
<body>
  <?php include 'dashboard.php'; ?>
  
  <div class="content">
    <h2>Analytics</h2>
    <p>Welcome to the Analytics section, where you can track your business performance.</p>

    <!-- Analytics Overview -->
    <div class="mt-4 row">
      <div class="col-md-3 mb-3">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Total Orders</h5>
            <p class="card-text"><?php echo $orderCount; ?> Orders</p>
          </div>
        </div>
      </div>
      
      <div class="col-md-3 mb-3">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Total Sales</h5>
            <p class="card-text">$<?php echo number_format($totalSales, 2); ?></p>
          </div>
        </div>
      </div>
      
      <div class="col-md-3 mb-3">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Total Products</h5>
            <p class="card-text"><?php echo $productCount; ?> Products</p>
          </div>
        </div>
      </div>
      
      <div class="col-md-3 mb-3">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Total Customers</h5>
            <p class="card-text"><?php echo $customerCount; ?> Customers</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Order Shipping Status -->
    <div class="mt-4">
      <h4>Order Status Overview</h4>
      <div class="row">
        <div class="col-md-4">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Shipped Orders</h5>
              <p class="card-text"><?php echo $shippedOrderCount; ?> Orders Shipped</p>
            </div>
          </div>
        </div>
        
        <!-- Additional statistics can be added here -->
      </div>
    </div>
  </div>
  
  <script src="bootstrap.js"></script>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
