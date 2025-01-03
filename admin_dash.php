<?php
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'cshells';

$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Add Product Logic with Image Upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {
    $product_name = $_POST['product_name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $image = $_FILES['image'];

    if (!file_exists('uploads')) {
        mkdir('uploads', 0777, true);
    }

    $image_name = time() . '-' . basename($image['name']);
    $image_tmp_name = $image['tmp_name'];
    $image_folder = 'uploads/' . $image_name;

    if (move_uploaded_file($image_tmp_name, $image_folder)) {
        $query = "INSERT INTO products (product_name, description, price, stock, image_url) 
                  VALUES ('$product_name', '$description', '$price', '$stock', '$image_name')";
        if ($conn->query($query)) {
            header("Location: " . $_SERVER['PHP_SELF'] . "?success=1");
            exit();
        } else {
            echo "Failed to add product to the database.";
        }
    } else {
        echo "Failed to upload image.";
    }
}

// Update Stock Logic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_stock'])) {
    $id = $_POST['id'];
    $new_stock = $_POST['new_stock'];
    $query = "UPDATE products SET stock = '$new_stock' WHERE product_id = '$id'";
    $conn->query($query);
}

// Remove Product Logic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_product'])) {
    $id = $_POST['remove_id'];
    $query = "SELECT image_url FROM products WHERE product_id = '$id'";
    $result = $conn->query($query);
    $row = $result->fetch_assoc();
    $image_url = 'uploads/' . $row['image_url'];
    if (file_exists($image_url)) {
        unlink($image_url);
    }
    $query = "DELETE FROM products WHERE product_id = '$id'";
    $conn->query($query);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style_admin.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <style>
        body { font-family: Arial, sans-serif; }
        .form { margin-bottom: 20px; }
        input, button { padding: 10px; margin: 5px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: center; }
        img { max-width: 100px; }
        .file-input-wrapper { display: flex; align-items: center; gap: 10px; }

        #name{
            max-width: 1220px;
            margin-left: 300px;

        }
    </style>
</head>
<body>
<div id="name">
    <h1>Admin Dashboard</h1>

    <!-- Logout Button -->
    <div style="margin: 20px 0; text-align: right;">
        <form method="POST" action="logout.php">
            <button type="submit" style="padding: 10px 20px; background-color: #ff4d4d; color: white; border: none; cursor: pointer; font-size: 16px;">
                Logout
            </button>
        </form>
    </div>
    <!-- Home Page Button -->
    <div style="margin: 20px 0; text-align: right;">
        <form method="POST" action="cart.php">
            <button type="submit" style="padding: 10px 20px; background-color:rgb(92, 255, 77); color: white; border: none; cursor: pointer; font-size: 16px;">
                Home Page
            </button>
        </form>
    </div>

    <!-- JavaScript Alert -->
    <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
        <script>alert("Product added successfully! 🎉");</script>
    <?php endif; ?>

    <!-- Add Product -->
    <div class="form">
        <h2>Add Product</h2>
        <form method="POST" enctype="multipart/form-data">
            <input type="text" name="product_name" placeholder="Product Name" required>
            <textarea name="description" placeholder="Description" required></textarea>
            <input type="number" name="price" placeholder="Price" step="0.01" required>
            <input type="number" name="stock" placeholder="Stock" required>
            <div class="file-input-wrapper">
                <input type="file" name="image" id="imageInput" required>
                <button type="button" id="removeImageButton">Remove or Reupload the Image</button>
            </div>
            <button type="submit" name="add_product">Add Product</button>
        </form>
    </div>

    <!-- Update Stock -->
    <div class="form">
        <h2>Update Stock</h2>
        <form method="POST">
            <input type="number" name="id" placeholder="Product ID" required>
            <input type="number" name="new_stock" placeholder="New Stock" required>
            <button type="submit" name="update_stock">Update Stock</button>
        </form>
    </div>

    <!-- View Products -->
    <h2>All Products</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Description</th>
            <th>Price</th>
            <th>Stock</th>
            <th>Image</th>
            <th>Action</th>
        </tr>
        <?php
        $query = "SELECT * FROM products";
        $result = $conn->query($query);
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                <td>{$row['product_id']}</td>
                <td>{$row['product_name']}</td>
                <td>{$row['description']}</td>
                <td>{$row['price']}</td>
                <td>{$row['stock']}</td>
                <td><img src='uploads/{$row['image_url']}' alt='Product Image'></td>
                <td>
                    <form method='POST'>
                        <input type='hidden' name='remove_id' value='{$row['product_id']}'>
                        <button type='submit' name='remove_product'>Remove</button>
                    </form>
                </td>
            </tr>";
        }
        ?>
    </table>

    <script>
        const removeImageButton = document.getElementById('removeImageButton');
        const imageInput = document.getElementById('imageInput');
        removeImageButton.addEventListener('click', () => {
            imageInput.value = ''; // Clear the selected file
            alert('You can now choose a new file!');
        });
    </script>
</div>
</body>
</html>
