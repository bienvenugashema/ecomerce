
<?php include 'config.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard</title>
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
    .stats-card {
      transition: transform 0.2s;
    }
    .stats-card:hover {
      transform: translateY(-5px);
    }
    .card-pending {
      border-left: 4px solid #ffc107;
    }
    .card-cancelled {
      border-left: 4px solid #dc3545;
    }
    .card-delivered {
      border-left: 4px solid #28a745;
    }
  </style>
</head>
<body>
  <?php


    // Count pending orders
    $pending_query = "SELECT COUNT(*) as pending_count FROM orders WHERE order_status = 'pending'";
    $pending_result = mysqli_query($conn, $pending_query);
    $pending_count = mysqli_fetch_assoc($pending_result)['pending_count'];

    // Count canceled orders (fixed spelling)
    $canceled_query = "SELECT COUNT(*) as canceled_count FROM orders WHERE order_status = 'canceled'";
    $canceled_result = mysqli_query($conn, $canceled_query);
    $canceled_count = mysqli_fetch_assoc($canceled_result)['canceled_count'];

    // Count delivered orders
    $delivered_query = "SELECT COUNT(*) as delivered_count FROM orders WHERE order_status = 'delivered'";
    $delivered_result = mysqli_query($conn, $delivered_query);
    $delivered_count = mysqli_fetch_assoc($delivered_result)['delivered_count'];

    mysqli_close($conn);
  ?>

  <!-- Sidebar -->
  <div class="sidebar">
    <h4 class="text-center">Admin Panel</h4>
    <a href="dashboard.php">Dashboard</a>
    <a href="manage_users.php">Manage Users</a>
    <a href="manage_products.php">Manage Products</a>
    <a href="orders.php">Orders</a>
    <a href="settings.php">Settings</a>
    <a href="view_products.php">View Products</a>
    <a href="analytics.php">Analytics</a>
    <a href="logout.php">Logout</a>
  </div>

  <!-- Main Content -->
  <div class="content">
    <h2 class="mb-4">Welcome to the Admin Dashboard</h2>
    <p>This is your central hub for managing the platform. Select an option from the sidebar to get started.</p>
    
    <!-- Order Statistics -->
    <div class="row mt-4">
      <!-- Pending Orders -->
      <div class="col-md-4 mb-4">
        <div class="card stats-card card-pending h-100">
          <div class="card-body">
            <h5 class="card-title text-warning">Pending Orders</h5>
            <h2 class="card-text mb-2"><?php echo $pending_count; ?></h2>
            <p class="card-text text-muted">Awaiting processing</p>
            <a href="pending_orders.php" class="btn btn-warning btn-sm">View Details</a>
          </div>
        </div>
      </div>

      <!-- Canceled Orders -->
      <div class="col-md-4 mb-4">
        <div class="card stats-card card-cancelled h-100">
          <div class="card-body">
            <h5 class="card-title text-danger">Canceled Orders</h5>
            <h2 class="card-text mb-2"><?php echo $canceled_count; ?></h2>
            <p class="card-text text-muted">Orders canceled</p>
            <a href="canceled_orders.php" class="btn btn-danger btn-sm">View Details</a>
          </div>
        </div>
      </div>

      <!-- Delivered Orders -->
      <div class="col-md-4 mb-4">
        <div class="card stats-card card-delivered h-100">
          <div class="card-body">
            <h5 class="card-title text-success">Delivered Orders</h5>
            <h2 class="card-text mb-2"><?php echo $delivered_count; ?></h2>
            <p class="card-text text-muted">Successfully delivered</p>
            <a href="delivered_orders.php" class="btn btn-success btn-sm">View Details</a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="bootstrap.js"></script>
</body>
</html>