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
            padding: 0;
        }

        .cart-container {
            max-width: 900px;
            padding: 260px 30px;
            margin: 0 auto;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
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
            width: 30px;
            height: auto;
            object-fit: cover;
            border-radius: 0;
            margin-right: 0;
        }

        #pic {
            width: 100px;
            height: 100px;
            margin-right: 15px;
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
            margin-left: 10px;
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
    </style>
</head>
<body>
<div id="navbar">
    <img id="logo" src="../images/logo.svg" onclick="window.location.href = '../../index.html';" alt="Logo">

    <div id="navbar-wrap">
                <span id="search-bar">
                    <input type="text" id="search-input" placeholder="Search products...">
                    <button class="search-button" onclick="" aria-label="Search">
                        <img src="../images/Search.svg" alt="Search Icon">
                    </button>

                    <button id="shopping-cart" onclick="window.location.href = './cart.php';">
                        <img src="../images/Cart.svg" alt="Cart">
                        <label>Go to Cart</label>
                    </button>
                    <div id="dropdown-menu">
                <div id="dropdown-grid">
                    <div class="cell" onclick="querySearchBar('produce')">
                        <img src="../images/produce-category.png" alt="Produce">
                        <p>Produce</p>
                    </div>
                    <div class="cell" onclick="querySearchBar('school supplies')">
                        <img src="../images/school-category.png" alt="School Supplies">
                        <p>School Supplies</p>
                    </div>
                    <div class="cell" onclick="querySearchBar('beverages')">
                        <img src="../images/drinks-category.png" alt="Beverages">
                        <p>Beverages</p>
                    </div>
                    <div class="cell" onclick="querySearchBar('household&essentials')">
                        <img src="../images/household-category.png" alt="Household Essentials">
                        <p>Household Essentials</p>
                    </div>
                    <div class="cell" onclick="querySearchBar('meat')">
                        <img src="../images/meat-category.png" alt="Meat">
                        <p>Meat</p>
                    </div>
                    <div class="cell" onclick="querySearchBar('cleaning&supplies')">
                        <img src="../images/cleaning-category.png" alt="Cleaning Supplies">
                        <p>Cleaning Supplies</p>
                    </div>
                </div>
            </div>
                </span>

            <div class="widgets">
                <a href="../../index.html">About</a>
                <a href="mailto:peterl8@rpi.edu,carla2@rpi.edu,loaizs@rpi.edu" target="_blank">Contact</a>
                <a href="./login.php">Login</a>
                <a href="./logout.php">Logout</a>
            </div>
    </div>
</div>

<div class="cart-container">
    <h2>Your Shopping Cart</h2>

    <?php if ($emptyCart): ?>
        <div class="empty-cart">
            <p>Your cart is empty. Start shopping now!</p>
        </div>
    <?php else: ?>
        <?php foreach ($cartItems as $item): ?>
            <div class="cart-item" id="cart-item-<?= $item['shopping_id']; ?>">
                <img src="<?= $item['image_url']; ?>" id="pic" alt="<?= $item['name']; ?>">
                <div class="details">
                    <h4><?= $item['name']; ?></h4>
                    <p>Store: <?= $item['store']; ?></p>
                    <p>Price: $<?= number_format($item['price'], 2); ?></p>
                </div>
                <div class="price">
                    $<?= number_format($item['price'], 2); ?>
                </div>
                <button class="remove-btn" data-item-id="<?= $item['shopping_id']; ?>"><img src="../images/icons8-trash.svg" width="12" height="auto"></button>
                <button class="open-btn" onclick="window.open('<?= $item['URL']; ?>', '_blank')"><img src="../images/external-link-svgrepo-com.svg" width="12" height="auto"></button>
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
