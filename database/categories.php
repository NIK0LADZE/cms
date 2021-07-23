<?php
require_once("conn.php");

Class Categories extends Connection {
    public $categories;

    /* Opening Database Connection */
    function __construct() {
        $this->openConn();
    }

    // This method counts total amount of comments
    function count() {
        $sql = "SELECT COUNT(id) as count FROM categories";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $count = $stmt->fetch(PDO::FETCH_ASSOC);
        echo $count["count"];
    }

    /* This method checks if category really exists */
    function checkCategory($cat_title) {
        $sql = "SELECT COUNT(cat_title) as count FROM categories WHERE cat_title=? LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$cat_title]);
        $count = $stmt->fetch(PDO::FETCH_ASSOC);
        return $count["count"];
    }

    /* This method adds new categories */
    function insert() {
        if(isset($_POST["addcat"])) {
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                if(!empty($_POST["cat_title"])) {
                    $cat_title = $_POST["cat_title"];
                    $count = $this->checkCategory($cat_title);
                    if($count == 1) {
                        echo "<p style='color: red; margin: 0;'>This category already exists</p>";
                    } else {
                        $sql = "INSERT INTO categories(cat_title) VALUES(?)";
                        $stmt = $this->conn->prepare($sql);
                        $stmt->execute([$cat_title]);
                    }
                } else {
                    echo "<p style='color: red; margin: 0;'>This field should not be empty</p>";
                }
            }
        }
    }

    function update() {
        if(isset($_POST["new"])) {
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $oldCatTitle = $_POST["old"];
                $newCatTitle = $_POST["new"];
                if(!empty($newCatTitle)) {
                    $updateCat = "UPDATE categories SET cat_title=? WHERE cat_title=?";
                    $updateCat = $this->conn->prepare($updateCat);
                    $updateCat->execute([$newCatTitle, $oldCatTitle]);
                    $updatePosts = "UPDATE posts SET post_category=? WHERE post_category=?";
                    $updatePosts = $this->conn->prepare($updatePosts);
                    $updatePosts->execute([$newCatTitle, $oldCatTitle]);
                } else {
                    echo "<p style='text-align: center; color: red; margin: 0;'>New value shoud not be empty</p>";
                }
            }
        }
    }

    /* This method displays categories */
    function display() {
        $sql = "SELECT id, cat_title as title FROM categories";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $this->categories = $stmt->fetchAll();
    }

    /* This method deletes user */
    function delete() {
        if(isset($_POST["delete_category"])) {
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $id = $_POST["cat_id"];
                $sql = "DELETE FROM categories where id=?";
                $stmt = $this->conn->prepare($sql);
                $stmt->execute([$id]);
            }
        }
    }

    /* Closing Database Connection */
    function __destruct() {
        $this->conn = 0;
    }
}