<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport">
    <title>Login</title>
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

<div class="formcontainer">
    <form action="login.php" method="post">
        <h1 class="loginhead">SIGN IN WITH ACCOUNT NAME</h1>
        <label for="username"></label>
        <input class="logininput" type="text" id="username" name="username" required><br><br>
        <label for="password">PASSWORD</label>
        <input class="logininput" type="password" id="password" name="password" required><br><br>
        <br>
        <input type="submit" value="Sign in" class="sign-in-btn">
        <br>
        <a href="register.php" class="rl">Register</a>
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

if (isset($_SESSION['username']) && !isset($_GET['logout'])) {
    // User is already logged in, redirect to dashboard
    header("Location: dashboard.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Connect to the database
    $conn = mysqli_connect("localhost", "root", "", "steamdb");

    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Check if the username exists
    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $user_data = mysqli_fetch_assoc($result);

        // Verify the password
        if (password_verify($password, $user_data['password'])) {
            // User is authenticated, store username in session
            $_SESSION['username'] = $username;
            // Redirect to dashboard
            header("Location: dashboard.php");
            exit;
        } else {
            // Display error message
            $error = "<p class='error-message'>Invalid username or password</p>";
        }
    } else {
        // Display error message
        $error = "<p class='error-message'>Invalid username or password</p>";
    }

    // Close database connection
    mysqli_close($conn);
}

if (isset($_GET['logout']) && $_GET['logout']) {
    unset($_SESSION['username']);
    session_destroy();
    header("Location: login.php");
    exit;
}
?>

<!-- Display error message if set -->
<?php if (isset($error)) { echo $error; } ?>