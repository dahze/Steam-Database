<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport">
    <title>Register</title>
    <link rel="stylesheet" href="steamdb.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Play&display=swap" rel="stylesheet">
</head>
<body>

<header class="header">
    <img src="https://store.cloudflare.steamstatic.com/public/shared/images/header/logo_steam.svg?t=962016" alt="Logo" width="200" height="200">
    <a href="#" class="logo"></a>
</header>

<div>
    <br><br>
</div>

<div class="registerformcontainer">
    <form action="register.php" method="post">
        <h1 class="loginhead">ENTER USERNAME</h1>
        <input class="logininput" type="text" id="username" name="username" required><br><br>
        <label for="password">PASSWORD</label>
        <input class="logininput" type="password" id="password" name="password" required><br><br>
        <label for="confirm_password">CONFIRM PASSWORD</label>
        <input class="logininput" type="password" id="confirm_password" name="confirm_password" required><br><br>
        <br>
        <input type="submit" value="Register" class="sign-in-btn">
        <br>
        <a href="login.php" class="rl">Sign In</a>
    </form>
</div>

<footer>
    <div class="footer">
        <div class="row">
            <a href="https://www.facebook.com/Steam/"><i class="fa fa-facebook"></i></a>
            <a href="https://www.instagram.com/steam_games_official/?hl=en"><i class="fa fa-instagram"></i></a>
            <a href="https://www.youtube.com/channel/UChmZ6QbxgHlmX3ISIHxIpQQ"><i class="fa fa-youtube"></i></a>
            <a href="https://x.com/Steam?ref_src=twsrc%5Egoogle%7Ctwcamp%5Eserp%7Ctwgr%5Eauthor"><i class="fa fa-twitter"></i></a>
        </div>

        <div class="row">
            <ul>
                <li><a href="#">Contact us</a></li>
                <li><a href="#">Our Services</a></li>
                <li><a href="#">Privacy Policy</a></li>
                <li><a href="#">Terms & Conditions</a></li>
                <li><a href="#">Career</a></li>
            </ul>
        </div>

        <div class="row">
            105-131 Copyright © 2024 105-131 - All rights reserved || Designed By: 105-131
        </div>
    </div>
</footer>

</body>
</html>

<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Connect to the database
    $conn = mysqli_connect("localhost", "root", "", "steamdb");

    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);

    // Check if the username is already taken
    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) > 0) {
        echo "<p class='error-message'>Username already taken</p>";
        exit;
    }

    // Check if the passwords match
    if ($password != $confirm_password) {
        echo "<p class='error-message'>Passwords do not match</p>";
        exit;
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert the new user into the database
    $query = "INSERT INTO users (username, password) VALUES ('$username', '$hashed_password')";
    if (mysqli_query($conn, $query)) {
        header("Location: login.php");
        exit;
    } else {
        echo "Error: " . mysqli_error($conn);
    }

    mysqli_close($conn);
}
?>