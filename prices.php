<?php
session_start();

if (!isset($_SESSION['username']) || !isset($_SESSION['appid'])) {
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

$appid = $_GET['appid'];
$currencies = array('US', 'EU', 'UK', 'CA', 'AU', 'JP', 'CH', 'MX', 'RU', 'IN', 'BR', 'CN', 'KR', 'SG', 'PL');

$prices = [];
$success = false;

foreach ($currencies as $cc) {
    $url = "https://store.steampowered.com/api/appdetails?appids=$appid&cc=$cc&filters=price_overview";
    $json = file_get_contents($url);

    if ($json === false) {
        echo "Error fetching data for $cc\n";
        continue;
    }

    $data = json_decode($json, true);

    if (json_last_error() === JSON_ERROR_NONE && isset($data[$appid]['success']) && $data[$appid]['success'] == true) {
        $price_overview = $data[$appid]['data']['price_overview'];
        $final_formatted_price = $price_overview['final_formatted'];
        $prices[$cc] = $final_formatted_price;
        $success = true;
    } else {
        echo "Error parsing data for $cc\n";
    }
}

if ($success) {
    // Escape column names with backticks
    $columns = implode(", ", array_map(function($col) { return "`$col`"; }, array_keys($prices)));
    $placeholders = implode(", ", array_fill(0, count($prices), '?'));
    $update_columns = implode(", ", array_map(function($col) { return "`$col` = VALUES(`$col`)"; }, array_keys($prices)));

    $query = "INSERT INTO game_prices (`appid`, $columns) VALUES (?, $placeholders) ON DUPLICATE KEY UPDATE $update_columns";
    $stmt = $conn->prepare($query);

    if ($stmt === false) {
        die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
    }

    $types = "i" . str_repeat("s", count($prices)); // 's' for string types
    $values = array_merge([$appid], array_values($prices));

    $stmt->bind_param($types, ...$values);

    if (!$stmt->execute()) {
        echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
    } else {
        header("Location: game_page.php?appid=$appid&user_id=".$_SESSION['user_id']);
        exit;
    }

    $stmt->close();
}

$conn->close();
?>