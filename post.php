<?php require_once("includes/header.php"); ?>

    <!-- Page Content -->
    <div class="container">

        <div class="row">

            <!-- Blog Post Content Column -->
            <div class="col-lg-8">

                <!-- Blog Post -->
                <?php 
                if(isset($_GET["post_id"])) {
                    require_once("database/posts.php");
                    $id = $_GET["post_id"];
                    $posts = new Posts\Display("post_id='$id'");
                    $count = count($posts->post);
                    if($count == 1) { 
                        $post = $posts->post[0];
                        $views = $post["views"];
                        $views++;
                        $updateViews = new Posts\Views($views, $id);
                        ?>
                        <!-- Title -->
                        <h1><?=$post["title"]?></h1>
        
                        <!-- Author -->
                        <p class="lead">
                            by <a href="/cms/author.php?author=<?=$post["author"]?>"><?=$post["author"]?></a>
                        </p>
        
                        <hr>
        
                        <!-- Date/Time -->
                        <p><span class="glyphicon glyphicon-time"></span> Posted on <?=date("F d, Y \a\\t H:i A", strtotime($post["date"]))?></p>
        
                        <hr>
        
                        <!-- Preview Image -->
                        <img class="img-responsive" src="/cms/uploads/<?=$post["image"]?>" alt="">
        
                        <hr>
        
                        <!-- Post Content -->
                        <p><?=$post["content"]?></p>
        
                        <hr>
        
                        <!-- Blog Comments -->
        
                        <!-- Comments Form -->
                        <div class="well">
                            <h4>Leave a Comment:</h4>
                            <form action="actions/addComment.php" method="post">
                                <?php if(!isset($_SESSION["auth"])) { ?>
                                    <div class="form-group">
                                        <label for="comment_author">Author</label>
                                        <input name="comment_author" type="text" class="form-control" required>
                                    </div>
                                <?php } ?>
                                <div class="form-group">
                                    <?php if(!isset($_SESSION["auth"])) { ?>
                                        <label for="comment_content">Comment</label>
                                    <?php } ?>
                                    <textarea name="comment_content" style="resize: vertical; min-height: 100px;" class="form-control" rows="3" required></textarea>
                                </div>
                                <input type="hidden" name="post_id" value="<?=$_GET["post_id"]?>">
                                <button name="addcomment" type="submit" class="btn btn-primary">Submit</button>
                            </form>
                        </div>
        
                        <hr>
        
                        <!-- Comments -->
                        <?php 
                        require_once("database/comments.php");
                        $post_id = $_GET["post_id"];
                        $comments = new Comments\Display($post_id);
                        foreach ($comments->comments as $comment) { ?>
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
                        <?php } ?>
                    <?php } else {
                        echo "<h1>This page doesn't exist.</h1>";
                    }
                } else {
                    echo "<h1>This page doesn't exist.</h1>";
                }
                ?>

            </div>

            <!-- Blog Sidebar Widgets Column -->
            <?php require_once("includes/sidebar.php"); ?>

        </div>
        <!-- /.row -->

        <hr>

        <!-- Footer -->
        <?php require_once("includes/footer.php"); ?>