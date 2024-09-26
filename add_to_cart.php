<?php
session_start();
$response = ['success' => false, 'cart_count' => 0];

if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
    require 'database.php';
    $conn = get_connection();

    $product_id = $_POST['product_id'];
    $quantity = (int)$_POST['quantity'];

    $result = mysqli_query($conn, "SELECT * FROM products WHERE id = $product_id");

    if ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $itemArray = array(
            $row["name"] => array(
                'id' => $row["id"],
                'name' => $row["name"],
                'image' => $row["image"],
                'price' => $row["price"],
                'quantity' => $quantity
            )
        );

        if (empty($_SESSION["shopping_cart"])) {
            $_SESSION["shopping_cart"] = $itemArray;
        } else {
            $array_keys = array_keys($_SESSION["shopping_cart"]);
            if (in_array($row["name"], $array_keys)) {
                $_SESSION["shopping_cart"][$row["name"]]["quantity"] += $quantity;
            } else {
                $_SESSION["shopping_cart"] = array_merge($_SESSION["shopping_cart"], $itemArray);
            }
        }
        $response['success'] = true;
        $response['cart_count'] = count($_SESSION["shopping_cart"]);
    }

    mysqli_free_result($result);
    mysqli_close($conn);
}

echo json_encode($response);
