<?php
// Database connection
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'cshells';
$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

session_start();

// Add to cart
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['id'];
    $query = "SELECT * FROM products WHERE product_id = '$product_id'";
    $result = $conn->query($query);
    $product = $result->fetch_assoc();

    if ($product) {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        $_SESSION['cart'][$product_id] = [
            'name' => $product['product_name'],
            'price' => $product['price'],
            'image_url' => $product['image_url'],  // Updated field name
            'quantity' => ($_SESSION['cart'][$product_id]['quantity'] ?? 0) + 1,
            'stock' => $product['stock']  // Added stock field
        ];
    }
}

// Update quantity (increment or decrement)
if (isset($_POST['update_cart'])) {
    $product_id = $_POST['id'];
    $action = $_POST['action'];

    if (isset($_SESSION['cart'][$product_id])) {
        if ($action === 'increment' && $_SESSION['cart'][$product_id]['quantity'] < $_SESSION['cart'][$product_id]['stock']) {
            $_SESSION['cart'][$product_id]['quantity']++;
        } elseif ($action === 'decrement') {
            $_SESSION['cart'][$product_id]['quantity']--;

            if ($_SESSION['cart'][$product_id]['quantity'] <= 0) {
                unset($_SESSION['cart'][$product_id]);
            }
        }
    }
}

// Checkout
if (isset($_POST['checkout'])) {
    foreach ($_SESSION['cart'] as $id => $item) {
        $quantity = $item['quantity'];
        $query = "UPDATE products SET stock = stock - '$quantity' WHERE product_id = '$id'";
        $conn->query($query);
    }
    $_SESSION['cart'] = [];
    $checkout_message = "Checkout successful! 🎉";
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>C Shells Website</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet"
    href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
</head>
<body>
    <!--Navbar-->
    <header>
        <a href="shop_site.php" class="logo">
            <img src="img/logo.png" alt="C Shells">
        </a>
        <!--Menu Icon-->
        <div class="menu-icon">
        <i class='bx bx-menu'></i>
        </div>
        <!--Links-->
        <ul class="navbar">
            <li><a href="#home">Home</a></li>
            <li><a href="#about">About</a></li>
            <li><a href="#products">Products</a></li>
            <li><a href="#contact">Contact</a></li>
        </ul>

        <!--Icons-->
        <div class="header-icon">
            <a href="new_cart.php"><i class='bx bx-cart-download'></i></a>
            <i class='bx bx-search-alt' id="search-icon"></i>
        </div>
        <!--Search Box-->
    <div class="search-box" id="search-box">
        <input type="search" placeholder="Search Here For Anything">
    </div>

    <script src="script.js"></script>
    </header>

    <!--Home-->
    <section class="home" id="home">
        <div class="home-text">
            <h1>All your needs<br>In one place</h1>
            <p>"At C Shells, we offer a wide range of premium and stylish products to meet your every desire. Explore our collection and find your next favorite item today!"</p><br>
            <a href="#products" class="btn">Shop Now</a>
        </div>
        <div class="home-img">
            <img src="img/ShoppingCart.png" alt="">
        </div>
    </section>
    <!--About-->
    <section class="about" id="about">
        <div class="about-image">
            <img src="img/about.jpg" alt="">
        </div>
        <div class="about-text">
            <h2>Our History</h2>
            <p>C Shells began with a passion for creating a shopping experience that’s convenient, trendy, and trustworthy.</p>
            <p>Over the years, we have grown to become a destination for premium products, earning the trust and love of our loyal customers.</p>
            <p>Join us in this exciting journey and discover the magic of C Shells!</p>
            <a href="#" class="btn">Learn More</a>
        </div>
        </section>
    <title>Shop</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .container {
            display: flex;
            padding: 20px;
            gap: 20px;
        }
        .products, .cart {
            flex: 1;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
        }
        .product, .cart-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }
        .product img, .cart-item img {
            width: 80px;
            height: 80px;
            border-radius: 8px;
        }
        button {
            padding: 5px 10px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        .cart-total {
            font-weight: bold;
            margin-top: 20px;
        }
        .quantity-buttons button {
            margin: 0 5px;
            padding: 5px;
            font-size: 16px;
        }
    </style>
</head>
    <h1 style="text-align: center;">Shop</h1>
    <div class="container">
        <!-- Products Section -->
        <div class="products" id="products">
            <h2>Products</h2>
            <?php
            $query = "SELECT * FROM products";
            $result = $conn->query($query);
            while ($product = $result->fetch_assoc()) {
                // Update the image path to use 'image_url' field
                $image_url = 'uploads/' . $product['image_url'];  // Updated field name

                echo "
                <div class='product'>
                    <div>
                        <img src='$image_url' alt='Product Image'>
                    </div>
                    <div>
                        <h3>{$product['product_name']}</h3>
                        <p>{$product['description']}</p>
                        <p>Price: {$product['price']} | Stock: {$product['stock']} </p>
                    </div>
                    <form method='POST'>
                        <input type='hidden' name='id' value='{$product['product_id']}'>
                        <button type='submit' name='add_to_cart'>Add to Cart</button>
                    </form>
                </div>";
            }
            ?>
        </div>

        
    <!--Customers-->
<section class="customers" id="customers">
        <div class="heading">
        <h2>Our Happy Customers</h2>
    </div>
    <!--Customer Container-->
    <div class="customer-container">
        <div class="box">
            <div class="stars">
                <i class='bx bxs-star'></i>
                <i class='bx bxs-star'></i>
                <i class='bx bxs-star'></i>
                <i class='bx bxs-star'></i>
                <i class='bx bxs-star-half' ></i>
            </div>
            <p>C Shells has the best collection! The products are high-quality, and delivery was super fast. Totally love it! 💖✨</p>
            <h2>Minuri De Silva</h2>
            <img src="img/customer (1).jpg" alt="">
        </div>
        <div class="box">
            <div class="stars">
                <i class='bx bxs-star'></i>
                <i class='bx bxs-star'></i>
                <i class='bx bxs-star'></i>
                <i class='bx bxs-star'></i>
                <i class='bx bx-star' ></i>
            </div>
            <p>Amazing experience! Great customer service and fantastic designs. Highly recommend C Shells to everyone. 🌟👌</p>
            <h2>Ashen Senarathne</h2>
            <img src="img/customer (2).jpg" alt="">
        </div><div class="box">
            <div class="stars">
                <i class='bx bxs-star'></i>
                <i class='bx bxs-star'></i>
                <i class='bx bxs-star'></i>
                <i class='bx bxs-star-half' ></i>
                <i class='bx bx-star' ></i>
            </div>
            <p>I’m obsessed with their unique styles! Shopping here is always a pleasure. Thank you, C Shells! 🛍️💕</p>
            <h2>Anushki Premachnadra</h2>
            <img src="img/customer (3).jpg" alt="">
        </div>

    </section>

    <!--Footer-->
    <section class="footer">
        <div class="footer-box">
            <h3>C Shells Store</h3>
            <p>Your trusted partner for all your fancy needs! Contact us for inquiries or support, and let us help make your shopping journey delightful.</p>
        </div>
        <div class="social">
            <a href="https://www.facebook.com/profile.php?id=61550031967493"><i class='bx bxl-facebook' ></i></a>
            <a href="https://api.whatsapp.com/send/?phone=%2B94763475278&text&type=phone_number&app_absent=0"><i class='bx bxl-whatsapp'></i></a>
            <a href="https://www.instagram.com/c_shells__/"><i class='bx bxl-instagram' ></i></a>
            <a href="https://www.youtube.com/@vinzbeats.13"><i class='bx bxl-youtube' ></i></a>
        </div>
    
    
    <div class="footer-box">
        <h3>Suppot</h3>
        <li><a href="#">Products</a></li>
        <li><a href="#">Help and Support</a></li>
        <li><a href="#">Return Policy</a></li>
        <li><a href="#">Terms of use</a></li>
    </div>
    <div class="footer-box">
        <h3>View Guides</h3>
        <li><a href="#">Featurs</a></li>
        <li><a href="#">Careers</a></li>
        <li><a href="#">Blog Post</a></li>
        <li><a href="#">Developers</a></li>
    </div>
    <div id="contact" class="contact">
        <h3>Contact</h3>
        <span><i class='bx bx-map'></i>Ambalangoda,Southern Province,Sri Lanka</span>
        <span><i class='bx bx-phone-call'></i>+94 77 123 4567</span>
        <span><i class='bx bx-envelope'></i>cshells@web.com</span>
    
    </section>
</body>
</html>
