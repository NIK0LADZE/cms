<?php
require_once("conn.php");

Class Users extends Connection {
    /* Opening Database Connection */
    function __construct() {
        $this->openConn();
    }

    /* This method checks if user really exists */
    function count($username) {
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