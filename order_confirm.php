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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $customer_name = mysqli_real_escape_string($conn, $_POST['customer_name']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $mobile_number = mysqli_real_escape_string($conn, $_POST['mobile_number']);
    $total_amount = $_POST['total_bill'];
    $user_id = $_SESSION['user_id']; 
    $order_date = date('Y-m-d H:i:s'); 

    if (empty($customer_name) || empty($address) || empty($mobile_number) || empty($user_id)) {
        echo "<script>alert('All fields are required.'); window.history.back();</script>";
        exit;
    }

    if (!preg_match('/^[0-9]{10}$/', $mobile_number)) {
        echo "<script>alert('Invalid mobile number. Please enter a valid 10-digit number.'); window.history.back();</script>";
        exit;
    }

    // Insert order into the orders table
    $query = "INSERT INTO orders (customer_name, address, mobile_number, total_amount, order_date, status, user_id) 
              VALUES ('$customer_name', '$address', '$mobile_number', '$total_amount', '$order_date', 'Pending','$user_id')";

    if (mysqli_query($conn, $query)) {
            $clear_cart_query = "DELETE FROM cart WHERE user_id = '$user_id'";
            mysqli_query($conn, $clear_cart_query);

            echo "<script>alert('Order placed successfully!'); window.location.href = 'cart.php';</script>";
        } else {
            echo "<script>alert('Cart is empty. Please add items to your cart.'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('Failed to place the order. Please try again.'); window.history.back();</script>";
    }
?>