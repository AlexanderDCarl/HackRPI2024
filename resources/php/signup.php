<?php
    include('./connection.php');

    session_start();

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $usn = mysqli_real_escape_string($conn, $_POST['usn']);
        $pwd = mysqli_real_escape_string($conn, $_POST['pwd']);
        $pwd_confirm = mysqli_real_escape_string($conn, $_POST['pwd_confirm']);

        if (empty($usn) || empty($pwd) || empty($pwd_confirm)) {
            echo "All fields are required.";
            exit();
        }

        if ($pwd !== $pwd_confirm) {
            echo "Passwords do not match.";
            exit();
        }

        $hashed_pwd = password_hash($pwd, PASSWORD_DEFAULT);

        $sql = "INSERT INTO user (usn, pwd) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $usn, $hashed_pwd);

        if ($stmt->execute()) {
            echo "Signup successful!";
            header("Location: login.php"); // Redirect to login page
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
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
    <h2>Create an Account</h2>
    <form method="POST" action="signup.php">
        <div class="input-group">
            <label for="usn">Username:</label>
            <input type="text" id="usn" name="usn" required>
        </div>
        <div class="input-group">
            <label for="pwd">Password:</label>
            <input type="password" id="pwd" name="pwd" required>
        </div>
        <div class="input-group">
            <label for="pwd_confirm">Confirm Password:</label>
            <input type="password" id="pwd_confirm" name="pwd_confirm" required>
        </div>
        <button type="submit" class="submit-btn">Sign Up</button>
        <p>Already have an account? <a href="./login.php">Login here</a></p>
    </form>
</div>

</body>
</html>