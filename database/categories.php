<?php
namespace Categories;
require_once("conn.php");
use Connection;
use PDO;

class Count extends Connection {
    public $count;

    function __construct($cat_title) {
        $this->openConn();
        $sql = "SELECT COUNT(cat_title) as count FROM categories WHERE cat_title='$cat_title' LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $this->count = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->count = $this->count["count"];
    }

    function __destruct() {
        $this->conn = 0;
    }
}