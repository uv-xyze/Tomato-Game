<?php
// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tomato";


// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}



// Initialize variables
$email = $username = $password = $avatar = "";
$selectedAvatar = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $avatar = $_POST['avatar']; // Assuming you send the avatar filename as a string

    // Validation logic
    if (empty($email) || empty($username) || empty($password)) {
        echo "Please fill in all fields.";
    } else {
        // Insert data into the database
        $sql = "INSERT INTO users (email, username, password, avatar) VALUES ('$email', '$username', '$password', '$avatar')";

        if ($conn->query($sql) === true) {
             header("Location: login1.html");
			exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}
// Close the database connection
$conn->close();
?>







