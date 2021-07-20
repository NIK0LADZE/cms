<?php
namespace Comments;
require_once("conn.php");
use Connection;
use PDO;

class Display extends Connection {
    public $comments;

    function __construct($post_id) {
        $this->openConn();
        $sql = "SELECT users.image as photo, comments.comment_author as author, comments.comment_content as content, comments.comment_date as date 
        FROM comments LEFT JOIN users ON comments.comment_author = users.username WHERE post_id=? ORDER BY date DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$post_id]);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $this->comments = $stmt->fetchAll();
    }

    function __destruct() {
        $this->conn = 0;
    }
}

class Insert extends Connection {
    function __construct($post_id, $author, $comment) {
        $this->openConn();
        $sql = "INSERT INTO comments(post_id, comment_author, comment_content) ";
        $sql .= "VALUES(?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$post_id, $author, $comment]);
    }

    function __destruct() {
        $this->conn = 0;
    }
}