<?php
    include('./connection.php');

    session_start();

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $usn = mysqli_real_escape_string($conn, $_POST['usn']);
        $pwd = mysqli_real_escape_string($conn, $_POST['pwd']);

        if (empty($usn) || empty($pwd)) {
            echo "Both fields are required.";
            exit();
        }

        $sql = "SELECT * FROM user WHERE usn = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $usn);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($pwd, $user['pwd'])) {
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['usn'] = $user['usn'];
                $_SESSION['loggedin'] = true;

                session_regenerate_id(true);

                header("Location: ../../index.html");
                exit();
            } else {
                echo "Incorrect password.";
            }
        } else {
            echo "No user found with that username.";
        }
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="icon" type="image/x-icon" href="../images/Logo-simple.svg">

    <style>
        html, body {
            height: 100%;
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .form-container {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        h2 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #333;
        }

        .input-group {
            margin-bottom: 15px;
            text-align: left;
        }

        .input-group label {
            font-size: 14px;
            color: #333;
            margin-bottom: 5px;
            display: block;
        }

        .input-group input {
            width: 100%;
            padding: 10px;
            font-size: 14px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
            transition: border-color 0.3s;
        }

        .input-group input:focus {
            border-color: #4caf50;
        }

        .submit-btn {
            width: 100%;
            padding: 12px;
            background-color: #4caf50;
            border: none;
            border-radius: 5px;
            color: white;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .submit-btn:hover {
            background-color: #45a049;
        }

        p a {
            color: #4caf50;
            text-decoration: none;
        }

        p a:hover {
            text-decoration: underline;
        }

    </style>

</head>
<body>

<div class="form-container">
    <h2>Login to Your Account</h2>
    <form method="POST" action="login.php">
        <div class="input-group">
            <label for="usn">Username:</label>
            <input type="text" id="usn" name="usn" required>
        </div>
        <div class="input-group">
            <label for="pwd">Password:</label>
            <input type="password" id="pwd" name="pwd" required>
        </div>
        <button type="submit" class="submit-btn">Login</button>
        <p>Don't have an account? <a href="./signup.php">Sign up here</a></p>
    </form>
</div>

</body>
</html>
