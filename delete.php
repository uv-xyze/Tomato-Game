<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

if (isset($_POST['deleteProfile'])) {
    // Database connection
    $servername = "localhost";
    $user = "root";
    $password = "";
    $dbname = "tomato";

    // Create a new mysqli connection
    $conn = new mysqli($servername, $user, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Delete user
    $username = $_SESSION['username'];
    $sql = "DELETE FROM users WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result) {
        // Logout the user and redirect to the login page
        session_unset();
        session_destroy();
        header("Location: signup.html");
        exit;
    } else {
        echo "Error deleting user: " . $conn->error;
    }

    $conn->close();
} else {
    // Redirect to home or some other page if the form wasn't submitted
    header("Location: home.php");
    exit;
}
?>

