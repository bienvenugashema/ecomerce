<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Financial Dashboard</title>
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
    .finance-card {
      transition: all 0.3s;
      border-left: 4px solid #28a745;
    }
    .finance-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
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
    <h2 class="mb-4">Financial Overview</h2>

    <?php
      // Calculate total potential revenue from products (price * quantity)
      $total_inventory_query = "SELECT SUM(price * quantity) as total_inventory_value FROM products";
      $inventory_result = mysqli_query($conn, $total_inventory_query);
      $total_inventory = mysqli_fetch_assoc($inventory_result)['total_inventory_value'];

      // Calculate today's revenue
      $today_revenue_query = "SELECT SUM(total_price) as today_revenue FROM orders WHERE DATE(order_date) = CURDATE()";
      $today_result = mysqli_query($conn, $today_revenue_query);
      $today_revenue = mysqli_fetch_assoc($today_result)['today_revenue'];

      // Calculate monthly revenue
      $monthly_revenue_query = "SELECT SUM(total_price) as monthly_revenue FROM orders WHERE MONTH(order_date) = MONTH(CURRENT_DATE()) AND YEAR(order_date) = YEAR(CURRENT_DATE())";
      $monthly_result = mysqli_query($conn, $monthly_revenue_query);
      $monthly_revenue = mysqli_fetch_assoc($monthly_result)['monthly_revenue'];
    ?>

    <!-- Revenue Overview Cards -->
    <div class="row mb-4">
      <div class="col-md-4">
        <div class="card finance-card">
          <div class="card-body">
            <h5 class="card-title">Total Inventory Value</h5>
            <h2 class="card-text text-success">$<?php echo number_format($total_inventory, 2); ?></h2>
            <p class="text-muted">Total value of all products in inventory</p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card finance-card">
          <div class="card-body">
            <h5 class="card-title">Today's Revenue</h5>
            <h2 class="card-text text-success">$<?php echo number_format($today_revenue, 2); ?></h2>
            <p class="text-muted">Revenue generated today</p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card finance-card">
          <div class="card-body">
            <h5 class="card-title">Monthly Revenue</h5>
            <h2 class="card-text text-success">$<?php echo number_format($monthly_revenue, 2); ?></h2>
            <p class="text-muted">Revenue this month</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Monthly Revenue Table -->
    <div class="card mb-4">
      <div class="card-header">
        <h5 class="mb-0">Monthly Revenue Breakdown</h5>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-striped">
            <thead>
              <tr>
                <th>Month</th>
                <th>Revenue</th>
                <th>Number of Orders</th>
                <th>Average Order Value</th>
              </tr>
            </thead>
            <tbody>
              <?php
                $monthly_breakdown_query = "
                  SELECT 
                    DATE_FORMAT(order_date, '%Y-%m') as month,
                    SUM(total_price) as revenue,
                    COUNT(*) as order_count,
                    AVG(total_price) as avg_order
                  FROM orders 
                  GROUP BY DATE_FORMAT(order_date, '%Y-%m')
                  ORDER BY month DESC
                  LIMIT 6";
                $monthly_breakdown_result = mysqli_query($conn, $monthly_breakdown_query);
                
                while ($row = mysqli_fetch_assoc($monthly_breakdown_result)) {
                  echo "<tr>";
                  echo "<td>" . date('F Y', strtotime($row['month'] . '-01')) . "</td>";
                  echo "<td>$" . number_format($row['revenue'], 2) . "</td>";
                  echo "<td>" . number_format($row['order_count']) . "</td>";
                  echo "<td>$" . number_format($row['avg_order'], 2) . "</td>";
                  echo "</tr>";
                }
              ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Payment Methods -->
    <div class="card">
      <div class="card-header">
        <h5 class="mb-0">Payment Methods Distribution</h5>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table">
            <thead>
              <tr>
                <th>Payment Method</th>
                <th>Number of Orders</th>
                <th>Total Amount</th>
                <th>Percentage</th>
              </tr>
            </thead>
            <tbody>
              <?php
                // Get payment method distribution with totals
                $payment_query = "SELECT 
                    payment_method,
                    COUNT(*) as count,
                    SUM(total_price) as total_amount
                    FROM orders 
                    GROUP BY payment_method
                    ORDER BY count DESC";
                $payment_result = mysqli_query($conn, $payment_query);

                // Calculate overall totals
                $total_query = "SELECT COUNT(*) as total, SUM(total_price) as total_amount FROM orders";
                $total_result = mysqli_query($conn, $total_query);
                $totals = mysqli_fetch_assoc($total_result);
                $total_orders = $totals['total'];
                $total_amount = $totals['total_amount'];

                while ($row = mysqli_fetch_assoc($payment_result)) {
                    $percentage = ($row['count'] / $total_orders) * 100;
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['payment_method']) . "</td>";
                    echo "<td>" . number_format($row['count']) . "</td>";
                    echo "<td>$" . number_format($row['total_amount'], 2) . "</td>";
                    echo "<td>" . number_format($percentage, 1) . "%</td>";
                    echo "</tr>";
                }
              ?>
              <tr class="font-weight-bold">
                <td>Total</td>
                <td><?php echo number_format($total_orders); ?></td>
                <td>$<?php echo number_format($total_amount, 2); ?></td>
                <td>100%</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <script src="bootstrap.js"></script>
</body>
</html>