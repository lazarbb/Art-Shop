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

    <div class="info">
      <h3>
         Hello admin. You can change and update products, also you can add new ones or delete the existing ones.
      </h3>
      <h2>New Items</h2>
    </div>
    
    <div class="items-grid">
      
      <?php

        require "database.php";
        $conn = get_connection();
        
        if (!isset($_COOKIE['user_logged_in'])) {
          header("Location: admin.php"); 
          exit();
      }
        
        $newItemsQuery = "SELECT * FROM products WHERE created_at >= DATE_SUB(NOW(), INTERVAL 3 day)";
        $newItemsResult = mysqli_query($conn, $newItemsQuery);
       
        while ($row = mysqli_fetch_array($newItemsResult, MYSQLI_ASSOC)) {
            
          echo "<div class=item>";
          echo "<img src=$row[image] alt='Missing'>";
          echo "<h2>$row[name]</h2>";
          echo "<h3>Price: $row[price]$</h3>";
          echo "<h2>In stock: $row[quantity]</h2>";
          echo "<a href='update_product.php?id=$row[id]'><button  class='update-btn'>Update</button></a>";
          echo "</div>";
        }

        mysqli_free_result($newItemsResult);
        mysqli_close($conn);
        session_write_close();
      ?>
    </div>
  </body>
</html>