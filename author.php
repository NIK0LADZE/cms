<?php require_once("includes/header.php"); ?>

    <!-- Page Content -->
    <div class="container">

        <div class="row">

            <!-- Blog Entries Column -->
            <div class="col-md-8">

                <!-- Blog Post -->
                <?php
                if(isset($_GET["user_id"])) {
                    require_once("database/posts.php");
                    require_once("database/users.php");
                    $author_id = $_GET["user_id"];
                    $posts = new Posts();
                    $author = new Users();
                    $posts->perPage($author_id);
                    $posts->display($author_id);
                    $count = count($posts->array);
                    
                    if($count == 0) {
                        // This object is set to check if user really exists in database
                        if($author->check($author_id) == 0) {
                            echo "<h1>This page doesn't exist.</h1>";
                        } else {
                            echo "<h1>No posts yet.</h1>";
                        }
                    } else { ?>
                        <h1 class="page-header">
                            <?php 
                            $author->title($_GET["user_id"]);
                            echo $author->title;
                            ?>
                        </h1>
                        <?php require_once("includes/posts.php"); ?>
                        <hr>

                        <!-- Pagination -->
                        <?php pager("author.php?user_id=".$author_id."&", $posts->page, $posts->pagerCount);?>
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