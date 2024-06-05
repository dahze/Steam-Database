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

$db_host = "localhost";
$db_username = "root";
$db_password = "";
$db_name = "steamdb";

$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$username = $_SESSION['username'];

$query = "SELECT g.banner_url, g.name, g.price, g.appid, uw.added_at
FROM user_wishlist uw
JOIN games g ON uw.appid = g.appid
WHERE uw.username = ?
ORDER BY uw.added_at DESC";

$stmt = $conn->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "<table class='Wish-heading'>
    <tr>
        <td style='text-align: center;'>WISHLIST</td>
    </tr>
  
    </table>";
    while ($game = $result->fetch_assoc()) {
        echo "<table class='game-table'>

      <tr>
        <td style='text-align: center; padding-top: 5px; border-radius: 5px;'><a href='game_page.php?appid=" . $game['appid'] . "'><img src='" . $game['banner_url'] . "'></a></td>
      </tr>
      
      <tr>
        <td style='text-align: center'><p style='font-family: sans-serif; font-weight: bold; color: #c5c3c0;'>Price: " . "$".$game['price'] . "</p></td>
      </tr>
      
      <tr>
        <td style='text-align: center'>
            <form action='' method='post'>
              <input type='hidden' name='appid' value='" . $game['appid'] . "'>
              <input type='hidden' name='username' value='" . $username . "'>
              <input class='remove-btn' type='submit' name='remove_from_wishlist' value='Remove from Wishlist'>
            </form>
        </td>
      </tr>
     
      </table>";
    }
} else {
    echo "<div class='wishlist-empty'>YOUR WISHLIST IS EMPTY</div>";
}

if (isset($_POST['remove_from_wishlist'])) {
    $appid = $_POST['appid'];
    $username = $_POST['username'];

    // Check if user exists in users table
    $query = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // User exists, remove from user_wishlist
        $query = "DELETE FROM user_wishlist WHERE username = ? AND appid = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("si", $username, $appid);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo "<script>window.location.reload()</script>";
        }
    } else {
        // User doesn't exist, display error message
        echo "Error: User does not exist in users table.";
    }
}

$conn->close();
?>

<style>
    .Wish-heading{
        margin-top: 125px;
        border-radius: 5px;
        padding: 5px;
        background-color: #181c24;
        font-family: Poppins, sans-serif;
        color: #c5c3c0;
        font-weight: bold;
    }

    .wishlist-empty {
        border-radius: 5px;
        padding: 20px;
        background-color: #181c24;
        font-family: Poppins, sans-serif;
        color: red;
        font-weight: bold;
        margin-bottom: 150px;
    }

    .game-table {
        margin-top: 20px;
        table-layout: fixed;
        width: 21%;
        border-radius: 7px;
        background-color: #181c24;
    }

    .remove-btn{
        width: 100%;
        padding: 15px 20px 15px 20px;
        background-color: #248cfc;
        color: #fff;
        border: none;
        border-radius: 5px;
        display:block;
        margin: auto;
        cursor: pointer;
    }

    .remove-btn:hover{
        background-color: #1b9cfc;
    }

    .game-table:nth-child(1) {
        margin-top: 200px; /* add some margin to separate from header */
    }

    .game-table:nth-last-child(2) {
        margin-bottom: 300px; /* add some margin to separate from footer */
        padding-top: 0.1px; /* add a small padding to prevent margin collapse */
    }
</style>