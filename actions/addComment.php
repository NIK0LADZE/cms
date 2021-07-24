<?php
session_start();

if(isset($_POST["addcomment"])) {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $author = $_SESSION["id"];
        $comment = $_POST['comment_content'];
        $post_id = $_POST["post_id"];

        require_once($_SERVER['DOCUMENT_ROOT']."/cms/database/comments.php");
        $comments = new Comments();
        $comments->insert($post_id, $author, $comment);
        header("Location: ".$_SERVER["HTTP_REFERER"]);
    }
}