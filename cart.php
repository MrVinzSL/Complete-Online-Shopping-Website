<?php
session_start(); // Start session for cart functionality

// Database connection
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'cshells';

$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}



// Remove item from cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_from_cart'])) {
    $product_id = $_POST['id'];
    unset($_SESSION['cart'][$product_id]); // Remove item from cart
}

// Checkout logic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['checkout'])) {
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $id => $item) {
            $quantity = $item['quantity'];
            // Update stock after checkout
            $query = "UPDATE products SET stock = stock - $quantity WHERE product_id = $id";
            $conn->query($query);
        }
        unset($_SESSION['cart']); // Clear the cart after checkout
        $message = "Checkout successful! Stock updated.";
    } else {
        $message = "Your cart is empty!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>C Shells Website - Shop</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
    <link rel="icon" href="favicon.ico" type="image/x-icon">

    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
        }

        th {
            background-color: #f4f4f4;
        }

        .total-section {
            margin-top: 20px;
            text-align: right;
        }

        .checkout-btn {
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            border: none;
            cursor: pointer;
        }

        .checkout-btn:hover {
            background-color: #218838;
        }

        select {
            padding: 8px;
            border-radius: 5px;
            background-color:rgb(248, 255, 218);
        }
        .prodImg{
            width: 100%;
            height: 250px;
            object-fit: contain;
            object-position: center;
            padding: 20px;
            border-radius: 0.5rem;
            background-color: #f1f1f1;
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
            <h2>About C Shells</h2>
            <p>C Shells offers a premium collection of stylish fashion items, including clothing, accessories, and footwear, designed for those who appreciate quality and elegance.</p>
            <p><b>We pride ourselves on delivering the latest trends and timeless designs that cater to every occasion. Our focus is on providing exceptional customer service, fast delivery, and a seamless shopping experience, ensuring you always feel confident and stylish. With C Shells, your perfect look is just a click away.<b></p><br>
            <!--<p>Thank you for choosing C Shell – where style meets convenience! 🌟💖</p>-->
            <a href="https://www.facebook.com/profile.php?id=61550031967493" class="btn">Learn More</a>
        </div>
        </section>

    <!-- Products Section -->
    <section class="products" id="products">
        <div class="heading">
            <h2>Our Popular Products</h2>
        </div>
        <div class="product-container">
    <?php
    // Fetch all products from the database
    $query = "SELECT * FROM products";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        // Loop through each product
        while ($product = $result->fetch_assoc()) {
    ?>
        <div class="box">
            <img src="uploads/<?php echo $product['image_url']; ?>" alt="<?php echo htmlspecialchars($product['product_name']); ?>">
            <h3><?php echo htmlspecialchars($product['product_name']); ?></h3>
            <p><?php echo htmlspecialchars($product['description']); ?></p>
            <div class="content">
                <span>Rs.<?php echo number_format($product['price'], 2); ?>/=</span>
                <form method="POST" action="cart_handler.php">
                        <input type="hidden" name="pid" value="<?php echo $product['product_id']; ?>">  <!-- Adjusted for your column name -->
                        <input type="hidden" name="pname" value="<?php echo $product['product_name']; ?>">
                        <input type="hidden" name="pdes" value="<?php echo $product['description']; ?>">
                        <input type="hidden" name="pprice" value="<?php echo $product['price']; ?>">
                        <input type="hidden" name="pqty" value="<?php echo $product['stock']; ?>">
                        <input type="hidden" name="purl" value="<?php echo $product['image_url']; ?>">
                        <button type="submit" name="add_to_cart">Add to Cart</button>
                </form>
            </div>
        </div>
    <?php
        }
    } else {
        echo "<p>No products found.</p>";
    }
    ?>
</div>

    </section>

    <!-- Cart Section -->
    <section class="cart" id="cart">
    <div class="heading">
        <h2>Your Cart</h2>
    </div>
    <div class="cart-container">
        <?php 
         if (!isset($_SESSION['fname'])) {
            echo "Please Login To Add Items To Cart !! ";
        } 
        else {
            $total_bill = 0;
            $query = "SELECT * FROM cart WHERE user_id=$_SESSION[user_id]";
            $result_set = mysqli_query($conn, $query);
            ?>
            <table>
                <thead>
                    <tr>
                        <div class="prodImg"><th>Item Image</th></div>
                        <th>Item Name</th>
                        <th>Item Description</th>
                        <th>Item Quantity</th>
                        <th>Total Price</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (mysqli_num_rows($result_set) > 0) {
                        while ($row = mysqli_fetch_assoc($result_set)) {
                            $item_price = $row['product_price']; // Base price of the item
                            $total_bill += $item_price; // Add base price to the total bill initially
                            ?>
                            <tr>
                                <td><img src="uploads/<?php echo $row['product_url']; ?>" alt="Item Image" width="50"></td>
                                <td><?php echo $row['product_name']; ?></td>
                                <td><?php echo $row['product_description']; ?></td>
                                <td>
                                    <!-- Quantity dropdown -->

                                    <select class="quantity-dropdown" data-price="<?php echo $item_price; ?>" data-row-id="<?php echo $row['product_id']; ?>">
                                        <option value="1" selected>1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                    </select>
                                </td>
                                <td class="item-total" id="item-total-<?php echo $row['product_id']; ?>">Rs.<?php echo number_format($item_price, 2); ?>/=</td>
                                <td>
                                    <form method="post" action="remove_from_cart.php">
                                        <input type="hidden" name="product_id" value="<?php echo $row['product_id']; ?>">
                                        <button type="submit" class="checkout-btn" name="remove" style="background-color: red;">Remove</button>
                                    </form>
                                </td>
                            </tr>
                            <?php
                        }
                    } else {
                        ?>
                        <tr>
                            <td colspan="6">Your cart is empty!</td>
                        </tr>
                        <?php
                    }
                }
                ?>
            </tbody>
        </table>

        <div class="total-section">
            <h3>Total Bill: <span id="total-bill">Rs.<?php 
                if(!isset($_SESSION['fname'])){
                    echo "0.00";
                }
                else{
                    echo number_format($total_bill, 2);
                 ?>/=</span></h3>
            <form method="post" action="payment_page.php">
                <input type="hidden" name="bill" id="total-bill-input" value="<?php echo $total_bill ?>">
                <button type="submit" class="checkout-btn" style="margin:10px 0;">Proceed to Checkout</button>
            </form>
            <button type="submit" class="checkout-btn btn-warning" style="margin:5px 0;" onclick="window.location.href='your_orders.php'">View Your Orders</button>
                <?php } ?>
        </div>
    </div>
    </section>

    <!-- Other Sections (Footer, etc.) -->
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
                <i class='bx bx-star'></i>
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
                <i class='bx bx-star'></i>
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
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Get all quantity dropdowns
            const quantityDropdowns = document.querySelectorAll('.quantity-dropdown');
            const totalBillElement = document.getElementById('total-bill');
            const totalBillInput = document.getElementById('total-bill-input');
            let totalBill = <?php echo $total_bill; ?>;

            // Update total price when quantity changes
            quantityDropdowns.forEach(dropdown => {
                dropdown.addEventListener('change', function () {
                    const selectedQuantity = parseInt(this.value);
                    const itemPrice = parseFloat(this.dataset.price);
                    const rowId = this.dataset.rowId;

                    // Calculate new total for the item
                    const newItemTotal = selectedQuantity * itemPrice;

                    // Update the item's total price in the table
                    const itemTotalElement = document.getElementById('item-total-' + rowId);
                    itemTotalElement.textContent = 'Rs.' + newItemTotal.toFixed(2) + '/=';

                    // Recalculate the total bill
                    recalculateTotalBill();
                });
            });

            function recalculateTotalBill() {
                let newTotalBill = 0;

                // Loop through all dropdowns to calculate the total bill
                quantityDropdowns.forEach(dropdown => {
                    const selectedQuantity = parseInt(dropdown.value);
                    const itemPrice = parseFloat(dropdown.dataset.price);
                    newTotalBill += selectedQuantity * itemPrice;
                });

                // Update the total bill on the page
                totalBillElement.textContent = 'Rs.' + newTotalBill.toFixed(2) + '/=';
                totalBillInput.value = newTotalBill; // Update the hidden input value
            }
        });
    </script>
</body>
</html>