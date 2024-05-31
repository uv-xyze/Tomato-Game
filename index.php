<?php

session_start();

include 'common.php';

// Check if the user is already logged in
if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true){
    header("Location: home.php");
    exit;
}



if($_SERVER["REQUEST_METHOD"] == "POST"){
   
    $servername = "localhost";
    $user = "root";
    $password = "";
    $database = "tomato";

    
    $conn = new mysqli($servername, $user, $password, $database);

   
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Retrieve username and password from form
    $username = $conn->real_escape_string($_POST['username']); 
    $password = $conn->real_escape_string($_POST['password']); 

    //check if user exists and the password is correct using a prepared statement
$sql = "SELECT id, password FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows == 1) {
    // Bind the result to variables
    $stmt->bind_result($id, $hashed_password);
    $stmt->fetch();

    // Check if the entered password matches the hashed password
    if (password_verify($password, $hashed_password)) {
        
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $username;
        $_SESSION['login_time'] = time();

       
        header("Location: home.php");
        exit;
    } else {
        
        $error = "Invalid username or password.";
        
        logEvent('Login Attempt', 'User attempted to log in with invalid credentials');
    }
} else {
    
    $error = "Invalid username or password.";
    
    logEvent('Login Attempt', 'User attempted to log in with invalid credentials');
}
t
$stmt->close();
}
?>



