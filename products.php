<!DOCTYPE html>
<html lang="hr">
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
    if (isset($_SESSION['message'])) {
        $message = $_SESSION['message'];
        echo "<script language='javascript'>";
        echo "alert('$message')";
        echo "</script>";
        unset($_SESSION['message']);
    }
    $cart_count = !empty($_SESSION["shopping_cart"]) ? count(array_keys($_SESSION["shopping_cart"])) : 0;
    ?>
    
    <nav>
        <div class="logo-container">
            <h1>Art Shop</h1>
            <a href="index.php"><img src="./photos/98dff1ef135960c3b148ebeba4a80377.jpg" alt="Logo"></a>
        </div>
        <div class="search-container">
            <form action="" method="GET">
                <input type="text" name="query" placeholder="Search products..." value="<?php if(isset($_GET['query'])) echo htmlspecialchars($_GET['query']); ?>" style="border-radius: 20px; font-size: 16px;">
                <button type="submit" class="search-button">Search</button>
            </form>
        </div>
        <div>
            <a href="index.php"><button <?php if ($currentPage == 'index.php') echo 'class="active"'; ?>>Home</button></a>
            <a href="products.php"><button <?php if ($currentPage == 'products.php') echo 'class="active"'; ?>>Browse shop</button></a>
            <a href="cart.php"><button <?php if ($currentPage == 'cart.php') echo 'class="active"'; ?>>Cart<span class="cart-badge"><?php echo $cart_count; ?></span></button></a>
            <a href="order.php"><button <?php if ($currentPage == 'order.php') echo 'class="active"'; ?>>Order information</button></a>
            <a class="login-button" href="admin.php"><button <?php if ($currentPage == 'admin.php') echo 'class="active"'; ?>>Admin Login</button></a>
        </div>
    </nav>

    <div class="info">
        <h2>Products</h2>
        <label for="sortOptions">Sort by:</label>
        <select id="sortOptions">
            <option value="name-asc">Name (A-Z)</option>
            <option value="name-desc">Name (Z-A)</option>
            <option value="price-asc">Price (Low to High)</option>
            <option value="price-desc">Price (High to Low)</option>
        </select>
    </div>

    <div class="items-grid" id="productsGrid">
        <?php
        require "database.php";
        $conn = get_connection();

        $result = mysqli_query($conn, "SELECT * FROM products");
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            echo "<div class='item' data-name='{$row['name']}' data-price='{$row['price']}'>";
            echo "<img src='{$row['image']}' alt='Missing'>";
            echo "<h2>{$row['name']}</h2>";
            echo "<h3>Price: {$row['price']}$</h3>";
            echo "<form method='POST'>";
            echo "<div class='form-row'>";
            echo "<label for='{$row['name']}-quantity'>Quantity: </label>";
            echo "<input type='number' name='{$row['name']}-quantity' min='1' max='50' value='1'>";
            echo "</div>";
            echo "<input type='submit' value='Add to cart' class='add-to-cart-btn' name='{$row['name']}-insert'>";
            echo "</form>";
            echo "</div>";

            if (isset($_POST[$row["name"]."-insert"])) {
              $quantity = (int)$_POST[$row["name"]."-quantity"];
              $db_quantity = $row["quantity"];
        
              if ($quantity <= $db_quantity) {
                $itemArray = array(
                  $row["name"] => array(
                    'id' => $row["id"],
                    'name' => $row["name"],
                    'image' => $row["image"],
                    'price' => $row["price"],
                    'quantity' => $quantity, // Store entered quantity
                  ),
                );
        
                if (empty($_SESSION["shopping_cart"])) {
                  $_SESSION["shopping_cart"] = $itemArray;
                  $_SESSION["message"] = "Product added successfully.";
                } else {
                  $array_keys = array_keys($_SESSION["shopping_cart"]);
                  if (in_array($row["name"], $array_keys)) {
                    $_SESSION["message"] = "Product is already in your cart.";
                  } else {
                    $_SESSION["shopping_cart"] = array_merge($_SESSION["shopping_cart"], $itemArray);
                    $_SESSION["message"] = "Product added successfully.";
                  }
                }
              } else {
                $itemArray = array(
                  $row["name"] => array(
                    'id' => $row["id"],
                    'name' => $row["name"],
                    'image' => $row["image"],
                    'price' => $row["price"],
                    'quantity' => $db_quantity, // Store available quantity
                  ),
                );
        
                if (empty($_SESSION["shopping_cart"])) {
                  $_SESSION["shopping_cart"] = $itemArray;
                  $_SESSION["message"] = "Product added successfully, but the quantity is $db_quantity because that is all we have.";
                } else {
                  $array_keys = array_keys($_SESSION["shopping_cart"]);
                  if (in_array($row["name"], $array_keys)) {
                    $_SESSION["message"] = "Product is already in your cart.";
                  } else {
                    $_SESSION["shopping_cart"] = array_merge($_SESSION["shopping_cart"], $itemArray);
                    $_SESSION["message"] = "Product added successfully, but the quantity is $db_quantity because that is all we have.";
                  }
                }
              }
          header("Location: products.php");
        }
      }

        mysqli_free_result($result);
        mysqli_close($conn);
        session_write_close();
        ?>
    </div>

    <script src="script.js"></script>
</body>
</html>