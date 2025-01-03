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

session_start(); // Start session for cart and wishlist

// Add item to cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $product_id = $_POST['id'];
    $product_name = $_POST['name'];
    $product_price = $_POST['price'];
    $quantity = 1;

    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = [
            'name' => $product_name,
            'price' => $product_price,
            'quantity' => $quantity,
        ];
    }
}

// Update cart quantities
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_cart'])) {
    foreach ($_POST['quantity'] as $product_id => $quantity) {
        if ($quantity <= 0) {
            unset($_SESSION['cart'][$product_id]);
        } else {
            $_SESSION['cart'][$product_id]['quantity'] = $quantity;
        }
    }
}

// Add to wishlist
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_wishlist'])) {
    $product_id = $_POST['id'];
    $product_name = $_POST['name'];

    if (!isset($_SESSION['wishlist'][$product_id])) {
        $_SESSION['wishlist'][$product_id] = $product_name;
    }
}

// Clear cart after checkout
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['checkout'])) {
    if (!empty($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $id => $item) {
            $quantity = $item['quantity'];
            $query = "UPDATE products SET stock = stock - $quantity WHERE product_id = $id";
            $conn->query($query);
        }
        unset($_SESSION['cart']);
        $message = "Checkout successful!";
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
    <link rel="stylesheet" href="cart.css">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
</head>

<body>

<!-- Navbar -->
<header>
    <a href="#" class="logo"><img src="img/logo.png" alt=""></a>
    <ul class="navbar">
        <li><a href="#home">Home</a></li>
        <li><a href="#about">About</a></li>
        <li><a href="#products">Products</a></li>
        <li><a href="#cart">Cart</a></li>
    </ul>
    <div class="header-icon">
        <a href="#cart"><i class='bx bx-cart'></i></a>
        <a href="#wishlist"><i class='bx bx-heart'></i></a>
    </div>
</header>



<!-- Cart and Wishlist Section -->
<section id="cart-wishlist">
    <h2>Your Cart and Wishlist</h2>

    <!-- Cart Section -->
    <div class="cart">
        <h3>Your Cart</h3>
        <?php if (!empty($_SESSION['cart']) || !empty($_SESSION['wishlist'])): ?>
            <form method="POST">
                <table>
                    <tr>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Subtotal</th>
                        <th>Action</th>
                    </tr>
                    <?php 
                    $total = 0;
                    foreach ($_SESSION['cart'] as $id => $item):
                        $subtotal = $item['price'] * $item['quantity'];
                        $total += $subtotal;
                    ?>
                    <tr>
                        <td>
                            <img src="<?= 'uploads/' . $item['image_url'] ?>" alt="Cart Item Image" width="50">
                            <?= $item['name'] ?>
                        </td>
                        <td>
                            <input type="number" name="quantity[<?php echo $id; ?>]" value="<?php echo $item['quantity']; ?>" min="1">
                        </td>
                        <td>Rs.<?php echo number_format($item['price'], 2); ?></td>
                        <td>Rs.<?php echo number_format($subtotal, 2); ?></td>
                        <td>
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="id" value="<?= $id ?>">
                                <input type="hidden" name="action" value="remove_from_cart">
                                <button type="submit" name="update_cart">Remove</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td colspan="3">Total</td>
                        <td>Rs.<?php echo number_format($total, 2); ?></td>
                        <td></td>
                    </tr>
                </table>
                <button type="submit" name="update_cart">Update Cart</button>
                <button type="submit" name="checkout">
                 <a href="payment_page.php">Proceed to Payment</a>
                </button>

            </form>
        <?php else: ?>
            <p>Your cart is empty.</p>
        <?php endif; ?>
    </div>

    <!-- Wishlist Section -->
    <div class="wishlist">
        <h3>Your Wishlist</h3>
        <?php if (!empty($_SESSION['wishlist'])): ?>
            <ul>
                <?php foreach ($_SESSION['wishlist'] as $id => $name): ?>
                    <li>
                        <?= $name ?>
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="id" value="<?= $id ?>">
                            <input type="hidden" name="action" value="add_to_cart">
                            <button type="submit" name="update_cart">Add to Cart</button>
                        </form>
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="id" value="<?= $id ?>">
                            <input type="hidden" name="action" value="remove_from_wishlist">
                            <button type="submit" name="update_cart">Remove</button>
                        </form>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>Your wishlist is empty.</p>
        <?php endif; ?>
    </div>
</section>

</body>

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

</html>