<?php
namespace Posts;
require_once("conn.php");
use Connection;
use PDO;

class Posts extends Connection {
    public $post;
    public $postPerPage = 10;
    public $startPostsFrom;
    public $postCount;
    public $pagerCount;
    public $page;

    function __construct() {
        $this->openConn();
    }

    function display($startPostsFrom = 0, $postPerPage = 1, $content = 1) {
        $sql = "SELECT post_id as id, post_title as title, post_author as author, post_category as category,
        post_image as image, post_views as views, post_content as content, post_date as date FROM posts
        WHERE ";
        if(isset($_GET["post_id"])) {
            $content = $_GET["post_id"];
            $sql .= "post_id=?";
        } else {
            $sql .= "1=?";
        }
        $sql .= " ORDER BY date DESC LIMIT {$startPostsFrom}, {$postPerPage}";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$content]);
        return $this->post = $stmt->fetchAll();
    }

    function updateViews($views, $id) {
        $viewSQL = "UPDATE posts SET post_views=? WHERE post_id=?";
        $updateViews = $this->conn->prepare($viewSQL);
        $updateViews->execute([$views, $id]);
    }

    function perPage($content = 1) {
        $countSQL = "SELECT COUNT(post_id) as count FROM posts WHERE ".$content;
        if(isset($_GET["keyword"])) {
            $keyword = explode(" ", $_GET["keyword"]);
            foreach ($keyword as $key => $value) {
                if($key > 0) {
                    $countSQL .= "OR";
                }
                $countSQL .= "'%".$value."%'";
            } 
        }
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