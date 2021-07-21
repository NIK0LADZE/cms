<?php
require_once("conn.php");

class Posts extends Connection {
    /* Properties for Posts */
    public $post;
    public $postPerPage = 10;
    public $startPostsFrom;
    public $postCount;
    public $pagerCount;
    public $page;

    /* Opening Database Connection */

    function __construct() {
        $this->openConn();
    }

    /* This method displays posts */

    function display($startPostsFrom = 0, $postPerPage = 1, $content = 1) {
        $sql = "SELECT post_id as id, post_title as title, post_author as author, post_category as category,
        post_image as image, post_views as views, post_content as content, post_date as date FROM posts
        WHERE ";
        // Displays post on post page
        if(isset($_GET["post_id"])) {
            $content = $_GET["post_id"];
            $sql .= "post_id=?";
        // Displays posts on selected category page
        } elseif(isset($_GET["category"])) {
            $sql .= "post_category=?";
        // Displays posts on selected user page
        } elseif(isset($_GET["author"])) {
            $sql .= "post_author=?";
        // Displays posts on home page
        } else {
            $sql .= "1=?";
        }
        // Sets diapason for posts to be shown on each page
        $sql .= " ORDER BY date DESC LIMIT {$startPostsFrom}, {$postPerPage}";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$content]);
        return $this->post = $stmt->fetchAll();
    }

    /* This method counts how many times each post was viewed */

    function updateViews($views, $id) {
        $viewSQL = "UPDATE posts SET post_views=? WHERE post_id=?";
        $updateViews = $this->conn->prepare($viewSQL);
        $updateViews->execute([$views, $id]);
    }

    /* This method counts how many posts are on different pages and applies it to pagination */

    function perPage($content = 1) {
        $countSQL = "SELECT COUNT(post_id) as count FROM posts WHERE ";
        if(isset($_GET["keyword"])) {
            $countSQL .= "post_tags LIKE ";
            // Setting keywords to search in posts
            $keywords = explode(" ", $_GET["keyword"]);
            $keywords = "%".implode("% %", $keywords)."%";
            $keywords = explode(" ", $keywords);
            // Checking for each keyword
            for ($i = 0; $i < count($keywords); $i++) {
                if($i > 0) {
                    $countSQL .= "OR";
                }
                $countSQL .= "?";
            } 
        // Calculates amount of posts in selected category
        } elseif(isset($_GET["category"])) {
            $countSQL .= "post_category=?";
        // Calculates amount of posts by selected user
        } elseif(isset($_GET["author"])) {
            $countSQL .= "post_author=?";
        // Displays all posts for home page
        } else {
            $countSQL .= "1=?";
        }
        $stmt = $this->conn->prepare($countSQL);
        // This line only gets executed when user searches for posts
        if(isset($_GET["keyword"])) {
            $stmt->execute($keywords);
        // This line gets executed in any other occasions
        } else {
            $stmt->execute([$content]);
        }
        // Calculates diapason of posts which should be shown on each page
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

    /* Search method */

    function search($startPostsFrom, $postPerPage) {
        if(empty($_GET["keyword"])) {
            return header("Location: /cms/");
        } else {
            // Setting keywords to search in posts
            $keywords = explode(" ", $_GET["keyword"]);
            $keywords = "%".implode("% %", $keywords)."%";
            $keywords = explode(" ", $keywords);
            $sql = "SELECT post_id as id, post_title as title, post_author as author, post_category as category,
            post_image as image, post_views as views, post_content as content, post_date as date FROM posts WHERE post_tags LIKE ";
            // Checking for each keyword
            for ($i = 0; $i < count($keywords); $i++) {
                if($i > 0) {
                    $sql .= "OR";
                }
                $sql .= "?";
            } 
            $sql .= " ORDER BY post_date DESC LIMIT {$startPostsFrom}, {$postPerPage}";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($keywords);
            return $this->post = $stmt->fetchAll();
        }
    }

    /* Closing Database Connection */

    function __destruct() {
        $this->conn = 0;
    }
}