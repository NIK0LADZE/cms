<?php
namespace Database;
require_once("conn.php");
use Connection;
use PDO;

class Count extends Connection {
    public $count;

    function __construct($table, $column, $content) {
        $this->openConn();
        $sql = "SELECT COUNT(".$column.") as count FROM ".$table." WHERE ".$column."='$content' LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $this->count = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->count = $this->count["count"];
    }

    function __destruct() {
        $this->conn = 0;
    }
}