<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT']."/cms/includes/db.php");

if(isset($_POST["addcomment"])) {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if(isset($_SESSION["username"])) {
            $author = $_SESSION["username"];
        } else {
            $author = $_POST["comment_author"];
        }
        $comment = $_POST['comment_content'];
        $post_id = $_POST["post_id"];

        openConn();
        $sql = "INSERT INTO comments(post_id, comment_author, comment_content) ";
        $sql .= "VALUES(:post_id, :author, :comment)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':post_id', $post_id);
        $stmt->bindParam(':author', $author);
        $stmt->bindParam(':comment', $comment);
        $stmt->execute();
        $conn = 0;
        header("Location: ".$_SERVER["HTTP_REFERER"]);
    }
}