<?php
require "database.php";
$connection = get_connection();
$currentPage = basename($_SERVER['PHP_SELF']);

if (!isset($_COOKIE['user_logged_in'])) {
    header("Location: admin.php"); 
    exit();
}

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $image = $_POST['image'];
    $quantity = $_POST['quantity'];

    $query = "INSERT INTO products (name, price, image, quantity) VALUES ('$name', '$price', '$image', '$quantity')";
    $insertResult = mysqli_query($connection, $query);

    if ($insertResult) {
        $_SESSION["message"] = "New item added successfully!";
        header("Location: productsAdmin.php");
        exit();
    } else {
        $_SESSION["message"] = "Error adding new item.";
    }
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
<body>
 
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
    <h2>Add New Item</h2>
    <form action="" method="POST">
        <label for="name">Name:</label>
        <input type="text" name="name" id="name" required><br>

        <label for="price">Price:</label>
        <input type="number" min="0" step="0.01" name="price" id="price" required><br>

        <label for="image">Image link:</label>
        <textarea name="image" id="image" required></textarea><br>

        <label for="quantity">Quantity:</label>
        <input type="number" min="0" step="1" name="quantity" id="quantity" value="<?php echo htmlspecialchars($product['quantity']); ?>" required><br>

        <input style="color: #24180a;" type="submit" name="submit" value="Add Item">
    </form>
    </div>
</body>
</html>
