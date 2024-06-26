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




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #210D41;
            margin-left: 0;
        }

        .container {
            max-width: 400px;
            margin-top: 0;
            margin-right: auto;
            margin-left: auto;
            margin-bottom: 50px;
            padding-top: 50px;
            padding-right: 50px;
            padding-left: 50px;
            padding-bottom: 50px;
            background-color: #210D41;
            border-radius: 5px;
           
        }

        .title {
            text-align: center;
            max-width: 150px;
            width: auto;
            margin-top: 0;
        }

        .input-container {
            position: relative;
            margin: 15px 0;
        }

        .input-container img {
            position: absolute;
            height: 20px;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
        }

        .input-container input {
            width: 85%;
            padding: 10px;
            padding-left: 40px;
            border: 7px solid #ccc;
            border-radius: 5px;
        }

        .submit-button {
            background: url('images/continue.png') no-repeat;
            background-size: cover;
            width: 100%;
            padding: 40px;
            border: none;
            cursor: pointer;
        }

        .bottom-image {
            max-width: 110%;
            display: block;
            margin-top: 0px;
            margin-right: auto;
            margin-left: auto;
            font-size: 36px;
            cursor: pointer;
        }

        
    </style>
</head>
<body>
	
	<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <div class="container">
        <h2 class="title">
            <img src="images/login11.png" alt="Login Title" width="400">
        </h2>
        <p><img src="images/username.png" alt="username" width="150"></p>
        <div class="input-container">
            <input type="text" id="username" name="username" required> 
        </div>
        <p><img src="images/password.png" alt="Password" width="150"></p>
        <div class="input-container">
            <input type="password" id="password" name="password" required> 
        </div>
        <div class="input-container">
            <button class="submit-button" id="submit-button"></button> 
        </div>
        <p><img src="images/dont.png" alt="bottom-image" width="300" height="75" class="bottom-image" id="bottom-image" </p>
		</div>

        </form>
    </div>
	
 <script>
    document.addEventListener("DOMContentLoaded", function () {
        document.getElementById("bottom-image").addEventListener("click", function () {
            window.location.href = "signup.html";
        });

        
    });
</script>

        
        
</body>
</html>  




