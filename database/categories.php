<?php
require_once("conn.php");

Class Categories extends Connection {
    public $categories;

    /* Opening Database Connection */
    function __construct() {
        $this->openConn();
    }

    // This method counts total amount of comments
    function countCategories() {
        $sql = "SELECT COUNT(id) as count FROM categories";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $count = $stmt->fetch(PDO::FETCH_ASSOC);
        echo $this->count = $count["count"];
    }

    /* This method checks if category really exists */
    function checkCategory($cat_title) {
        $sql = "SELECT COUNT(cat_title) as count FROM categories WHERE cat_title=? LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$cat_title]);
        $count = $stmt->fetch(PDO::FETCH_ASSOC);
        return $count["count"];
    }

    /* This method sends array in navigation */
    function display() {
        $sql = "SELECT cat_title as title FROM categories";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $this->categories = $stmt->fetchAll();
    }

    /* Closing Database Connection */
    function __destruct() {
        $this->conn = 0;
    }
}