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

// Check if the user exists in the database
$query = "SELECT * FROM users WHERE username = '" . $_SESSION['username'] . "'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
    // User does not exist, unset session and redirect to login page
    unset($_SESSION['username']);
    session_destroy();
    header("Location: login.php");
    exit;
}

// Display recently viewed games
$query = "SELECT * FROM user_viewed WHERE username = '" . $_SESSION['username'] . "' ORDER BY viewed_at DESC LIMIT 12";
$result = mysqli_query($conn, $query);
if (mysqli_num_rows($result) > 0) {
    echo '<div class="dashpadding">
    <div class="dashformcontainer">
    <h1>Welcome, ' . $_SESSION['username'] . '!</h1>
    <br>
    <div><form action="search.php" method="post">
      <input class="search-input" type="text" name="game_name" id="game_name" placeholder="">
      <br>
      <br>
      <input class="search-btn" type="submit" value="Search">
      </div>
      </div>
    </div>
    <br>
    <br>';

    echo "<h2 class='View-heading'>Recently Viewed Games</h2>";
    echo "<div class='game-grid'>";
    while ($row = mysqli_fetch_assoc($result)) {
        $appid = $row['appid'];
        $innerQuery = "SELECT * FROM games WHERE appid = '$appid'";
        $innerResult = mysqli_query($conn, $innerQuery);
        $game_details = mysqli_fetch_assoc($innerResult);
        echo "<div class='game-item'>";
        echo "<a href='game_page.php?appid=" . $appid . "'>";
        echo "<img src='" . $game_details['banner_url'] . "'>";
        //echo "<h3>" . $game_details['name'] . "</a></h3>";
        echo "</div>";
    }
    echo "</div>";
}

else
{
    echo '<div class="dashpadding2">
    <div class="dashformcontainer">
    <h1>Welcome, ' . $_SESSION['username'] . '!</h1>
    <br>
    <div><form action="search.php" method="post">
      <input class="search-input" type="text" name="game_name" id="game_name" placeholder="">
      <br>
      <br>
      <input class="search-btn" type="submit" value="Search">
      </div>
      </div>
    </div>
    <br>
    <br>';
}

// Close the connection
mysqli_close($conn);
?>

<style>
    .View-heading {
        color: #c5c3c0;
        border-radius: 5px;
        font-weight: bold;
        margin-bottom: 10px;
        background-color: #181c24;
        padding: 3px 450px 3px 450px;
    }

    .dashpadding{
        padding-top: 125px;
    }

    .dashpadding2{
        padding-bottom: 125px;
    }

    .dashformcontainer {
        background-color: #181A21;
        padding: 20px 25px 20px 25px;
        border-radius: 10px;
        color: #c5c3c0;
        font-size: 13px;
        font-family: sans-serif !important;
    }

    .game-grid {
        padding-bottom: 250px;
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 10px;
    }

    .game-item {
        text-align: center;
        background-color: #181c24;
        padding: 10px 10px 10px 10px;
        border-radius: 10px;
    }

    .game-item img {
        width: 100%;
        height: 100%;
        border-radius: 10px;
    }

    .game-item h3 {
        font-size: 10px;
        color: #c5c3c0;
        font-weight: 500;
        text-decoration: none;
    }

    .search-input {
        background-color: #403c44;
        color: white;
        border: 0;
        padding: 9px;
        border-radius: 2px;
        outline: 0;
        width: 100%;
    }

    .search-btn {
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

    .search-btn:hover {
        background-color: #1b9cfc;
    }
</style>
