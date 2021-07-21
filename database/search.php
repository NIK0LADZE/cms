<?php 
namespace Search;
require_once("conn.php");
use Connection;
use PDO;

class Results extends Connection {
    public $post;
    
    function __construct($startPostsFrom, $postPerPage) {
        $this->openConn();
        if(!isset($_GET["keyword"])) {
            return header("Location: ../");
        } else {
            $keyword = explode(" ", $_GET["keyword"]);
            $sql = "SELECT post_id as id, post_title as title, post_author as author, post_category as category,
            post_image as image, post_views as views, post_content as content, post_date as date FROM posts WHERE post_tags LIKE ";
            foreach ($keyword as $key => $value) {
                if($key > 0) {
                    $sql .= "OR";
                }
                $sql .= "'%".$value."%'";
            } 
            $sql .= " ORDER BY post_date DESC LIMIT {$startPostsFrom}, {$postPerPage}";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $this->post = $stmt->fetchAll();
        }
    }

    function __destruct() {
        $this->conn = 0;
    }
}