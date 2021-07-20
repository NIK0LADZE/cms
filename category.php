<?php require_once("includes/header.php"); ?>

    <!-- Page Content -->
    <div class="container">

        <div class="row">

            <!-- Blog Entries Column -->
            <div class="col-md-8">

                <!-- Blog Post -->
                <?php
                if(isset($_GET["category_title"])) {
                    require_once("database/posts.php");
                    require_once("database/categories.php");
                    $cat_title=$_GET["category_title"];
                    $postCount = new Posts\PostPerPage("post_category='$cat_title'");
                    $category = new Categories\Count($cat_title);
                    $posts = new Posts\Display("post_category='$cat_title'", $postCount->startPostsFrom, $postCount->postPerPage);
                    $count = count($posts->post);

                    if($count == 0) {
                        if($category->count == 0) {
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

                        <!-- Pager -->
                        <?php pager("category.php?category_title=".$cat_title."&", $postCount->page, $postCount->pagerCount);?>
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