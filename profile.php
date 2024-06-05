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
    <nav class="navbar">
        <a href="dashboard.php" class="Price">Search</a>
        <a href="profile.php">Profile</button></a>
        <a href="wishlist.php">Wishlist</button></a>
        <a href="login.php?logout=true">Logout</button></a>
    </nav>
</header>

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

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Connect to the database
$conn = mysqli_connect("localhost", "root", "", "steamdb");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Display the form to change password
echo '<div class="registerformcontainer">
<form action="profile.php" method="post">
  <label class="loginhead" for="current_password">Current Password</label>
  <br>
  <input class="logininput" type="password" name="current_password" id="current_password" placeholder="">
  <br>
  <br>
  <label for="new_password">New Password</label>
  <input class="logininput" type="password" name="new_password" id="new_password" placeholder="">
  <br>
  <br>
  <label for="confirm_new_password">Confirm New Password</label>
  <input class="logininput" type="password" name="confirm_new_password" id="confirm_new_password" placeholder="">
  <br>
  <br>
  <input class="sign-in-btn" type="submit" name="change_password" value="Change Password">
  <br>
  <input class="del-btn" type="submit" name="delete_account" value="Delete Account">
</form>
</div>';

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['change_password'])) {
        $current_password = mysqli_real_escape_string($conn, $_POST['current_password']);
        $new_password = mysqli_real_escape_string($conn, $_POST['new_password']);
        $confirm_new_password = mysqli_real_escape_string($conn, $_POST['confirm_new_password']);

        // Validate the input
        if ($new_password == $confirm_new_password) {
            // Check if the current password is correct
            $query = "SELECT * FROM users WHERE username = '" . $_SESSION['username'] . "'";
            $result = mysqli_query($conn, $query);
            $user_data = mysqli_fetch_assoc($result);
            if (password_verify($current_password, $user_data['password'])) {
                // Hash the new password
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

                // Update the user's password
                $query = "UPDATE users SET password = '$hashed_password' WHERE username = '" . $_SESSION['username'] . "'";
                $result = mysqli_query($conn, $query);

                if ($result) {
                    session_unset();
                    session_destroy();
                    header("Location: login.php");
                    exit;
                } else {
                    echo "<p class='error-message'>Error updating password!</p>";
                }
            } else {
                echo "<p class='error-message'>Current password is incorrect!</p>";
            }
        } else {
            echo "<p class='error-message'>Passwords do not match!</p>";
        }
    }

    if (isset($_POST['delete_account'])) {
        $query = "DELETE FROM user_viewed WHERE username = '" . $_SESSION['username'] . "'";
        $result = mysqli_query($conn, $query);
        $query = "DELETE FROM user_wishlist WHERE username = '" . $_SESSION['username'] . "'";
        $result = mysqli_query($conn, $query);
        $query = "DELETE FROM users WHERE username = '" . $_SESSION['username'] . "'";
        $result = mysqli_query($conn, $query);
        if ($result) {
            session_unset();
            session_destroy();
            header("Location: login.php");
            exit;
        } else {
            echo "<p class='error-message'>Error deleting account!</p>";
        }
    }
}

// Close the connection
mysqli_close($conn);
?>

<style>
.del-btn {
        width: 165%;
        padding: 15px 20px 15px 20px;
        background-color: rgba(255, 0, 0, 0.85);
        color: #fff;
        border: none;
        border-radius: 5px;
        display:block;
        margin: auto;
        cursor: pointer;
}

.del-btn:hover {
        background-color: #ff0000;
}
</style>
