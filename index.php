<?php 

include 'config.php';
include 'header.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MT Glory Co - Home</title>
    <link rel="stylesheet" href="bootstrap.css">
    <style>
        /* Custom Styles */
        .hero-section {
            position: relative;
            height: 400px;
            background: url('banner.jpg') no-repeat center center/cover;
            color: white;
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        .hero-section h1 {
            font-size: 3rem;
        }
        .hero-text p {
            font-size: 1.5rem;
            margin-bottom: 20px;
        }
        .hero-section .btn {
            font-size: 1.2rem;
        }

        .categories, .featured-products, .testimonials, .promo-section, .newsletter, .social-media, .trending-products {
            margin: 40px 0;
        }
        .categories .category, .featured-products .product-card, .testimonial-card {
            display: inline-block;
            text-align: center;
            width: 30%;
            margin: 10px;
        }
        .product-card img, .category img {
            width: 100%;
            height: auto;
            border-radius: 10px;
        }
        .testimonial-card {
            font-style: italic;
            padding: 15px;
            background-color: #f4f4f4;
            border-radius: 10px;
        }
        .social-media a img {
            width: 30px;
            margin: 0 10px;
        }
        .search-bar {
            display: flex;
            justify-content: center;
            margin: 20px;
        }
        .search-bar input {
            width: 300px;
            padding: 10px;
            margin-right: 10px;
        }
    </style>
</head>
<body>

<!-- Hero Section -->
<div class="hero-section">
    <h1>Welcome to MT Glory Co!</h1>
    <p>Exclusive Offers Just For You</p>
    <a href="products.php" class="btn btn-primary">Shop Now</a>
</div>

<!-- Product Categories -->
<div class="categories text-center">
    <h2>Shop by Categories</h2>
    <div class="category">
        <a href="http://localhost/glory/products.php?search=phone">
            <img src="categories/phone.jpg" alt="Phones">
            <p>Phones</p>
        </a>
    </div>
    <div class="category">
        <a href="http://localhost/glory/products.php?search=computer">
            <img src="categories/computer.jpg" alt="Laptops">
            <p>Laptops</p>
        </a>
    </div>
    <div class="category">
        <a href="http://localhost/glory/products.php?search=accessories">
            <img src="categories/accessories.jpg" alt="Accessories">
            <p>Accessories</p>
        </a>
    </div>
</div>

<!-- Featured Products -->
<div class="featured-products text-center">
    <h2>Featured Products</h2>
    <div class="product-card">
        <img src="featured/iphone16.jpg" alt="Product Name">
        <h3>iPHONE 16 Pro Max</h3>
        <p>$1199</p>
        <a href="product_details.php?id=1" class="btn btn-secondary">View Details</a>
    </div>
    <div class="product-card">
        <img src="featured/redmi13.jpg" alt="Product Name">
        <h3>Redmi Note 13</h3>
        <p>$322</p>
        <a href="product_details.php?id=2" class="btn btn-secondary">View Details</a>
    </div>
    <div class="product-card">
        <img src="featured/hp.jpg" alt="Product Name">
        <h3>HP ELITE BOOK i9</h3>
        <p>$700</p>
        <a href="product_details.php?id=3" class="btn btn-secondary">View Details</a>
    </div>
</div>

<!-- Testimonials -->
<div class="testimonials text-center">
    <h2>What Our Customers Say</h2>
    <div class="testimonial-card">
        <p>"Great shopping experience, fast delivery, and excellent customer service!"</p>
        <p><strong>- John Doe</strong></p>
    </div>
    <div class="testimonial-card">
        <p>"The products are exactly as described, very happy with my purchase!"</p>
        <p><strong>- Jane Smith</strong></p>
    </div>
</div>

<!-- Newsletter Signup -->
<div class="newsletter text-center">
    <h2>Stay Updated!</h2>
    <p>Sign up for our newsletter and get the latest deals directly in your inbox.</p>
    <form action="subscribe.php" method="POST">
        <input type="email" name="email" placeholder="Enter your email" required>
        <button type="submit" class="btn btn-success">Subscribe</button>
    </form>
</div>

<!-- Social Media Links -->
<div class="social-media text-center">
    <h2>Follow Us</h2>
    <a href="https://www.instagram.com/mtgloryco/" target="_blank">
        <img src="social/insta.png" alt="Instagram">
    </a>
    <a href="https://web.facebook.com/profile.php?id=61552664511309" target="_blank">
        <img src="social/fb.png" alt="Facebook">
    </a>
</div>
<script src="bootstrap.js"></script>
</body>
</html>

<?php  include 'footer.php'; ?>
</html>
