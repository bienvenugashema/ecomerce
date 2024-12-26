<?php
// Start the session
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>About Us</title>
  <link rel="stylesheet" href="bootstrap.css">
  <style>
    .hero-section {
      background: url('about.jpg') no-repeat center center/cover;
      color: white;
      text-align: center;
      padding: 100px 20px;
    }

    .hero-section h1 {
      font-size: 3rem;
      font-weight: bold;
    }

    .hero-section p {
      font-size: 1.2rem;
    }

    .section {
      padding: 40px 20px;
    }

    .section h2 {
      font-size: 2rem;
      font-weight: bold;
      margin-bottom: 20px;
    }

    .section p {
      font-size: 1rem;
      line-height: 1.6;
    }

    .values {
      display: flex;
      justify-content: space-around;
      flex-wrap: wrap;
    }

    .value-item {
      flex: 1;
      margin: 20px;
      max-width: 300px;
      text-align: center;
    }

    .value-item img {
      width: 300px;
      height: 250px;
      margin-bottom: 15px;
      border-radius: 8px;
    }

    .value-item h4 {
      font-size: 1.5rem;
      margin-bottom: 10px;
    }

    .value-item p {
      font-size: 0.9rem;
    }
  </style>
</head>
<body>
  <?php include 'header.php'; ?>

  <!-- Hero Section -->
  <div class="hero-section">
    <h1>About Us</h1>
    <p>Discover who we are, what we stand for, and what drives us to serve you better every day.</p>
  </div>

  <!-- Company Overview Section -->
  <div class="container section">
    <h2>Who We Are</h2>
    <p>
      Welcome to TechStore! We are your go-to destination for high-quality electronics, from the latest smartphones to powerful computers and gadgets.
      Founded with a passion for technology, our mission is to make advanced technology accessible to everyone. At TechStore, we believe in delivering
      top-notch products, exceptional customer service, and an outstanding shopping experience.
    </p>
  </div>

  <!-- Mission and Vision Section -->
  <div class="container section">
    <h2>Our Mission and Vision</h2>
    <p>
      Our mission is to empower people through technology by offering a wide range of electronics that cater to both personal and professional needs. 
      We aim to be the most trusted name in the industry by prioritizing customer satisfaction, innovation, and affordability.
    </p>
    <p>
      Our vision is to build a future where technology enhances every aspect of life, making it smarter, simpler, and more connected.
    </p>
  </div>

  <!-- Our Values Section -->
  <div class="container section">
    <h2>Our Core Values</h2>
    <div class="values">
      <div class="value-item">
        <img src="values/quality.jpg" alt="Quality">
        <h4>Quality</h4>
        <p>We deliver only the best products, thoroughly tested for performance and reliability.</p>
      </div>
      <div class="value-item">
        <img src="values/innovation.jpg" alt="Innovation">
        <h4>Innovation</h4>
        <p>We embrace change and continuously seek out the latest and greatest in technology.</p>
      </div>
      <div class="value-item">
        <img src="values/customer.jpg" alt="Customer First">
        <h4>Customer First</h4>
        <p>Your satisfaction is our priority, and we are here to support you every step of the way.</p>
      </div>
    </div>
  </div>

  <!-- Footer -->
  <?php include 'footer.php'; ?>

  <script src="bootstrap.js"></script>
</body>
</html>
