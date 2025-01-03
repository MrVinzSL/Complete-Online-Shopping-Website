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
	header('location:customer_login/main.php');
}


if (isset($_POST['add_to_cart'])) {
    if (!isset($_SESSION['user_id'])) {
        die('User is not logged in.');
    }

    $uid = $_SESSION['user_id'];
    $pid = $_POST['pid'];
    $pname = $_POST['pname'];
    $pdes = $_POST['pdes'];
    $pprice = $_POST['pprice'];
    $pqty = $_POST['pqty'];
    $purl = $_POST['purl'];

    // Check if the product is already in the cart
    $sql = "SELECT * FROM cart WHERE product_id = '$pid'";
    $sql_result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($sql_result) > 0) {
        echo '<script>alert("This product is already added!");</script>';
    } else {
        // Use prepared statement to insert into the cart
        $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, product_name, product_description, product_url, product_price) 
                                VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('iissss', $uid, $pid, $pname, $pdes, $purl, $pprice);

        if ($stmt->execute()) {
            echo '<script>alert("Product successfully added!");</script>';
        } else {
            echo '<script>alert("Operation Failed!");</script>';
        }

        $stmt->close();
    }

    // Redirect to cart page
    header('Location: cart.php');
    exit;
}
?>
