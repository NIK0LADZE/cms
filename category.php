<?php require_once("includes/header.php"); ?>

    <!-- Page Content -->
    <div class="container">

        <div class="row">

            <!-- Blog Entries Column -->
            <div class="col-md-8">

                <!-- Blog Post -->
                <?php
                if(isset($_GET["category"])) {
                    require_once("database/posts.php");
                    require_once("database/categories.php");
                    $cat_title=$_GET["category"];
                    $posts = new Posts();
                    $posts->perPage($cat_title);
                    $posts->display($posts->startPostsFrom, $posts->postPerPage, $cat_title);
                    $count = count($posts->post);
                    
                    if($count == 0) {
                        // This object is set to check if category really exists in database
                        $category = new Categories();
                        if($category->count($cat_title) == 0) {
                            echo "<h1>This page doesn't exist.</h1>";
                        } else {
                            echo "<h1>No posts yet.</h1>";
                        }
                    } else { ?>
                        <h1 class="page-header">
                            <?=$cat_title?>
                        </h1>
                        <?php require_once("includes/posts.php"); ?>
                        <hr>

                        <!-- Pagination -->
                        <?php pager("category.php?category=".$cat_title."&", $posts->page, $posts->pagerCount);?>
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