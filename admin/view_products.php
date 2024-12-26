<?php
// Include the database configuration file
include 'config.php';

// Check if a search query is provided
$search = '';
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    // Modify the SQL query to include a search condition
    $sql = "SELECT * FROM products WHERE NAME LIKE '%$search%'";
} else {
    // Default query to fetch all products
    $sql = "SELECT * FROM products";
}

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>View Products</title>
  <link rel="stylesheet" href="bootstrap.css">
</head>
<body>
  <?php include 'dashboard.php'; ?>
  <div class="content">
    <h2>View Products</h2>
    <p>Below is the list of products available in the database.</p>
    
    <!-- Search Form -->
    <form method="GET" class="form-inline mb-4">
      <input type="text" name="search" class="form-control mr-2" placeholder="Search by product name" value="<?php echo htmlspecialchars($search); ?>">
      <button type="submit" class="btn btn-primary">Search</button>
      <a href="view_products.php" class="btn btn-secondary ml-2">Reset</a>
    </form>

    <!-- Products Table -->
    <div class="mt-4">
      <table class="table table-bordered table-striped">
        <thead class="thead-dark">
          <tr>
            <th>#</th>
            <th>Product Name</th>
            <th>Description</th>
            <th>Price</th>
            <th>Category</th>
            <th>Quantity</th>
            <th>Image</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php
          // Check if there are any products
          if ($result->num_rows > 0) {
            // Output data of each product
            while ($row = $result->fetch_assoc()) {
              echo "<tr>";
              echo "<td>" . $row['id'] . "</td>";
              echo "<td>" . htmlspecialchars($row['NAME']) . "</td>";
              echo "<td>" . htmlspecialchars($row['description']) . "</td>";
              echo "<td>" . number_format($row['price'], 2) . "</td>";
              echo "<td>" . htmlspecialchars($row['category']) . "</td>";
              echo "<td>" . htmlspecialchars($row['quantity']) . "</td>";
              // Display image if available
              $image = $row['main_image'] ? $row['main_image'] : 'default.jpg';
              echo "<td><img src='imgs/$image' alt='" . htmlspecialchars($row['NAME']) . "' width='50' height='50'></td>";
              echo "<td>
                      <a href='edit_product.php?id=" . $row['id'] . "' class='btn btn-primary'>Edit</a>
                      <a href='delete_product.php?id=" . $row['id'] . "' class='btn btn-danger'>Delete</a>
                    </td>";
              echo "</tr>";
            }
          } else {
            echo "<tr><td colspan='8' class='text-center'>No products found.</td></tr>";
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>
  
  <script src="bootstrap.js"></script>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
