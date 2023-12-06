<?php
// Connect to the database
$servername = "localhost";
$user = "root";
$password = "";
$dbname = "tomato";

$conn = new mysqli($servername, $user, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch leaderboard data from the database
$sql = "SELECT username, MAX(player_score) as player_score FROM score GROUP BY username ORDER BY player_score DESC LIMIT 10";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $leaderboardData = array();

    // Fetch data and store in an array
    while ($row = $result->fetch_assoc()) {
        $leaderboardData[] = $row;
    }

    // Return the data as JSON
    header('Content-Type: application/json');
    echo json_encode($leaderboardData);
} else {
    echo "No data found";
}

$conn->close();
?>
