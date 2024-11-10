<?php
    include('./connection.php');

    session_start();

    if (!$_SESSION['loggedin']) {
        echo "Error: User not logged in.";
        exit();
    }

    $user_id = $_SESSION['user_id'];


    if (isset($_POST['url'], $_POST['name'], $_POST['image'], $_POST['price'], $_POST['store'])) {        $productURL = htmlspecialchars($_POST['url']);
        $productName = htmlspecialchars($_POST['name']);
        $productPrice = (float) $_POST['price'];
        $productStore = htmlspecialchars($_POST['store']);
        $productImage = htmlspecialchars($_POST['image']);

        $sql = "INSERT INTO shopping_list (user_id, price, image_url, name, URL, store) VALUES (?, ?, ?, ?, ?, ?)";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("dsssss", $user_id, $productPrice, $productImage, $productName, $productURL, $productStore);

            if ($stmt->execute()) {
                echo "Product added to cart successfully!";
            } else {
                echo "Error: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Error: " . $conn->error;
        }

        $conn->close();
    } else {
        if (!isset($_POST['url'])) echo "Error: Missing product details (url).<br>";
        if (!isset($_POST['name'])) echo "Error: Missing product details (name).<br>";
        if (!isset($_POST['price'])) echo "Error: Missing product details (price).<br>";
        if (!isset($_POST['store'])) echo "Error: Missing product details (store).<br>";
        if (!isset($_POST['image'])) echo "Error: Missing product details (image).<br>";
    }
?>