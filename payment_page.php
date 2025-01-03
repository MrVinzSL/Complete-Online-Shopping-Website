<?php
session_start();

// Database connection
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'cshells';

$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) { 
    die("Connection failed: " . $conn->connect_error);
}

if(empty($_SESSION['user_id']))  
{
	header('location:login.php');
}

$message = "";

// Handle Cash on Delivery (COD) submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cod_submit'])) {
    $customer_name = $_POST['customer_name'];
    $address = $_POST['address'];
    $mobile_number = $_POST['mobile_number'];
    $total_amount = 0;
    $products = [];

    // Collect product details and calculate the total amount
    if (!empty($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            // Ensure product has an 'id' key
            if (isset($item['id'])) {
                $products[] = $item['name']; // Collect product names
                $total_amount += $item['price'] * $item['quantity'];

                // Reduce stock for each item ordered
                $product_id = $item['id'];  // Get the product ID
                $ordered_quantity = $item['quantity']; // Get the ordered quantity

                // Check if stock is available
                $result = $conn->query("SELECT stock FROM products WHERE product_id = $product_id");
                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $current_stock = $row['stock'];

                    // If enough stock is available, update the stock
                    if ($current_stock >= $ordered_quantity) {
                        $new_stock = $current_stock - $ordered_quantity;
                        $conn->query("UPDATE products SET stock = $new_stock WHERE product_id = $product_id");
                    } else {
                        // Handle out-of-stock situation (optional)
                        $message = "Not enough stock for {$item['name']}. Please reduce quantity or try again later. 😔";
                        break;
                    }
                }
            }
        }
    }

    $products_list = implode(', ', $products); // Convert products array to comma-separated string

    // Save order to the database
    $stmt = $conn->prepare("INSERT INTO orders (customer_name, address, mobile_number, products, total_amount) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssd", $customer_name, $address, $mobile_number, $products_list, $total_amount);

    if ($stmt->execute()) {
        $message = "Order placed successfully! 🎉";
        $_SESSION['cart'] = []; // Clear the cart after order is placed
    } else {
        $message = "Failed to place the order. Please try again! 😔";
    }

    $stmt->close();
}

$total = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Page</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/animsition.min.css" rel="stylesheet">
    <link href="css/animate.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
    <style>
    /* General Body Styling */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
            color: #333;
        }

        /* Page Title Styling */
        h2 {
            text-align: center;
            padding: 20px 0;
            color: #444;
            background-color: #f1f1f1;
            margin-bottom: 20px;
            font-size: 28px;
        }

        /* Section Titles */
        h3 {
            font-size: 24px;
            color: #555;
            margin-bottom: 10px;
            text-align: center;
        }

        /* Table Styling */
        table {
            width: 90%;
            margin: 0 auto 20px auto;
            border-collapse: collapse;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: #fff;
        }

        th, td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ddd;
        }

        th {
            background-color: #4CAF50;
            color: white;
            font-size: 18px;
        }

        td {
            font-size: 16px;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        /* Form Styling */
        form {
            width: 80%;
            max-width: 600px;
            margin: 0 auto 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        label {
            font-size: 16px;
            margin-bottom: 5px;
            display: block;
        }

        input[type="text"], textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }

        textarea {
            resize: none;
        }

        button {
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            background-color: #4CAF50;
            color: white;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #45a049;
        }

        /* Message Styling */
        p {
            text-align: center;
            font-size: 16px;
        }

        .message {
            font-size: 18px;
            color: green;
            text-align: center;
            margin-top: 10px;
        }

        .error {
            font-size: 18px;
            color: red;
            text-align: center;
            margin-top: 10px;
        }

        /* Shop More Button */
        form[action="cart.php"] button {
            margin: 20px auto;
            display: block;
            background-color: #007BFF;
        }

        form[action="cart.php"] button:hover {
            background-color: #0056b3;
        }
        .container{
            margin-top: 100px;
        }
    </style>

</head>
<body>
    <!-- Navbar and other HTML content -->
    <header>
        <a href="#" class="logo">
            <img src="img/logo.png" alt="">
        </a>
        <span style="color:#fff"><?php 
            if (isset($_SESSION['fname'])) {
                echo "Welcome, " . $_SESSION['fname'];
            } else {
                echo "Welcome, Guest<br>"; // Line break after Welcome, Guest
                echo "Sign In/Up Now";
            }
            ?>
            <a href="customer_login/main.php" title="Login" style="text-decoration: none;">
            <i class='bx bx-log-in-circle' style="color: white; cursor: pointer;"></i>
            </a>

        </span>     
        
        <!--Menu Icon-->
        <div class="menu-icon">
        <i class='bx bx-menu'></i>
        </div>
        <!--Links-->
        <ul class="navbar">
            <li><a href="#home">Home</a></li>
            <li><a href="#about">About</a></li>
            <li><a href="#products">Products</a></li>
            <li><a href="#customers">Customers</a></li>
            <?php if (!isset($_SESSION['fname'])){ ?>
                <li><a href="customer_login/main.php">Login</a></li><?php } ?>
        </ul>

        <!--Icons-->
        <div class="header-icon">
            <a href="your_orders.php"><i class='bx bxs-shopping-bags' title="Your Orders" style="cursor: pointer;"></i></a>
            <i class='bx bx-search-alt' id="search-icon" title="Search" style="cursor: pointer;"></i>
            <i class='bx bx-log-out' id="logout-icon" onclick="logout()" title="Logout" style="cursor: pointer;"></i>
        </div>
        
            <script>
            function logout() {
            window.location.href = 'customer_login/cus_logout.php';
            window.location.href = 'customer_login/main.php';
            }
            </script>

        <!--Search Box-->
    <div class="search-box">
        <input type="search" placeholder="Search Here For Anything">
    </div>

    <script src="script.js"></script>
    </header>

    <div class="container">
    <h2>Payment Page</h2>
    <h3>Cash on Delivery Details</h3>
    <form method="POST"  action="order_confirm.php">
        <label for="customer_name">Customer Name:</label><br>
        <input type="text" name="customer_name" id="customer_name" required><br><br>

        <label for="address">Address:</label><br>
        <textarea name="address" id="address" rows="4" required></textarea><br><br>

        <label for="mobile_number">Mobile Number:</label><br>
        <input type="text" name="mobile_number" id="mobile_number" required pattern="[0-9]{10}" title="Enter a valid 10-digit mobile number"><br><br>

        <div class="page-wrapper">
            <div class="container m-t-30">
                    <div class="widget clearfix">
                        <div class="widget-body">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="cart-totals margin-b-20">
                                        <div class="cart-totals-title">
                                            <h4>Cart Summary</h4>
                                        </div>
                                        <div class="cart-totals-fields">
                                            <table class="table">
                                                <tbody>
                                                    <tr>
                                                        <td>Cart Subtotal</td>
                                                        <?php 
                                                        
                                                        $sub_bill = $_POST['bill'];
                                                
                                                        ?>
                                                        <td> <?php echo "Rs." . $sub_bill . ".00 /="; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Delivery Charges</td>
                                                        <td>Rs.250.00 /=</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-color"><strong>Total</strong></td>
                                                        <?php
                                                        
                                                        $tot_bill = $sub_bill + 250;

                                                        ?>
                                                        <td class="text-color"><strong> <?php echo "Rs." . $tot_bill . ".00 /="; ?></strong></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="payment-option">
                                        <ul class=" list-unstyled">
                                            <li>
                                                <label class="custom-control custom-radio  m-b-20">
                                                    <input name="mod" id="radioStacked1" checked value="COD" type="radio" class="custom-control-input"> <span class="custom-control-indicator"></span> <span class="custom-control-description">Cash on Delivery</span>
                                                </label>
                                            </li>
                                        </ul>
                                        <input type="hidden" name="total_bill" value="<?php echo $tot_bill ?>">
                                        <p class="text-xs-center"> <input type="submit" onclick="return confirm('Do you want to confirm the order?');" name="submit" class="btn btn-success btn-block" value="Order Now"> </p>
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <?php if ($message): ?>
        <p><?= $message ?></p>
    <?php endif; ?>

    <!-- Shop More Button -->
<form action="cart.php" method="get">
    <button type="submit" style="padding: 10px 20px; background-color: #4CAF50; color: white; border: none; border-radius: 5px; cursor: pointer;">
        Shop More 🛒
    </button>
</form>

</div>

<!--Footer-->
<section class="footer">
        <div class="footer-box">
            <h3>C Shells Store</h3>
            <p>Thank you for choosing C Shell – Where Style Meets Convenience!</p>
        </div>
        <div class="social">
            <a href="https://www.facebook.com/profile.php?id=61550031967493"><i class='bx bxl-facebook' ></i></a>
            <a href="#"><i class='bx bxl-twitter' ></i></a>
            <a href="#"><i class='bx bxl-instagram' ></i></a>
            <a href="#"><i class='bx bxl-youtube' ></i></a>
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
    <div class="contact">
        <h3>Contact</h3>
        <span><i class='bx bx-map'></i>Ambalangoda,Southern Province,Sri Lanka</span>
        <span><i class='bx bx-phone-call'></i>+94 77 123 4567</span>
        <span><i class='bx bx-envelope'></i>cshells@web.com</span>
    
    </section>

</body>
</html>
