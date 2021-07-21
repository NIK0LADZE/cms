<?php require_once("includes/header.php"); ?>

    <!-- Page Content -->
    <div class="container">

        <div class="row">

            <!-- Blog Entries Column -->
            <div class="col-md-8">

                <!-- Blog Post -->
                <?php
                if(isset($_GET["author"])) {
                    require_once("database/posts.php");
                    require_once("database/count.php");
                    $author=$_GET["author"];
                    $postCount = new Posts\PostPerPage("post_author='$author'");
                    $authors = new Database\Count("posts", "post_author", "post_author='$author'");
                    $posts = new Posts\Display("post_author='$author'", $postCount->startPostsFrom, $postCount->postPerPage);
                    $count = count($posts->post);
                    
                    if($count == 0) {
                        if($authors->count == 0) {
                            echo "<h1>This page doesn't exist.</h1>";
                        } else {
                            echo "<h1>No posts yet.</h1>";
                        }
                    } else { ?>
                        <h1 class="page-header">
                            <?=$author?>
                        </h1>
                        <?php require_once("includes/posts.php"); ?>
                        <hr>

                        <!-- Pager -->
                        <?php pager("author.php?author=".$author."&", $postCount->page, $postCount->pagerCount);?>
                    <?php } ?>
                <?php } else {?>
                    <h1>This page doesn't exist.</h1>
                <?php } ?>
            </div>

            <!-- Blog Sidebar Widgets Column -->
            <?php require_once("includes/sidebar.php"); ?>

        </div>
        <!-- /.row -->

        <hr>

        <!-- Footer -->
        <?php require_once("includes/footer.php"); ?>