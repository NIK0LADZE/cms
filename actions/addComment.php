<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $author = $_SESSION["id"];
    $comment = $_POST['comment_content'];
    $post_id = $_POST["post_id"];

    require_once($_SERVER['DOCUMENT_ROOT']."/cms/database/comments.php");
    $comments = new Comments();
    $comments->insert($post_id, $author, $comment);
    $comments->display($post_id);
    foreach ($comments->array as $comment) { ?>
    <div class="media">
        <a class="pull-left" href="#">
            <img class="media-object" width="64px" height="64px" src="/cms/uploads/users/<?php
            if(isset($comment["photo"])) {
                echo $comment["photo"];
            } else {
                echo "no-photo.png";
            }
            ?>" alt="User photo">
        </a>
        <div class="media-body">
            <h4 class="media-heading"><?=$comment["author"]?>
                <small><?=date("F d, Y \a\\t H:i A", strtotime($comment["date"]))?></small>
            </h4>
            <?=$comment["content"];?>
        </div>
    </div>
<?php }
}
