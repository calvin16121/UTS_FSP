<?php
class classDB{
    protected $mysqli;
    
    public function __construct() {
        // Only define constants if they don't exist yet
        if (!defined("SERVER_NAME")) define("SERVER_NAME", "localhost");
        if (!defined("USER_NAME")) define("USER_NAME", "root");
        if (!defined("PASSWORD")) define("PASSWORD", "");
        if (!defined("DB_NAME")) define("DB_NAME", "fullstack");
        
        // Create the database connection
        $this->mysqli = new mysqli(SERVER_NAME, USER_NAME, PASSWORD, DB_NAME);
        
        // Add error handling
        if ($this->mysqli->connect_error) {
            die("Connection failed: " . $this->mysqli->connect_error);
        }
    }
}
?>