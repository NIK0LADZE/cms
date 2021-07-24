<?php require_once("includes/header.php"); ?>

    <!-- Page Content -->
    <div class="container">

        <div class="row">

            <!-- Blog Entries Column -->
            <div class="col-md-8">

                <!-- Blog Post -->
                <?php
                if(isset($_GET["cat_id"])) {
                    require_once("database/posts.php");
                    require_once("database/categories.php");
                    $cat_id = $_GET["cat_id"];
                    $posts = new Posts();
                    $category = new Categories();
                    $posts->perPage($cat_id);
                    $posts->display($cat_id);
                    $count = count($posts->array);
                    
                    if($count == 0) {
                        // This object is set to check if category really exists in database
                        $category->display();
                        if($category->check($cat_id) == 0) {
                            echo "<h1>This page doesn't exist.</h1>";
                        } else {
                            echo "<h1>No posts yet.</h1>";
                        }
                    } else { ?>
                        <h1 class="page-header">
                            <?php 
                            $category->title($_GET["cat_id"]);
                            echo $category->title;
                            ?>
                        </h1>
                        <?php require_once("includes/posts.php"); ?>
                        <hr>

                        <!-- Pagination -->
                        <?php pager("category.php?cat_id=".$cat_id."&", $posts->page, $posts->pagerCount);?>
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