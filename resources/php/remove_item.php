<?php
    include('./connection.php');
    session_start();

    if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
        echo 'Error: User not logged in.';
        exit();
    }

    if (isset($_POST['item_id'])) {
        $item_id = (int) $_POST['item_id'];
        $user_id = $_SESSION['user_id'];

        $sql = "DELETE FROM shopping_list WHERE shopping_id = ? AND user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("dd", $item_id, $user_id);

        if ($stmt->execute()) {
            echo 'success';
        }

        $stmt->close();
    } else {
        echo 'error';
    }

    $conn->close();
?>
