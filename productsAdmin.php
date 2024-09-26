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
      <div class="search-container">
        <form action="" method="GET">
          <input type="text" name="query" placeholder="Search products..." value="<?php if(isset($_GET['query'])) echo htmlspecialchars($_GET['query']); ?>" style="border-radius: 20px; font-size: 16px;">>
          <button type="submit" class="search-button">Search</button>
        </form>
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
    
      <h2>Products</h2>
    </div>

    <div class="items-grid">
      <?php
        require "database.php";
        $conn = get_connection();

        if (!isset($_COOKIE['user_logged_in'])) {
          header("Location: admin.php"); 
          exit();
      }
      if(isset($_GET['query'])) {
        $search_query = mysqli_real_escape_string($conn, $_GET['query']);
        $result = mysqli_query($conn, "SELECT * FROM products WHERE name LIKE '%$search_query%'");
        
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
          echo "<div class=item>";
          echo "<img src=$row[image] alt='Missing'>";
          echo "<h2>$row[name]</h2>";
          echo "<h3>Price: $row[price]$</h3>";
          echo "<h2>In stock: $row[quantity]</h2>";
          echo "<a href='update_product.php?id=$row[id]'><button class='update-btn'>Update</button></a>";
          echo "</div>";
        }
      }else{
        $result = mysqli_query($conn, "SELECT * FROM products");
        
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
          echo "<div class=item>";
          echo "<img src=$row[image] alt='Missing'>";
          echo "<h2>$row[name]</h2>";
          echo "<h3>Price: $row[price]$</h3>";
          echo "<h2>In stock: $row[quantity]</h2>";
          echo "<a href='update_product.php?id=$row[id]'><button class='update-btn'>Update</button></a>";
          echo "</div>";
        }
      }


        

        mysqli_free_result($result);
        mysqli_close($conn);
        session_write_close(); 
      ?>
    </div>
    
    
  </body>
</html>
