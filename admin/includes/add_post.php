<?php addPost($_POST, $_FILES); ?>
<form action="" method="post" enctype="multipart/form-data">
    <div class="form-group">
        <label for="title">Title</label>
        <input type="text" class="form-control" name="title" required>
    </div>
    <div class="form-group">
        <label for="category">Category</label>
        <select name="category" class="form-control">
            <?php displayOptions("null"); ?>
        </select>
    </div>
    <div class="form-group">
        <label for="status">Status</label>
        <input type="text" class="form-control" name="status">
    </div>
    <div class="form-group">
        <label for="image">Image</label>
        <input type="file" name="image">
    </div>
    <div class="form-group">
        <label for="tags">Tags</label>
        <input type="text" class="form-control" name="tags" required>
    </div>
    <div class="form-group">
        <label for="content">Content</label>
        <textarea name="content" id="" cols="30" rows="10" class="form-control" required></textarea>
    </div>
    <div class="form-group">
        <input class="btn btn-primary" type="submit" name="add_post" value="Publish Post">
    </div>
</form>