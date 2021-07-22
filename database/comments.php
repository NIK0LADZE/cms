<?php
require_once("conn.php");

class Comments extends Connection {
    public $comments;
    public $count;

    /* Opening Database Connection */
    function __construct() {
        $this->openConn();
    }

    // This method counts total amount of comments
    function countComments() {
        $sql = "SELECT COUNT(id) as count FROM comments";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $count = $stmt->fetch(PDO::FETCH_ASSOC);
        echo $this->count = $count["count"];
    }

    // This method displays comments for posts
    function display($post_id) {
        $sql = "SELECT users.image as photo, comments.comment_author as author, comments.comment_content as content, comments.comment_date as date 
        FROM comments LEFT JOIN users ON comments.comment_author = users.username WHERE post_id=? ORDER BY date DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$post_id]);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        return $this->comments = $stmt->fetchAll();
    }

    // This method adds new comments
    function insert($post_id, $author, $comment) {
        $sql = "INSERT INTO comments(post_id, comment_author, comment_content) ";
        $sql .= "VALUES(?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$post_id, $author, $comment]);
    }

    function __destruct() {
        $this->conn = 0;
    }
}