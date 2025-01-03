<?php
session_start();

$host = 'localhost';
$user = 'root';
$password = '';
$database = 'cshells';

// Establish connection
$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize error variable
$error = "";

// Login logic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];

    // Query the admin table
    $query = "SELECT * FROM admin WHERE username = '$username'";
    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {
        $admin = $result->fetch_assoc();

        // Verify password
        if ($password == $admin['password']) {
            // Set session variables
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_name'] = $admin['username'];

            // Redirect to admin dashboard
            header("Location: admin_dash.php");
            exit();
        } else {
            $error = "Invalid username or password." . $admin['username'];
        }
    } else {
        $error = "Error.";
    }
}

// Close connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="admin_login_style.css">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
    <link rel="icon" href="favicon.ico" type="image/x-icon">
</head>
<style>
        .button-row {
            display: flex; /* Arrange items in a row */
            justify-content: center; /* Center items horizontally */
            gap: 20px; /* Add space between buttons */
            margin: 20px 0; /* Add spacing around the row */
        }

        .btnLog {
            font-size: 1.1rem;
            padding: 8px 0;
            border-radius: 5px;
            outline: none;
            border: none;
            width: 500px; /* Adjust width to fit text */
            background: rgb(148, 126, 4);
            color: white;
            cursor: pointer;
            transition: 0.9s;
            text-align: center; /* Center text inside buttons */
            text-decoration: none; /* Remove underline */
            margin-bottom: 0; /* Remove space below button */
        }

        .btnLog:hover {
            background: #463f02;
        }
    </style>
<body>
    <div class="login-container">
        <h1>Admin Login</h1>
        <?php if (!empty($error)): ?>
            <p class="error"><?= $error ?></p>
        <?php endif; ?>
        <form method="POST">
            <div class="form-group">
            <i class='bx bxs-user'></i>
                <input type="text" name="username" id="username" placeholder="Enter your username" required>
                <label for="username">Username</label>
            </div>
            <div class="form-group">
            <i class='bx bxs-lock-alt' ></i>
                <input type="password" name="password" id="password" placeholder="Enter your password" required>
                <label for="password">Password</label>
            </div>
            <button type="submit" class="btn" name="login">Login</button>
        </form>
        <div class="button-row">
        <a href="cart.php" class="btnLog">Home Page</a>
        <a href="customer_login/main.php" class="btnLog">Login as a Customer</a>
    </div>
        <div class="forgot-password">
            <a href="forgot_password.php">Forgot Password?</a>
        </div>
    </div>
    
</body>
</html>
