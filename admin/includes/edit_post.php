<?php 
require_once("../database/posts.php");
$posts = new Posts();
$posts->edit();
$posts->data($_GET["post_id"]);
if(empty($posts->data)) {
    echo "<h1>This page doesn't exist.</h1>";
    return 0;
}
?>
<form action="" method="post" enctype="multipart/form-data">
    <div class="form-group">
        <label for="title">Title</label>
        <input type="text" class="form-control" name="title" value="<?=$posts->data["title"];?>" required>
    </div>
    <div class="form-group">
        <label for="category">Category</label>
        <select name="category" class="form-control">
            <?php $posts->options();?>
        </select>
    </div>
    <div class="form-group">
        <label for="image">Image</label><br>
        <img width="100" src="/cms/uploads/<?=$posts->data["image"];?>" alt=""><br><br>
        <input type="file" name="image">
    </div>
    <div class="form-group">
        <label for="tags">Tags</label>
        <input type="text" class="form-control" name="tags" value="<?=$posts->data["tags"];?>" required>
    </div>
    <div class="form-group">
        <label for="content">Content</label>
        <textarea name="content" id="" cols="30" rows="10" class="form-control" required><?=$posts->data["content"];?></textarea>
    </div>
    <div class="form-group">
        <input class="btn btn-primary" type="submit" name="add_post" value="Publish Post">
    </div>
</form>