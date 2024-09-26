<?php
require "database.php";
$connection = get_connection();

if (!isset($_COOKIE['user_logged_in'])) {
    header("Location: admin.php"); 
    exit();
}

if (isset($_GET['id'])) {
    $productID = $_GET['id'];

  
    $query = "SELECT * FROM products WHERE id = $productID";
    $result = mysqli_query($connection, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $product = mysqli_fetch_assoc($result);
    } else {
        $_SESSION["message"] = "Product not found.";
        exit();
    }
} else {
    $_SESSION["message"] = "Invalid product ID.";
    exit();
}


if (isset($_POST['delete'])) {
    $query = "DELETE FROM products WHERE id = $productID";
    $deleteResult = mysqli_query($connection, $query);

    if ($deleteResult) {
        $_SESSION["message"] = "Product deleted successfully!";
        header("Location: productsAdmin.php");
        exit();
    } else {
        $_SESSION["message"] = "Error deleting product.";
    }
}


if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $image = $_POST['image'];
    $quantity = $_POST['quantity'];

    $query = "UPDATE products SET name = '$name', price = '$price', image = '$image', quantity = '$quantity' WHERE id = $productID";
    $updateResult = mysqli_query($connection, $query);

    if ($updateResult) {
        $_SESSION["message"] = "Product updated successfully!";
    } else {
        $_SESSION["message"] = "Error updating product.";
    }
    header("Location: productsAdmin.php");
}
?>

<!DOCTYPE html>
<html>
<head> 
    <meta charset="UTF-8">
    <title>Web Shop</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style.css">
    
</head>
<body>
<?php
    session_start();
    $currentPage = basename($_SERVER['PHP_SELF']);
    session_write_close();
?>
    <nav>
      <div class="logo-container">
      <h1>Art Shop</h1>
      <a href="dashboard.php"><img src="./photos/98dff1ef135960c3b148ebeba4a80377.jpg"></a>
      </div>
      <div>
    <a href="dashboard.php"><button <?php if ($currentPage == 'dashboard.php') echo 'class="active"'; ?>>Home</button></a>
    <a href="productsAdmin.php"><button <?php if ($currentPage == 'productsAdmin.php') echo 'class="active"'; ?>>Browse products</button></a>
    <a href="add_product.php"><button <?php if ($currentPage == 'add_product.php') echo 'class="active"'; ?>>Add new product</button></a>
    <a href="ordersAdmin.php"><button <?php if ($currentPage == 'ordersAdmin.php') echo 'class="active"'; ?>>Orders</button></a>
    <a class="login-button" href="logout.php"><button <?php if ($currentPage == 'logout.php') echo 'class="active"'; ?>>Logout</button></a>
</div>
    </nav>
    
    <div class="form-container2">
    <h2>Product Details</h2>
    <form action="" method="POST">
        <label for="name">Name:</label>
        <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($product['name']); ?>" required><br>

        <label for="price">Price:</label>
        <input type="number" min="0" step="0.01" name="price" id="price" value="<?php echo htmlspecialchars($product['price']); ?>" required><br>

        <label for="image">Image link:</label>
        <textarea name="image" id="image" required><?php echo htmlspecialchars($product['image']); ?></textarea><br>

        <label for="quantity">Quantity:</label>
        <input type="number" min="0" step="1" name="quantity" id="quantity" value="<?php echo htmlspecialchars($product['quantity']); ?>" required><br>

        <input style="color: #24180a" type="submit" name="submit" value="Save Changes">
    </form>

    <form action="" method="POST">
        <input style="color: #24180a"type="submit" name="delete" value="Delete Product" class="delete-btn">
    </form>
    </div>
</body>
</html>
