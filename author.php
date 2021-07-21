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
                    require_once("database/users.php");
                    $author = $_GET["author"];
                    $posts = new Posts();
                    $posts->perPage($author);
                    $posts->display($posts->startPostsFrom, $posts->postPerPage, $author);
                    $count = count($posts->post);
                    
                    if($count == 0) {
                        // This object is set to check if user really exists in database
                        $authors = new Users();
                        if($authors->count($author) == 0) {
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

                        <!-- Pagination -->
                        <?php pager("author.php?author=".$author."&", $posts->page, $posts->pagerCount);?>
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