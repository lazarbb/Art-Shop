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
      if (!empty($_SESSION["shopping_cart"])) {
        $cart_count = count(array_keys($_SESSION["shopping_cart"]));
      } else {
        $cart_count = 0;
      }
    ?>
  <nav>
      <div class="logo-container">
      <h1>Art Shop</h1>
      <a href="index.php"><img src="./photos/98dff1ef135960c3b148ebeba4a80377.jpg"></a>
      </div>
      <div>
        <a href="index.php"><button <?php if ($currentPage == 'index.php') echo 'class="active"'; ?>>Home</button></a>
        <a href="products.php"><button <?php if ($currentPage == 'products.php') echo 'class="active"'; ?>>Browse shop</button></a>
        <a href="cart.php"><button <?php if ($currentPage == 'cart.php') echo 'class="active"'; ?>>Cart<span class="cart-badge"><?php echo $cart_count; ?></span></button></a>
        <a href="order.php"><button <?php if ($currentPage == 'order.php') echo 'class="active"'; ?>>Order information</button></a>
        <a class="login-button" href="admin.php"><button <?php if ($currentPage == 'admin.php') echo 'class="active"'; ?>>Admin Login</button></a>
    </div>
    </nav>

    <div class="cart-items">
      <?php
        if (empty($_SESSION["shopping_cart"])) {
          echo "<script language='javascript'>";
          echo "alert('Cart is empty.')";
          echo "</script>";
          session_write_close();
          exit;
        }
        require "database.php";
        $conn = get_connection();
        $total_cart_price = 0;
        foreach ($_SESSION["shopping_cart"] as $key => $item) {
          $total_price = $item["quantity"] * $item["price"];
          $total_cart_price += $total_price;
          echo "<div class=cart-item>";
          echo "<h2>$item[name]</h2>";
          echo "<h3>Total Price: $total_price$</h3>";
          echo "<form method='POST'>";
          echo "<div>";
          
          $product_name = mysqli_real_escape_string($conn, $item["name"]);
          $query = "SELECT quantity FROM products WHERE name = '$product_name'";
          $result = mysqli_query($conn, $query);
          $db_quantity = 0;
      
          if ($result && mysqli_num_rows($result) > 0) {
              $row = mysqli_fetch_assoc($result);
              $db_quantity = $row["quantity"];
          }

          echo "<input type='number' name=$item[name]-quantity min='1' max='50' value=$item[quantity]>";
          echo "<input type='submit' style='color: #24180a' value='Change quantity' class='add-to-cart-btn' name=$item[name]-change-quantity>";
          echo "</div>";
          echo "<input type='submit' style='color: #24180a' value='Remove' class='add-to-cart-btn' name=$item[name]-remove>";
          echo "</form>";
          echo "</div>";
          if (isset($_POST[$item["name"] . "-remove"])) {
            unset($_SESSION["shopping_cart"][$key]);
            header("Location: cart.php");
          } else if (isset($_POST[$item["name"] . "-quantity"])) {
            $quantity = (int)$_POST[$item["name"] . "-quantity"];
    
            if ($quantity > $db_quantity) {
                // Insufficient quantity in the database
                echo "<script language='javascript'>";
                echo "alert('Insufficient quantity for product: $product_name')";
                echo "</script>";
                $insufficientQuantity = true;
    
                // Set the cart quantity to the available quantity in the database
                $_SESSION["shopping_cart"][$key]["quantity"] = $db_quantity;
            } else {
                // Sufficient quantity, update the cart quantity
                $_SESSION["shopping_cart"][$key]["quantity"] = $quantity;
            }
    
            header("Location: cart.php");
          }
        }
        echo "<h2 class=cart-totatal-price>Cart total price: $total_cart_price$</h2>";
        session_write_close();
      ?>
    </div>

    <div class="cart-proceed-to-order-info" >
      <a href="order.php"><button>Buy now</button></a>
    </div>
  </body>
</html>