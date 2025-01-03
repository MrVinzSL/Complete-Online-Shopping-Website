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

$uid = $_SESSION['user_id'];
$oid = $_GET['order_del'];

$query = "UPDATE orders SET status='Cancelled' WHERE user_id=$uid AND order_id=$oid AND status='Pending';";

$result = mysqli_query($conn, $query);

if ($result) {
    header('Location: your_orders.php');
}
else {
    echo "<script>alert('Invalid Action.'); window.history.back();</script>";
}


?>