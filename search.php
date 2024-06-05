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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $game_name = $_POST['game_name'];

    // Use local database to fetch appid
    $db_host = "localhost";
    $db_username = "root";
    $db_password = "";
    $db_name = "steamdb";

    $conn = new mysqli($db_host, $db_username, $db_password, $db_name);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $query = "SELECT appid FROM apps WHERE name = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $game_name);

    if ($stmt->execute()) {
        $stmt->bind_result($appid);
        if ($stmt->fetch()) {
            $_SESSION['appid'] = $appid; // Store appid in session
            $stmt->close();

            // Check if the game details are already stored in the database
            $query = "SELECT * FROM games WHERE appid = '$appid'";
            $result = mysqli_query($conn, $query);

            if (mysqli_num_rows($result) > 0) {
                // Game details are already stored, redirect to game_page.php
                header("Location: game_page.php?appid=$appid&user_id=".$_SESSION['user_id']);
                exit;
            } else {
                // Use Steam Spy API to retrieve game details
                $steam_spy_api_url = "https://steamspy.com/api.php?request=appdetails&appid=" . $appid;
                $steam_spy_api_response = json_decode(file_get_contents($steam_spy_api_url), true);

                if ($steam_spy_api_response) {
                    // Store game details in database
                    $query = "INSERT INTO games (appid, name, developer, publisher, score_rank, owners, average_forever, average_2weeks, median_forever, median_2weeks, ccu, price, initialprice, discount, genre, banner_url) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

                    $stmt = $conn->prepare($query);

                    $banner_url = "https://cdn.cloudflare.steamstatic.com/steam/apps/$appid/hero_capsule.jpg";

                    if (@file_get_contents($banner_url) === false) {
                        $banner_url = 'default_game_banner.png';
                    }

                    $price = $steam_spy_api_response['price'] / 100;
                    $initialprice = $steam_spy_api_response['initialprice'] / 100;

                    $stmt->bind_param("isssisiiiiiddiss",
                        $appid,
                        $steam_spy_api_response['name'],
                        $steam_spy_api_response['developer'],
                        $steam_spy_api_response['publisher'],
                        $steam_spy_api_response['score_rank'],
                        $steam_spy_api_response['owners'],
                        $steam_spy_api_response['average_forever'],
                        $steam_spy_api_response['average_2weeks'],
                        $steam_spy_api_response['median_forever'],
                        $steam_spy_api_response['median_2weeks'],
                        $steam_spy_api_response['ccu'],
                        $price,
                        $initialprice,
                        $steam_spy_api_response['discount'],
                        $steam_spy_api_response['genre'],
                        $banner_url
                    );

                    if ($stmt->execute()) {
                        echo "Game details stored successfully!";
                        // Call prices.php to store currency prices
                        header("Location: prices.php?appid=$appid");
                        exit;
                    } else {
                        echo "Error storing game details: " . $stmt->error;
                    }

                    $stmt->close();
                } else {
                    echo "Error fetching game information";
                }
            }
        } else {
            echo "<div class='not-found-error'>GAME NOT FOUND</div>";
        }
    } else {
        echo "Error fetching appid";
    }

    $conn->close();
}
?>

<style>
    .not-found-error {
        border-radius: 5px;
        padding: 20px;
        background-color: #181c24;
        font-family: Poppins, sans-serif;
        color: red;
        font-weight: bold;
        margin-bottom: 150px;
    }
</style>

