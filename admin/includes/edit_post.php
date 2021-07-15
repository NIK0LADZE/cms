<?php 
editPost($_POST, $_FILES, $_GET["post_id"]);
openConn();
$post_id = $_GET["post_id"];
$editQuery = "SELECT * FROM posts WHERE post_id='$post_id'";
$result = $conn->query($editQuery)->fetch(PDO::FETCH_ASSOC); 
if(!$result) {
    echo "<h1>This page doesn't exist.</h1>";
    return 0;
}
?>
<form action="" method="post" enctype="multipart/form-data">
    <div class="form-group">
        <label for="title">Title</label>
        <input type="text" class="form-control" name="title" value="<?php echo $result["post_title"];?>" required>
    </div>
    <div class="form-group">
        <label for="category">Category</label>
        <select name="category" class="form-control">
            <?php displayOptions($result["post_category_id"]); ?>
        </select>
    </div>
    <div class="form-group">
        <label for="image">Image</label><br>
        <img width="100" src="/cms/uploads/<?php echo $result["post_image"];?>" alt=""><br><br>
        <input type="file" name="image">
    </div>
    <div class="form-group">
        <label for="tags">Tags</label>
        <input type="text" class="form-control" name="tags" value="<?php echo $result["post_tags"];?>" required>
    </div>
    <div class="form-group">
        <label for="content">Content</label>
        <textarea name="content" id="" cols="30" rows="10" class="form-control" required><?php echo $result["post_content"];?></textarea>
    </div>
    <div class="form-group">
        <input class="btn btn-primary" type="submit" name="add_post" value="Publish Post">
    </div>
</form>
<?php $conn = 0; ?>