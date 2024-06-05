<?php
ini_set('max_execution_time', 2000);
// Fetch the API response
$app_list = file_get_contents('https://api.steampowered.com/ISteamApps/GetAppList/v2/');

// Parse the JSON data
$app_data = json_decode($app_list, true);

// Connect to database
$conn = new mysqli('localhost', 'root', '', 'steamdb');

// Loop through the data and insert into the database
$last_appid = 0;
foreach ($app_data['applist']['apps'] as $app) {
    // Check if the appid already exists
    $stmt = $conn->prepare('SELECT * FROM apps WHERE appid = ?');
    $stmt->bind_param('i', $app['appid']);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) { // If the appid doesn't exist
        $name = $app['name'] ?: 'Unknown'; // Set a default value if name is null or empty
        $stmt = $conn->prepare('INSERT INTO apps (appid, name) VALUES (?, ?)');
        $stmt->bind_param('is', $app['appid'], $name);
        $stmt->execute();
    }

    $last_appid = $app['appid']; // Store the last appid
}

echo "Last record inserted: App ID " . $last_appid . "\n";

$conn->close();
?>