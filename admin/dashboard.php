
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
    <a href="finance.php">Finance</a>
    <a href="analytics.php">Analytics</a>
    <a href="logout.php">Logout</a>
  </div>

  <!-- Main Content -->
  <div class="content">
    <h2 class="mb-4">Welcome to the Admin Dashboard</h2>
    <p>This is your central hub for managing the platform. Select an option from the sidebar to get started.</p>
  </div>

  <script src="bootstrap.js"></script>
</body>
</html>