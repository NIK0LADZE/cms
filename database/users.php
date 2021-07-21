<?php
require_once("conn.php");

Class Users extends Connection {
    /* Opening Database Connection */

    function __construct() {
        $this->openConn();
    }

    /* This method checks if category really exists */

    function count($username) {
        $sql = "SELECT COUNT(cat_title) as count FROM categories WHERE cat_title=? LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$username]);
        $this->count = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->count = $this->count["count"];
    }

    /* Closing Database Connection */

    function __destruct() {
        $this->conn = 0;
    }
}