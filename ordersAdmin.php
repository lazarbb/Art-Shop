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

    <div class="orders-container">
        <h2>Orders</h2>

        <?php
        require "database.php";
        $conn = get_connection();

        if (!isset($_COOKIE['user_logged_in'])) {
            header("Location: admin.php"); 
            exit();
        }

        $query = "SELECT * FROM orders";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<div class='order-item'>";
                echo "<h3>Order ID: " . $row['id'] . "</h3>";
                echo "<p>Name: " . $row['first_name'] . " " . $row['last_name'] . "</p>";
                echo "<p>Address: " . $row['address'] . "</p>";
                echo "<p>Products: " . $row['products'] . "</p>";
                echo "<p>Total Price: $" . $row['total_price'] . "</p>";
                echo "</div>";
            }
        } else {
            echo "<p>No orders found.</p>";
        }

        mysqli_close($conn);
        ?>

    </div>
  </body>
</html>
