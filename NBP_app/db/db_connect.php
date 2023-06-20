<?php

class DatabaseConnection {
    private static $instance;
    private $conn;

    private function __construct() {
        $servername = "localhost";
        $username = "user";
        $password = "pass";
        $dbname = "nbp_currency_app";

        $this->conn = new mysqli($servername, $username, $password, $dbname);

        if ($this->conn->connect_error) {
            throw new Exception("Connection failed: " . $this->conn->connect_error);
        }
    }

    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new DatabaseConnection();
        }

        return self::$instance;
    }

    public function getConnection() {
        return $this->conn;
    }

    public function closeConnection() {
        if ($this->conn) {
            $this->conn->close();
            $this->conn = null;
        }
    }
    
}
?>
