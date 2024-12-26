<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the username and password from the form
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Hash the entered password (same hashing method used during registration)
    $hashedPassword = sha1($password);

    // Prepare the SQL query to fetch the admin with the given username and password
    $query = "SELECT * FROM admin_log WHERE username = ? AND password = ?";
    
    if ($stmt = $conn->prepare($query)) {
        // Bind parameters to the query to prevent SQL injection
        $stmt->bind_param("ss", $username, $hashedPassword);
        
        // Execute the query
        $stmt->execute();
        
        // Store the result
        $stmt->store_result();
        
        // If a matching admin is found, start a session and log in
        if ($stmt->num_rows > 0) {
            session_start();  // Start the session
            $_SESSION['username'] = $username;  // Store username in session
            header("Location: dashboard.php");  // Redirect to admin dashboard
            exit();
        } else {
            // If no match found, display an error message
            echo "<script>alert('Invalid username or password!');</script>";
        }
    } else {
        echo "<script>alert('Error preparing the query.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Login</title>
  <link rel="stylesheet" href="bootstrap.css">
  <style>
    body {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      background-color: #f8f9fa;
    }
    .login-form {
      width: 100%;
      max-width: 400px;
      background: #fff;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    .login-form h3 {
      text-align: center;
      margin-bottom: 20px;
    }
  </style>
</head>
<body>
  <div class="login-form">
    <h3>Admin Login</h3>
    <form action="" method="post">
      <div class="form-group">
        <label for="username">Username</label>
        <input type="text" class="form-control" id="username" name="username" placeholder="Enter username" required>
      </div>
      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" class="form-control" id="password" name="password" placeholder="Enter password" required>
      </div>
      <button type="submit" class="btn btn-primary btn-block" name="submit">Login</button>
    </form>
  </div>
  <script src="bootstrap.js"></script>
</body>
</html>
