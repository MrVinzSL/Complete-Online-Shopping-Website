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

session_start(); // Start session for cart functionality

if (isset($_POST['remove'])) {
    $pid = $_POST['product_id'];
    $uid = $_SESSION['user_id'];

    $query = "DELETE FROM cart WHERE user_id=$uid AND product_id=$pid;";
    $result = mysqli_query($conn, $query);

    echo "<script>alert('Product removed successfully')</script>";
    header('Location: cart.php');

}
?>