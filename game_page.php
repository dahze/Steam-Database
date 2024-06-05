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
            105-131 Copyright Â© 2024 105-131 - All rights reserved || Designed By: 105-131
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

if (isset($_GET['appid'])) {
    $appid = $_GET['appid'];
    $username = $_SESSION['username'];

    // Check if the row already exists in the user_viewed table
    $query = "SELECT * FROM user_viewed WHERE username = '$username' AND appid = '$appid'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 0) {
        // Row doesn't exist, insert it
        $query = "INSERT INTO user_viewed (username, appid) VALUES ('$username', '$appid')";
        mysqli_query($conn, $query);
    }

    // Fetch game details from database using $appid
    $query = "SELECT * FROM games WHERE appid = '$appid'";
    $result = mysqli_query($conn, $query);
    $game_details = mysqli_fetch_assoc($result);

    $price = ($game_details['price'] == 0) ? "Free" : "$".$game_details['price'];
    $initial_price = ($game_details['initialprice'] == 0) ? "Free" : "$".$game_details['initialprice'];
    $discount = ($game_details['discount'] == 0) ? "N/A" : $game_details['discount']."%";
    $score_rank = ($game_details['score_rank'] == 0) ? "N/A" : $game_details['score_rank'];
    ?>

    <div class="game-details-wrapper">
        <div class="game-details">
            <table>
                <tr>
                    <th class="game-title"><?= $game_details['name'] ?></th>
                </tr>
                <tr>
                    <td>Developer: <?= $game_details['developer'] ?></td>
                </tr>
                <tr>
                    <td>Publisher: <?= $game_details['publisher'] ?></td>
                </tr>
                <tr>
                    <td>Score Rank: <?= $score_rank ?></td>
                </tr>
                <tr>
                    <td>Owners: <?= $game_details['owners'] ?></td>
                </tr>
                <tr>
                    <td>Average Forever: <?= $game_details['average_forever'] ?></td>
                </tr>
                <tr>
                    <td>Average 2 Weeks: <?= $game_details['average_2weeks'] ?></td>
                </tr>
                <tr>
                    <td>Median Forever: <?= $game_details['median_forever'] ?></td>
                </tr>
                <tr>
                    <td>Median 2 Weeks: <?= $game_details['median_2weeks'] ?></td>
                </tr>
                <tr>
                    <td>CCU: <?= $game_details['ccu'] ?></td>
                </tr>
                <tr>
                    <td>Price: <?= $price ?></td>
                </tr>
                <tr>
                    <td>Initial Price: <?= $initial_price ?></td>
                </tr>
                <tr>
                    <td>Discount: <?= $discount ?></td>
                </tr>
                <tr>
                    <td>Genre: <?= $game_details['genre'] ?></td>
                </tr>
            </table>
        </div>

        <div class="image-container">
            <img src="<?= $game_details['banner_url'] ?>" alt="Game Banner">
            <?php
            // Check if game is already in wishlist
            $query = "SELECT * FROM user_wishlist WHERE username = '$username' AND appid = '$appid'";
            $result = mysqli_query($conn, $query);

            if (mysqli_num_rows($result) > 0) {
                // Game is already in wishlist, display remove form
                echo "<form action='' method='post'>
                <input type='hidden' name='appid' value='" . $appid . "'>
                <input type='hidden' name='username' value='" . $username . "'>
                <input type='submit' name='remove_from_wishlist' value='Remove from Wishlist' class='game-btn'>
                </form>";
            } else {
                // Game is not in wishlist, display add form
                echo "<form action='' method='post'>
                <input type='hidden' name='appid' value='" . $appid . "'>
                <input type='hidden' name='username' value='" . $username . "'>
                <input type='submit' name='add_to_wishlist' value='Add to Wishlist' class='game-btn'>
                </form>";
            }
}

        if (isset($_POST['remove_from_wishlist'])) {
            $appid = $_POST['appid'];
            $username = $_POST['username'];

            // Check if user exists in users table
            $query = "SELECT * FROM users WHERE username = '$username'";
            $result = mysqli_query($conn, $query);

            if (mysqli_num_rows($result) > 0) {
                // User exists, remove from user_wishlist
                $query = "DELETE FROM user_wishlist WHERE username = '$username' AND appid = '$appid'";
                $result = mysqli_query($conn, $query);

                if ($result) {
                    echo "Game removed from wishlist successfully!";
                    header("Location: game_page.php?appid=" . $appid);
                    exit;
                } else {
                    echo "Error removing game from wishlist: " . mysqli_error($conn);
                }
            } else {
                // User doesn't exist, display error message
                echo "Error: User does not exist in users table.";
            }
        }

        if (isset($_POST['add_to_wishlist'])) {
            $appid = $_POST['appid'];
            $username = $_POST['username'];

            // Check if user exists in users table
            $query = "SELECT * FROM users WHERE username = '$username'";
            $result = mysqli_query($conn, $query);

            if (mysqli_num_rows($result) > 0) {
                // User exists, insert into user_wishlist
                $query = "INSERT INTO user_wishlist (username, appid) VALUES ('$username', '$appid')";
                $result = mysqli_query($conn, $query);

                if ($result) {
                    echo "Game added to wishlist successfully!";
                    header("Location: game_page.php?appid=" . $appid);
                    exit;
                } else {
                    echo "Error adding game to wishlist: " . mysqli_error($conn);
                }
            } else {
                // User doesn't exist, display error message
                echo "Error: User does not exist in users table.";
            }
        }
        ?>
    </div>
</div>

<div class="game-prices-wrapper">
    <table>
        <tr>
        </tr>
        <?php
        $query = "SELECT * FROM game_prices WHERE appid = '$appid'";
        $result = mysqli_query($conn, $query);
        while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><img src="us.svg" alt="US Flag"> US</td>
                <td><img src="eu.svg" alt="EU Flag"> EU</td>
                <td><img src="uk.svg" alt="UK Flag"> UK</td>
                <td><img src="ca.svg" alt="CA Flag"> CA</td>
                <td><img src="au.svg" alt="AU Flag"> AU</td>
                <td><img src="jp.svg" alt="JP Flag"> JP</td>
                <td><img src="ch.svg" alt="CH Flag"> CH</td>
                <td><img src="mx.svg" alt="MX Flag"> MX</td>
                <td><img src="ru.svg" alt="RU Flag"> RU</td>
                <td><img src="in.svg" alt="IN Flag"> IN</td>
                <td><img src="br.svg" alt="BR Flag"> BR</td>
                <td><img src="cn.svg" alt="CN Flag"> CN</td>
                <td><img src="kr.svg" alt="KR Flag"> KR</td>
                <td><img src="sg.svg" alt="SG Flag"> SG</td>
                <td><img src="pl.svg" alt="PL Flag"> PL</td>
            </tr>
            <tr>
                <td><?php echo $row['US']; ?></td>
                <td><?php echo $row['EU']; ?></td>
                <td><?php echo $row['UK']; ?></td>
                <td><?php echo $row['CA']; ?></td>
                <td><?php echo $row['AU']; ?></td>
                <td><?php echo $row['JP']; ?></td>
                <td><?php echo $row['CH']; ?></td>
                <td><?php echo $row['MX']; ?></td>
                <td><?php echo $row['RU']; ?></td>
                <td><?php echo $row['IN']; ?></td>
                <td><?php echo $row['BR']; ?></td>
                <td><?php echo $row['CN']; ?></td>
                <td><?php echo $row['KR']; ?></td>
                <td><?php echo $row['SG']; ?></td>
                <td><?php echo $row['PL']; ?></td>
            </tr>
        <?php } ?>
    </table>
</div>

<style>
    .game-title{
        padding-bottom: 5px;
    }
    .game-prices-wrapper {
        padding: 20px 40px 20px 40px;
        border-radius: 10px;
        background-color: #181c24;
        color: #c5c3c0;
        display: flex; /* added this */
        text-align: center;
        flex-direction: column; /* added this */
        width: auto; /* change width to auto */
        margin-bottom: 250px;
    }

    .game-prices-wrapper table td {
        background-color: #212630;
    }

    .game-prices-wrapper table td:hover {
        background-color: #2f3542;
    }

    .game-details-wrapper {

        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: center;
        margin: 150px 20px 20px 20px;
    }

    .game-details-wrapper table td {
        background-color: #212630;
    }

    .game-details-wrapper table td:hover {
        background-color: #2f3542;
    }

    .game-details {
        width: 50%;
        padding: 20px 50px 20px 20px;
        border-radius: 10px;
        background-color: #181c24;
        white-space: nowrap; /* added this */
        color: #c5c3c0;
        border-bottom: 1px solid #333;
        height: 500px;
        display: flex; /* added this */
        flex-direction: column; /* added this */
        justify-content: space-evenly; /* added this */
        width: auto; /* change width to auto */
        min-width: 50%; /* add a minimum width */
        word-wrap: break-word; /* add word wrapping */
        overflow-wrap: break-word; /* add overflow wrapping */
    }

    table td {
        padding: 7px; /* Add space between lines */
    }

    .image-container {
        width: 50%;
        background-color: #181c24;
        padding: 10px 10px 10px 10px;
        border-radius: 10px;
        margin-left: 10px;
    }

    .image-container img {
        width: 100%;
        height: auto;
        border-radius: 10px;
    }

    .game-btn{
        width: 100%;
        padding: 5px 5px 5px 5px;
        background-color: #248cfc;
        color: #fff;
        border: none;
        border-radius: 5px;
        display:block;
        cursor: pointer;
        margin-bottom: 3px;

    }

    .game-btn:hover{
        background-color: #1b9cfc;
    }
</style>