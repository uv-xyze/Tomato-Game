    <!-- Sound Effect by UNIVERSFIELD from Pixabay (https://pixabay.com/users/universfield-28281460/?utm_source=link-attribution&utm_medium=referral&utm_campaign=music&utm_content=132111) -->
    <!-- Sound Effect from <a href="https://pixabay.com/sound-effects/?utm_source=link-attribution&utm_medium=referral&utm_campaign=music&utm_content=97915">Pixabay</a>-->
    <!-- Sound Effect from <a href="https://pixabay.com/sound-effects/?utm_source=link-attribution&utm_medium=referral&utm_campaign=music&utm_content=47165">Pixabay</a>-->
    <!-- Sound Effect from <a href="https://pixabay.com/sound-effects/?utm_source=link-attribution&utm_medium=referral&utm_campaign=music&utm_content=6346">Pixabay</a>-->
    <audio id="soundclick" src="music/click.mp3" preload="auto"></audio>
    <audio id="soundfail" src="music/fail.mp3" preload="auto"></audio>
    <audio id="soundsuccess" src="music/success.mp3" preload="auto"></audio>
    <audio id="soundlevelup" src="music/levelup.wav" preload="auto"></audio>
	
<?php
session_start();


// Check if the user is not logged in, redirect to the login page
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
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

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Tomato Game</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=VT323">
	<link rel="stylesheet" href="styles.css">
	
</head>
<body>
	
    <div id="game-container">
        <div id="player-info-container">
            
            <div id="player-name">
                <img src="images/title.png" alt="title">
				
            </div>
			

        </div>
		<div id="countdown-container">
    <p>C O U N T D O W N : <span id="countdown-display">05:00</span></p>
</div>
        <div id="game-header">
            <div id="player-info">
                <span id="player-level" class="bold-text"> L E V E L   1</span>
<span id="player-strikes" class="bold-text">S T R I K E S : </span>
<span id="player-score" class="bold-text">S C O R E : 0</span>

            </div>
			

            
		

    <div class="answer-container">
    <p>I N P U T : <span id="displayed-number"></span></p>
</div>
			
		<button id="mute-button" >M U T E</button>


        </div>
        <div id="game-content">
            <div id="question-container">
				<img id="image" src="placeholder.png" alt="Math Question">
					<button id="skip-button"></button>
				
                
				
            </div>
            <div id="keypad">
                
            </div>
                
                 <button id="enter-button"></button>
            
        </div>
        <div id="feedback-container">
            <div id="feedback-message"></div>
            
            <div id="level-up-popup"></div>
        </div>
    </div>
	
	<div id="game-over-popup">
    <div class="popup-header">
        <img src="images/gameover1.png" alt="Header Image">
			</div>
			<div class="popup-status">
				<p id="game-over-level"></p>
    <p id="game-over-score"></p>
			</div>
		<div class="popup-button1">
			<button id="restart-btn" onclick="playButtonClickSound(); setTimeout(() => { window.location.href = 'gameview.php'; }, 500);"></button>
			</div>	
    <div class="popup-button2">
			<button id="home-btn" onclick="playButtonClickSound(); setTimeout(() => { window.location.href = 'home.php'; }, 500);"></button>
			</div>	
			<div class="popup-button3">
			<button id="leaderboard-btn"></button>
				
			</div>	
			
</div>
	
<div id="leaderboard-popup" style="display: none;">
    <div class="popup-header">
        <img src="images/lead.png" alt="Leaderboard Image">
    </div>
    <div class="popup-content">
        <table id="leaderboard-table">
            <thead>
                <tr>
                    <th>R A N K</th>
                    <th>U S E R N A M E</th>
                    <th>S C O R E</th>
                </tr>
            </thead>
            <tbody id="leaderboard-body"></tbody>
        </table>
    </div>
    <div class="popup-button">
        <button id="close-leaderboard-btn">C L O S E</button>
    </div>
</div>


    <script>
		
     let isMuted = false;

    function playSound(soundId) {
        const soundElement = document.getElementById(soundId);

        if (!isMuted) {
            soundElement.play();
        }
    }

    function toggleMute() {
        const soundElements = document.querySelectorAll('audio');

        if (isMuted) {
            soundElements.forEach(sound => sound.muted = false);
        } else {
            soundElements.forEach(sound => sound.muted = true);
        }

        isMuted = !isMuted;
        updateMuteButtonText();
		
    }

    function updateMuteButtonText() {
        const muteButton = document.getElementById('mute-button');
        muteButton.textContent = isMuted ? 'U N M U T E' : '  M U T E  ';
    }

    document.getElementById('mute-button').addEventListener('click', function() {
		
        toggleMute();
    });

		
const numberButtons = document.querySelectorAll(".number-button");
const displayedNumber = document.getElementById("displayed-number");

numberButtons.forEach(button => {
    button.addEventListener("click", function() {
        const number = button.getAttribute("data-number");
        displayedNumber.textContent = number;
		 playSound('soundclick');
    });
});

       
 
		
		
        // Fetch image from the API and set it as the game image
        const gameImage = document.getElementById('image');
        const apiUrl = 'https://marcconrad.com/uob/tomato/api.php?out=csv&base64=yes';

        fetch(apiUrl)
            .then((response) => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.text();
            })
            .then((data) => {
                const apiResponse = data.split(',');
                const imageBase64 = apiResponse[0];

                // Set the game image using base64 data
                gameImage.src = 'data:image/jpeg;base64,' + imageBase64;
            })
            .catch((error) => {
                console.error('Error:', error);
            });
		
	
document.addEventListener('DOMContentLoaded', function() {
	
	

    const numberButtons = document.querySelectorAll(".number-button");
    const displayedNumber = document.getElementById("displayed-number");
    const enterButton = document.getElementById("enter-button");
	const skipButton = document.getElementById("skip-button"); 
	 const gameOverPopup = document.getElementById("game-over-popup");
    const restartBtn = document.getElementById('restart-btn');
    const homeBtn = document.getElementById('home-btn');
    const leaderboardBtn = document.getElementById('leaderboard-btn');
    const settingsBtn = document.getElementById('settings-btn');
	const leaderboardPopup = document.getElementById("leaderboard-popup");
    const leaderboardBody = document.getElementById("leaderboard-body");
    const closeLeaderboardBtn = document.getElementById("close-leaderboard-btn");
	   
  
	



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
		 playSound('soundclick');
    });

    leaderboardBtn.addEventListener('click', function() {
        fetchLeaderboardData();
    });
    
    let previousSolution = null;
	let consecutiveCorrectAnswers = 0;
	let accumulatedCorrectAnswers = 0;
    let playerLevel = 1;
	let playerScore = 0; 
	let playerStrikes = 0;
	
	
	function sendScoreToServer() {
    const xhr = new XMLHttpRequest();
    const url = 'insert_score.php'; 

    // Prepare the data to be sent
    const data = new FormData();
    data.append('username', '<?php echo $username; ?>'); 
    data.append('player_score', playerScore);

    // Set up the AJAX request
    xhr.open('POST', url, true);

    
    xhr.onload = function () {
        if (xhr.status === 200) {
            console.log('Score sent successfully');
        } else {
            console.error('Error sending score:', xhr.statusText);
        }
    };

    
    xhr.onerror = function () {
        console.error('Network error while sending score');
    };

    
    xhr.send(data);
	}
	

    const countdownContainer = document.getElementById('countdown-container');
    const countdownDisplay = document.getElementById('countdown-display');
    let countdownMinutes = 300;    

    function updateCountdown() {
        const minutes = Math.floor(countdownMinutes / 60);
        const seconds = countdownMinutes % 60;
        countdownDisplay.textContent = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
    }

    function startCountdown() {
        updateCountdown();

        const countdownInterval = setInterval(function () {
            countdownMinutes--;

            if (countdownMinutes < 0) {
                clearInterval(countdownInterval);
                handleGameOver();
            } else {
                updateCountdown();
            }
        }, 1000);
    }

    
    startCountdown();

    function resetCountdown() {
        countdownMinutes = 300 - (playerLevel - 1) * 60;
        startCountdown();
    }

    function levelUp() {
        resetCountdown();
		 playSound('soundlevelup');
      
    }

   

	
	function handleGameOver() {
    const gameOverPopup = document.getElementById("game-over-popup");
    const gameLevel = document.getElementById("game-over-level");
    const gameScore = document.getElementById("game-over-score");

    gameLevel.textContent = `LEVEL: ${playerLevel}`;
    gameScore.textContent = `SCORE: ${playerScore}`;

		
		// Store playerLevel and playerScore in local storage
    localStorage.setItem(`LEVEL: ${playerLevel}`, playerLevel);
    localStorage.setItem(`SCORE: ${playerScore}`, playerScore);
		
    gameOverPopup.style.display = "block";

    numberButtons.forEach(button => {
        button.disabled = true;
    });
    enterButton.disabled = true;
    skipButton.disabled = true;
		
	sendScoreToServer();
}
	
    function updateGame(data) {
        const questionImageURL = data.question;
        const correctSolution = data.solution;

        const isCorrect = displayedNumber.textContent == previousSolution;
		
		
		
		
if (isCorrect) {
    consecutiveCorrectAnswers++;
    accumulatedCorrectAnswers++;

    if (consecutiveCorrectAnswers % 3 === 0 || accumulatedCorrectAnswers === 7) {
        playerLevel++;
        document.getElementById("player-level").textContent = `L E V E L : ${playerLevel}`;
        levelUp();
		 playSound('soundlevelup');
        consecutiveCorrectAnswers = 0;
    } 
            playerScore += 25;
            document.getElementById("player-score").textContent = `S C O R E : ${playerScore}`;
        
} else {
    consecutiveCorrectAnswers = 0;

            
            playerStrikes++;
            document.getElementById("player-strikes").textContent = `S T R I K E S : ${'X'.repeat(playerStrikes)}`;

            
            if (playerStrikes === 5) {
        handleGameOver();
				 playSound('soundfail');
        return; 
    }
}

if (accumulatedCorrectAnswers === 7) {
    accumulatedCorrectAnswers = 0;
}
		
		
        // answer feedback popup
        const popup = document.createElement("div");
        popup.classList.add("popup");

        if (isCorrect) {
            popup.innerHTML = 'Yay! Correct Answer';
            popup.classList.add('correct-answer');
			 playSound('soundsuccess');
        } else {
            
            popup.innerHTML = `Oops! Wrong Answer`;
            popup.classList.add('wrong-answer');
			 playSound('soundfail');
        }

        
        document.getElementById("feedback-container").appendChild(popup);

        // Remove the popup after 3 seconds 
        setTimeout(() => {
            popup.remove();
        }, 3000);

        const gameImage = document.getElementById('image');
        gameImage.src = questionImageURL;

        previousSolution = correctSolution;

        displayedNumber.textContent = '';
    }

	const keypad = document.getElementById("keypad");

for (let i = 0; i <= 9; i++) {
    const button = document.createElement("button");
    button.classList.add("number-button");
    button.style.backgroundImage = `url('images/${i}.png')`;
    
        button.dataset.number = i;
        keypad.appendChild(button);
    
    
    button.addEventListener("click", function() {
        const number = button.getAttribute("data-number");
        displayedNumber.textContent = number;
        playSound('soundclick');
    });

    keypad.appendChild(button);
}




    numberButtons.forEach(button => {
        button.addEventListener("click", function() {
            const number = button.getAttribute("data-number");
            displayedNumber.textContent = number;
			 playSound('soundclick');
        });
    });

    enterButton.addEventListener("click", function() {
        const initialApiUrl = 'https://marcconrad.com/uob/tomato/api.php';
		 playSound('soundclick');

        fetch(initialApiUrl)
            .then((response) => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json(); 
            })
            .then((data) => {
			
                updateGame(data);
            })
            .catch((error) => {
                console.error('Error:', error);
            });
    });
skipButton.addEventListener("click", function() {
    
    const skipApiUrl = 'https://marcconrad.com/uob/tomato/api.php';
 playSound('soundclick');
    fetch(skipApiUrl)
        .then((response) => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json(); 
        })
        .then((data) => {
            const questionImageURL = data.question;

            const gameImage = document.getElementById('image');
            gameImage.src = questionImageURL;

            displayedNumber.textContent = '';
        })
        .catch((error) => {
            console.error('Error:', error);
        });
});
	
	 restartBtn.addEventListener('click', function() {
		 
		  playSound('soundclick');
		 setTimeout(() => {
            window.location.href = 'gameview.php';
        }, 500);
		 
    });

    homeBtn.addEventListener('click', function() {
		
		playSound('soundclick');
		setTimeout(() => {
            window.location.href = 'home.php';
        }, 500);
		 
    });

    leaderboardBtn.addEventListener('click', function() {
		fetchLeaderboardData();
		 playSound('soundclick');
        
    });

    
	


        
    });
		
</script>

	

</body>
</html> 