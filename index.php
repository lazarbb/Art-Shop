<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Art Shop</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <?php
    session_start();
    if (!empty($_SESSION["shopping_cart"])) {
        $cart_count = count(array_keys($_SESSION["shopping_cart"]));
    } else {
        $cart_count = 0;
    }
    $currentPage = basename($_SERVER['PHP_SELF']);
    session_write_close();
    ?>
    
    <nav>
        <div class="logo-container">
            <h1>Art Shop</h1>
            <a href="index.php"><img src="./photos/98dff1ef135960c3b148ebeba4a80377.jpg" alt="Logo"></a>
        </div>
        <div class="nav-buttons">
            <a href="index.php"><button <?php if ($currentPage == 'index.php') echo 'class="active"'; ?>>Home</button></a>
            <a href="products.php"><button <?php if ($currentPage == 'products.php') echo 'class="active"'; ?>>Browse shop</button></a>
            <a href="cart.php"><button <?php if ($currentPage == 'cart.php') echo 'class="active"'; ?>>Cart<span class="cart-badge"><?php echo $cart_count; ?></span></button></a>
            <a href="order.php"><button <?php if ($currentPage == 'order.php') echo 'class="active"'; ?>>Order information</button></a>
            <a class="login-button" href="admin.php"><button <?php if ($currentPage == 'admin.php') echo 'class="active"'; ?>>Admin Login</button></a>
        </div>
    </nav>

    <div class="info">
        <h3>Hello! Explore our wide range of products and enjoy this shopping experience.</h3>
        <h2>New Items</h2>
    </div>

    <div class="items-grid">
        <?php
        require "database.php";
        $conn = get_connection();
        $result = mysqli_query($conn, "SELECT * FROM products WHERE created_at >= DATE_SUB(NOW(), INTERVAL 3 day)");
        
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            echo "<div class='item'>";
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
            if(isset($_POST[$row["name"]."-insert"])){
              $itemArray = array(
                $row["name"] =>array(
                'id'=>$row["id"],
                'name'=>$row["name"],
                'image'=>$row["image"],
                'price'=>$row["price"],
                'quantity'=>(int)($_POST[$row["name"]."-quantity"])),
              );
              if(empty($_SESSION["shopping_cart"])) {
                $_SESSION["shopping_cart"] = $itemArray;
                $_SESSION["message"] = "Product added successfully.";
              }
              else {
                $array_keys = array_keys($_SESSION["shopping_cart"]);
                if(in_array($row["name"],$array_keys)) {
                  $_SESSION["message"] = "Product is already inside your cart.";
                } 
                else {
                  $_SESSION["shopping_cart"] = array_merge(
                    $_SESSION["shopping_cart"],
                    $itemArray
                  );
                  $_SESSION["message"] = "Product added successfully.";
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
</body>
</html>
