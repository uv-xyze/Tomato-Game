
<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

// Check if session has expired (45 minutes)
if (time() - $_SESSION['login_time'] > 2700) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'];


$servername = "localhost";
$user = "root";
$password = "";
$dbname = "tomato";


$conn = new mysqli($servername, $user, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch the highest score
$sql = "SELECT MAX(player_score) as highest_score FROM score WHERE username = '$username'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $highestScore = $row['highest_score'];
} else {
    $highestScore = 0;
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>home</title>
    <style>
		
        body {
            font-family: Arial, sans-serif;
            background: url('images/home.png') no-repeat;
            margin: 0;
            padding: 10; 
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        #title {
            padding: 100;
            max-width: 100%;
            height: auto;
        }

        .button-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 20px;
        }

        .button {
            padding: 20px;
            border: none;
            border-radius: 5px;
            font-size: 24px;
            cursor: pointer;
            background-repeat: no-repeat;
            background-position: center;
            background-size: cover;
            text-align: center;
            width: 400px;
            height: 70px;
        }

        

        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.7);
        }

        .modal-content {
            background-color: #fff;
            border-radius: 5px;
            margin: 15% auto;
            padding: 20px;
            width: 70%;
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
        }

        .close {
            position: absolute;
            top: 0;
            right: 0;
            padding: 10px;
            cursor: pointer;
        }

        #startButton {
            background-image: url('images/start.png');
        }

        #leaderboardBtn {
            background-image: url('images/leaderboard.png');
        }

        #logoutButton {
            background-image: url('images/logout1.png');
            margin-top: -7px;
        }
        #profileButton {
            background-image: url('images/profile.png');
        }

        .toggle-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

		#leaderboard-popup {
    display: none;
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
   box-shadow: 0 0 30px rgba(0, 0, 0, 0.8);
    z-index: 2000;
   
	
    width: 50vw; 
    height: 37vw;
    max-height: 100vh;
    padding: 30px;
    background-color: #AAD6F3;
	border-radius: 4%;
    border: 2px solid #ccc;
    z-index: 1000;
    text-align: center;
}

#leaderboard-popup .popup-header {
    margin-bottom: 15px;
}

#leaderboard-popup .popup-header img {
    max-width: 90%;
    height: auto;
}

#leaderboard-popup .popup-content {
    max-height: 300px;
    overflow-y: auto;
}

#leaderboard-popup table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
}

#leaderboard-popup table th, #leaderboard-popup table td {
    padding: 8px;
    border: 1px solid #ddd;
}

#leaderboard-popup table th {
    background-color: #3498db;
    color: #fff;
}

#leaderboard-popup .popup-button {
    margin-top: 15px;
}

#leaderboard-popup button {
    padding: 10px 15px;
    background-color: #3498db;
    color: #fff;
    border: none;
    cursor: pointer;
    font-size: 16px;
}

#leaderboard-popup button:hover {
    background-color: #2980b9;
}
#profile-popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.8);
            z-index: 2000;
            width: 50vw;
            height: 37vw;
            max-height: 100vh;
            padding: 30px;
            background-color: #AAD6F3;
            border-radius: 4%;
            border: 2px solid #ccc;
            z-index: 1000;
            text-align: center;
        }

        #profile-popup .popup-header {
            margin-top: 0px;
            color: red;
            font-size: 16px;
        }

        #profile-popup .popup-header img {
            max-width: 70%;
            height: auto;
        }

        #profile-popup .popup-content {
            max-height: 300px;
            overflow-y: auto;
        }

        #profile-popup .popup-content img{
            max-width: 50%;
            height: auto;
        }
        #profile-popup .popup-button {
            position: absolute;
        top: -30px;
        right: -50px;
        font-size: 50px;
        background: none;
        border: none;
        cursor: pointer;
        
        border-radius: 20px;
        }

        form {
        text-align: center;
    }

    input[type="submit"] {
        padding: 10px 20px;
        font-size: 16px;
        background-color: red; 
        color: #fff; 
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease; 
        margin-top: 40px;
    }

    input[type="submit"]:hover {
        background-color: black;
    }
    </style>
</head>
<body>
 
    <audio id="soundclick" src="music/click.mp3" preload="auto"></audio>
    

    <img id="title" src="images/title.png" alt="Title Image">
    <div class="button-container">
        
		<button class="button" id="startButton" onclick="playButtonClickSound(); setTimeout(() => { window.location.href = 'gameview.php'; }, 500);"></button>
        <button class="button" id="leaderboardBtn" ></button>
        <button class="button" id="profileButton" ></button>
         <button class="button" id="logoutButton" onclick="playButtonClickSound(); setTimeout(() => { window.location.href = 'logout.php'; }, 500);"></button>
       
    </div>
    

	<div id="leaderboard-popup" style="display: none;">
    <div class="popup-header">
        <img src="images/lead.png" alt="Leaderboard Image">
    </div>
    <div class="popup-content">
        <table id="leaderboard-table">
            <thead>
                <tr>
                    <th>RANK</th>
                    <th>USERNAME</th>
                    <th>SCORE</th>
                </tr>
            </thead>
            <tbody id="leaderboard-body"></tbody>
        </table>
    </div>
    <div class="popup-button">
        <button id="close-leaderboard-btn">Close</button>
    </div>
</div>



<div id="profile-popup" style="display: none;">
<div class="popup-header">

            <h1><img src="images/welcome.png" alt="Welcome Image"><?php echo $username; ?>!</h1>
        
    </div>
    <div class="popup-content">
    <h1><img src="images/high.png" alt="high Image"><?php echo $highestScore; ?></p>
        

        <form method="post" action="delete.php">
    <input type="submit" name="deleteProfile" value="Delete Profile">
</form>

    </div>
    <div class="popup-button">
        <button id="close-profile-btn">X</button>
    </div>
</div>


    

    <script>
       
	document.addEventListener('DOMContentLoaded', function() {
    const leaderboardPopup = document.getElementById("leaderboard-popup");
    const leaderboardBody = document.getElementById("leaderboard-body");
    const closeLeaderboardBtn = document.getElementById("close-leaderboard-btn");
    const leaderboardBtn = document.getElementById("leaderboardBtn");
    const startButton = document.getElementById("startButton");
    const logoutButton = document.getElementById("logoutButton");
    const soundClick = document.getElementById("soundclick");
    const profileButton = document.getElementById("profileButton");
    const profilePopup = document.getElementById("profile-popup");
    const profileBody = document.getElementById("profile-body");
    const closeProfileBtn = document.getElementById("close-profile-btn");
    
    let leaderboardClicked = false;
let profileClicked = false;

    function fetchLeaderboardData() {
        const xhr = new XMLHttpRequest();
        const url = 'fetch_leaderboard.php';

        xhr.open('GET', url, true);

        xhr.onload = function() {
            if (xhr.status === 200) {
                const leaderboardData = JSON.parse(xhr.responseText);
                populateLeaderboard(leaderboardData);
            } else {
                console.error('Error fetching leaderboard data:', xhr.statusText);
            }
        };

        xhr.onerror = function() {
            console.error('Network error while fetching leaderboard data');
        };

        xhr.send();
    }

    function playButtonClickSound() {
        soundClick.play();
        soundClick.onended = function() {
           
            if (!leaderboardClicked && !profileClicked) {
            window.location.href = 'gameview.php';
            }
        };
    }

    function populateLeaderboard(leaderboardData) {
        
        leaderboardBody.innerHTML = '';

        
        leaderboardData.forEach((entry, index) => {
            const row = document.createElement("tr");
            row.innerHTML = `<td>${index + 1}</td><td>${entry.username}</td><td>${entry.player_score}</td>`;
            leaderboardBody.appendChild(row);
        });

        leaderboardPopup.style.display = "block";
    }

    closeLeaderboardBtn.addEventListener('click', function() {
        leaderboardPopup.style.display = "none";
        playButtonClickSound();
    });

    leaderboardBtn.addEventListener('click', function() {
        leaderboardClicked = true;
        fetchLeaderboardData();
        playButtonClickSound();
    });

    startButton.addEventListener('click', function() {
        playButtonClickSound();
        setTimeout(() => {
            window.location.href = 'gameview.php';
        }, 500);
    });

    profileButton.addEventListener('click', function () {
    profileClicked = true;
    fetchProfileData();
    playButtonClickSound();
});
    logoutButton.addEventListener('click', function() {
        playButtonClickSound();
        setTimeout(() => {
            window.location.href = 'logout.php';
        }, 500);
    });


    

    function fetchProfileData() {
       
        const xhr = new XMLHttpRequest();
        const url = 'profile.php';

        xhr.open('GET', url, true);

        xhr.onload = function() {
            if (xhr.status === 200) {
                const profileData = JSON.parse(xhr.responseText);
                populateProfile(profileData);
            } else {
                console.error('Error fetching profile data:', xhr.statusText);
            }
        };

        xhr.onerror = function() {
            console.error('Network error while fetching profile data');
        };

        xhr.send();
    }

    function populateProfile(profileData) {
        
        profileBody.innerHTML = '';

       
        Object.entries(profileData).forEach(([detail, value]) => {
            const row = document.createElement("tr");
            row.innerHTML = `<td>${detail}</td><td>${value}</td>`;
            profileBody.appendChild(row);
        });

        profilePopup.style.display = "block";
    }

    closeProfileBtn.addEventListener('click', function() {
        profilePopup.style.display = "none";
        playButtonClickSound();
    });

    profileButton.addEventListener('click', function() {
        fetchProfileData();
        profilePopup.style.display = "block";
        playButtonClickSound();
    });
});
</script>
</body>
</html>
