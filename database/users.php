<?php
require_once("conn.php");

Class Users extends Connection {
    /* Opening Database Connection */
    function __construct() {
        $this->openConn();
    }

    // This method counts total amount of comments
    function countUsers() {
        $sql = "SELECT COUNT(id) as count FROM users";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $count = $stmt->fetch(PDO::FETCH_ASSOC);
        echo $this->count = $count["count"];
    }

    /* This method checks if user really exists */
    function checkUser($username) {
        $sql = "SELECT COUNT(username) as count FROM users WHERE username=? LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$username]);
        $count = $stmt->fetch(PDO::FETCH_ASSOC);
        return $count["count"];
    }

    /* Closing Database Connection */
    function __destruct() {
        $this->conn = 0;
    }
}