<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('HTTP/1.1 401 Unauthorized');
    exit;
}

// Database connection
$servername = "localhost";
$user = "root";
$password = "";
$dbname = "tomato";

// Create a new mysqli connection
$conn = new mysqli($servername, $user, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    header('HTTP/1.1 500 Internal Server Error');
    die("Connection failed: " . $conn->connect_error);
}

$username = $_SESSION['username'];

// Fetch user details
$sql = "SELECT * FROM users WHERE username = '$username'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $profileData = $result->fetch_assoc();
    echo json_encode($profileData);
} else {
    header('HTTP/1.1 404 Not Found');
}

$conn->close();
?>
