<?php
include 'config.php'; // Include your database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get user inputs
    $username = $_POST['admin-username'];
    $email = $_POST['admin-email'];
    $password = $_POST['admin-password'];
    
    // Check if username or email already exists in the database using prepared statements
    $checkQuery = "SELECT id FROM admin_log WHERE username = ? OR email = ?";
    if ($stmt = $conn->prepare($checkQuery)) {
        $stmt->bind_param("ss", $username, $email); // Bind the parameters to the query
        $stmt->execute(); // Execute the query
        $stmt->store_result(); // Store the result for later checking
        
        // If a record exists, show an error message
        if ($stmt->num_rows > 0) {
            echo "<script>alert('Admin with this username or email already exists!');</script>";
        } else {
            // Hash the password securely
            $hashedPassword = sha1($password); // Using SHA1 for simplicity (consider using password_hash for better security)
            
            // Insert the new admin using prepared statements to prevent SQL injection
            $insertQuery = "INSERT INTO admin_log (username, email, password) VALUES (?, ?, ?)";
            if ($stmt = $conn->prepare($insertQuery)) {
                $stmt->bind_param("sss", $username, $email, $hashedPassword); // Bind the parameters to the query
                if ($stmt->execute()) {
                    echo "<script>alert('New admin added successfully.');</script>";
                } else {
                    echo "<script>alert('Error adding admin. Please try again later.');</script>";
                }
            } else {
                echo "<script>alert('Error preparing insert query.');</script>";
            }
        }
        $stmt->close(); // Close the statement
    } else {
        echo "<script>alert('Error preparing check query.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Settings</title>
  <link rel="stylesheet" href="bootstrap.css">
</head>
<body>
  <?php include 'dashboard.php'; ?>
  <div class="content">
    <h2>Settings</h2>
    <p>This is where you can update system configurations.</p>
    
    <!-- Add New Admin Form -->
    <div class="mt-4">
      <h4>Add a New Admin</h4>
      <form action="" method="POST">
        <div class="form-group">
          <label for="admin-username">Username</label>
          <input type="text" class="form-control" id="admin-username" name="admin-username" placeholder="Enter new admin username" required>
        </div>
        <div class="form-group">
          <label for="admin-email">Email</label>
          <input type="email" class="form-control" id="admin-email" name="admin-email" placeholder="Enter admin email" required>
        </div>
        <div class="form-group">
          <label for="admin-password">Password</label>
          <input type="password" class="form-control" id="admin-password" name="admin-password" placeholder="Enter admin password" required>
        </div>
        <button type="submit" class="btn btn-primary mt-3">Add Admin</button>
      </form>
    </div>
    
  </div>
  <script src="bootstrap.js"></script>
</body>
</html>
