<?php
include('./connection.php');
session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch cart items from the database
$sql = "SELECT * FROM shopping_list WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("d", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    $emptyCart = true;
} else {
    $emptyCart = false;
    $cartItems = [];
    $totalPrice = 0;

    while ($row = $result->fetch_assoc()) {
        $cartItems[] = $row;
        $totalPrice += $row['price'];
    }
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="icon" type="image/x-icon" href="../images/Logo-simple.svg">

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            padding: 20px;
        }

        .cart-container {
            max-width: 900px;
            margin: 0 auto;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 30px;
        }

        h2 {
            text-align: center;
            font-size: 28px;
            margin-bottom: 20px;
            color: #333;
        }

        .cart-item {
            display: flex;
            align-items: center;
            padding: 15px;
            border-bottom: 1px solid #eee;
            margin-bottom: 15px;
        }

        .cart-item img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 8px;
            margin-right: 20px;
        }

        .cart-item .details {
            flex-grow: 1;
        }

        .cart-item .details h4 {
            margin: 0;
            font-size: 18px;
            color: #333;
        }

        .cart-item .details p {
            margin: 5px 0;
            color: #777;
        }

        .cart-item .price {
            font-size: 18px;
            color: #4caf50;
            font-weight: bold;
        }

        .remove-btn {
            padding: 8px 12px;
            background-color: #f44336;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s;
        }

        .remove-btn:hover {
            background-color: #e53935;
        }

        .open-btn {
            padding: 8px 12px;
            background-color: #2196F3;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s;
            margin-left: 10px;
        }

        .open-btn:hover {
            background-color: #1976D2;
        }

        .total-price {
            text-align: right;
            font-size: 24px;
            margin-top: 20px;
            font-weight: bold;
            color: #333;
        }

        .empty-cart {
            text-align: center;
            font-size: 18px;
            color: #777;
            margin-top: 40px;
        }

        .checkout-btn {
            display: inline-block;
            padding: 15px 25px;
            background-color: #4caf50;
            color: white;
            font-size: 18px;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s;
            text-align: center;
            width: 100%;
            margin-top: 20px;
        }

        .checkout-btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

<div class="cart-container">
    <h2>Your Shopping Cart</h2>

    <?php if ($emptyCart): ?>
        <div class="empty-cart">
            <p>Your cart is empty. Start shopping now!</p>
        </div>
    <?php else: ?>
        <?php foreach ($cartItems as $item): ?>
            <div class="cart-item" id="cart-item-<?= $item['shopping_id']; ?>">
                <img src="<?= $item['image_url']; ?>" alt="<?= $item['name']; ?>">
                <div class="details">
                    <h4><?= $item['name']; ?></h4>
                    <p>Store: <?= $item['store']; ?></p>
                    <p>Price: $<?= number_format($item['price'], 2); ?></p>
                </div>
                <div class="price">
                    $<?= number_format($item['price'], 2); ?>
                </div>
                <button class="remove-btn" data-item-id="<?= $item['shopping_id']; ?>">Remove</button>
                <button class="open-btn" onclick="window.open('<?= $item['URL']; ?>', '_blank')">Open in New Tab</button>
            </div>
        <?php endforeach; ?>

        <div class="total-price">
            Total Price: $<?= number_format($totalPrice, 2); ?>
        </div>
    <?php endif; ?>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // Remove item from cart via AJAX
    $(document).on('click', '.remove-btn', function () {
        var itemId = $(this).data('item-id');

        $.ajax({
            url: 'remove_item.php',
            type: 'POST',
            data: { item_id: itemId },
            success: function() {
                // Remove item from the DOM
                $('#cart-item-' + itemId).fadeOut();
            },
            error: function(xhr, status, error) {
                console.error('AJAX error:', error);
                alert('Error removing item.');
            }
        });
    });
</script>

</body>
</html>
