<?php
class Connection {
    protected $conn;
    protected $servername = "localhost";
    protected $username = "root";
    protected $password = "";

    protected function openConn() {
        try {
            $this->conn = new PDO("mysql:host=$this->servername;dbname=cms", $this->username, $this->password);
            // set the PDO error mode to exception
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
            return 0;
        }
    }
}