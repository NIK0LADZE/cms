<?php require_once("includes/header.php"); ?>

    <!-- Page Content -->
    <div class="container">

        <div class="row">

            <!-- Blog Entries Column -->
            <div class="col-md-8">

                <!-- Blog Post -->
                <?php
                if(isset($_GET["category_title"])) {
                    openConn();
                    $cat_title = $_GET["category_title"];
                    $categories = "SELECT * FROM categories WHERE cat_title='$cat_title' LIMIT 1";
                    $category = $conn->query($categories)->rowCount();
                    $posts = "SELECT * FROM posts WHERE post_category_id='$cat_title' ORDER BY post_date DESC";
                    $count = $conn->query($posts)->rowCount();
                    if($count == 0) {
                        if($category == 0) {
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
                        <ul class="pager">
                            <li class="previous">
                                <a href="#">&larr; Older</a>
                            </li>
                            <li class="next">
                                <a href="#">Newer &rarr;</a>
                            </li>
                        </ul>
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