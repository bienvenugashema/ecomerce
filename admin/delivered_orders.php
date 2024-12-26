<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pending Orders</title>
  <link rel="stylesheet" href="bootstrap.css">
  <style>
    .sidebar {
      height: 100vh;
      background-color: #343a40;
      color: #fff;
      padding-top: 20px;
      position: fixed;
      width: 200px;
    }
    .sidebar a {
      color: #fff;
      text-decoration: none;
      display: block;
      padding: 10px 20px;
      margin-bottom: 5px;
    }
    .sidebar a:hover {
      background-color: #495057;
      border-radius: 5px;
    }
    .content {
      margin-left: 200px;
      padding: 20px;
    }
  </style>
</head>
<body>
  <!-- Sidebar -->
  <div class="sidebar">
    <h4 class="text-center">Admin Panel</h4>
    <a href="dashboard.php">Dashboard</a>
    <a href="manage_users.php">Manage Users</a>
    <a href="manage_products.php">Manage Products</a>
    <a href="orders.php">Orders</a>
    <a href="order_summary.php">Orders Summary</a>
    <a href="settings.php">Settings</a>
    <a href="view_products.php">View Products</a>
    <a href="analytics.php">Analytics</a>
    <a href="logout.php">Logout</a>
  </div>

  <!-- Main Content -->
  <div class="content">
    <h2 class="mb-4">Delivered Orders</h2>
    
    <?php
      // Fetch pending orders
      $query = "SELECT * FROM orders WHERE order_status = 'delivered' ORDER BY order_date DESC";
      $result = mysqli_query($conn, $query);
      
      if (mysqli_num_rows($result) > 0) {
    ?>
    
    <div class="table-responsive">
      <table class="table table-striped table-bordered">
        <thead class="table-dark">
          <tr>
            <th>Order ID</th>
            <th>Customer Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Address</th>
            <th>Payment Method</th>
            <th>Total Price</th>
            <th>Order Date</th>
          </tr>
        </thead>
        <tbody>
          <?php
            while ($row = mysqli_fetch_assoc($result)) {
              echo "<tr>";
              echo "<td>".$row['order_id']."</td>";
              echo "<td>".$row['buyer_name']."</td>";
              echo "<td>".$row['buyer_email']."</td>";
              echo "<td>".$row['buyer_phone']."</td>";
              echo "<td>".$row['buyer_address'].", ".$row['buyer_city'].", ".$row['buyer_state']." ".$row['buyer_zip']."</td>";
              echo "<td>".$row['payment_method']."</td>";
              echo "<td>$".number_format($row['total_price'], 2)."</td>";
              echo "<td>".date('M d, Y H:i', strtotime($row['order_date']))."</td>";
              echo "</tr>";
            }
          ?>
        </tbody>
      </table>
    </div>
    
    <?php
      } else {
        echo "<div class='alert alert-info'>No pending orders found.</div>";
      }
    ?>
  </div>

  <script src="bootstrap.js"></script>
</body>
</html>