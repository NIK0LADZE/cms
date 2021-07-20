<?php
namespace Posts;
require_once("conn.php");
use Connection;
use PDO;

class PostPerPage extends Connection {
    public $postPerPage = 10;
    public $startPostsFrom;
    public $postCount;
    public $pagerCount;
    public $page;

    function __construct() {
        $this->openConn();
        $countSQL = "SELECT COUNT(post_id) as count FROM posts";
        $stmt = $this->conn->prepare($countSQL);
        $stmt->execute();
        $this->postCount = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->postCount = $this->postCount["count"];
        $this->pagerCount = ceil($this->postCount / $this->postPerPage); 
        if(isset($_GET["page"])) {
            if($_GET["page"] == 1) {
                $this->page = 1;
                $this->startPostsFrom = 0;
            } elseif($_GET["page"] == 0 || $_GET["page"] > $this->pagerCount || empty($_GET["page"])) {
                echo "<h1>This page doesn't exist.</h1>";
            } else {
                $this->page = $_GET["page"];
                $this->startPostsFrom = ($this->page - 1) * $this->postPerPage;
            }
        } else {
            $this->page = 1;
            $this->startPostsFrom = 0;
        }
    }

    function __destruct() {
        $this->conn = 0;
    }
}

class Display extends Connection {
    public $post;

    function __construct($startPostsFrom, $postPerPage) {
        $this->openConn();
        $sql = "SELECT post_id as id, post_title as title, post_author as author, post_category as category, post_image as image, post_content as content, post_date as date FROM posts
        ORDER BY date DESC LIMIT {$startPostsFrom}, {$postPerPage}";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $this->post = $stmt->fetchAll();
    }

    function __destruct() {
        $this->conn = 0;
    }
}