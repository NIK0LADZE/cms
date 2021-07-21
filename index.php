<?php require_once("includes/header.php"); ?>

    <!-- Page Content -->
    <div class="container">

        <div class="row">

            <!-- Blog Entries Column -->
            <div class="col-md-8">

                <!-- Blog Post -->
                <?php
                require_once("database/posts.php");
                $posts = new Posts\Posts(); 
                $postCount = $posts->perPage();
                if (isset($posts->startPostsFrom)) {
                    $posts->display($posts->startPostsFrom, $posts->postPerPage);
                    ?>
                    <h1 class="page-header">
                        Page Heading
                        <small>Secondary Text</small>
                    </h1>
                    <?php require_once("includes/posts.php"); ?>
                    <hr>

                    <!-- Pager -->
                    <?php pager("index.php?", $posts->page, $posts->pagerCount);
                } ?>

            </div>

            <!-- Blog Sidebar Widgets Column -->
            <?php require_once("includes/sidebar.php"); ?>

        </div>
        <!-- /.row -->

        <hr>

        <!-- Footer -->
        <?php require_once("includes/footer.php"); ?>