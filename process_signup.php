<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "tomato";


$conn = new mysqli($servername, $username, $password, $database);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$email = $conn->real_escape_string($_POST['email']);
$username = $conn->real_escape_string($_POST['username']);
$password = $conn->real_escape_string($_POST['password']); 
    



// Check if the username or email already exists
$checkQuery = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
$checkQuery->bind_param("ss", $username, $email);
$checkQuery->execute();
$result = $checkQuery->get_result();

if ($result->num_rows > 0) {
    die("Username or email already exists");
}

// Hash the password
$hashed_password = password_hash($password, PASSWORD_BCRYPT);


$stmt = $conn->prepare("INSERT INTO users (email, username, password) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $email, $username, $hashed_password);

if ($stmt->execute()) {
    
    header("Location: login.php");
    exit();
} else {
    echo "Error: " . $stmt->error;
}


$stmt->close();
$conn->close();
?>

