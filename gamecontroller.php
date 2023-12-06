<?php
require_once 'model.php';

class GameController {
    private $db;
    private $user;

    public function __construct($db, $user) {
        $this->db = $db;
        $this->user = $user;
    }

    public function index() {
        
        include 'view.php';
    }
}


$servername = "localhost";
$user = "root";
$password = "";
$dbname = "tomato";

$db = new Database($servername, $user, $password, $dbname);

session_start();
// Check if the user is logged in, otherwise redirect to the login page
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}
$username = $_SESSION['username'];
// Create a new User instance
$user = new User($username);

$highscore = "SELECT username, MAX(player_score) as highest_score 
        FROM score 
        WHERE username = '$username'";

// Create a new GameController instance and pass the database and user
$gameController = new GameController($db, $user);

/
$gameController->index();
?>