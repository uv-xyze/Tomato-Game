<?php

$servername = "localhost";
$username = "root";
$password = "";
$database = "tomato";

$conn = new mysqli($servername, $username, $password, $database);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// log events
function logEvent($eventType, $eventDescription) {
    global $conn;

    $eventType = mysqli_real_escape_string($conn, $eventType);
    $eventDescription = mysqli_real_escape_string($conn, $eventDescription);

    $sql = "INSERT INTO logs (event_type, event_description) VALUES ('$eventType', '$eventDescription')";
    mysqli_query($conn, $sql);
}


?>
