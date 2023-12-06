<?php
session_start();

class Database {
    private $conn;

    public function __construct($servername, $user, $password, $dbname) {
        $this->conn = new mysqli($servername, $user, $password, $dbname);

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function getConnection() {
        return $this->conn;
    }
}

class User {
    private $username;

    public function __construct($username) {
        $this->username = $username;
    }

    public function getUsername() {
        return $this->username;
    }
}
?>
