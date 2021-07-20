<?php require_once("includes/header.php"); ?>

    <!-- Page Content -->
    <div class="container">

        <div class="row">

            <!-- Blog Entries Column -->
            <div class="col-md-8">

                <!-- Blog Post -->
                <?php
                require_once("database/posts.php");
                $postCount = new Posts\PostPerPage(1);
                if (isset($postCount->startPostsFrom)) {
                    $posts = new Posts\Display(1, $postCount->startPostsFrom, $postCount->postPerPage); ?>
                    <h1 class="page-header">
                        Page Heading
                        <small>Secondary Text</small>
                    </h1>
                    <?php require_once("includes/posts.php"); ?>
                    <hr>

                    <!-- Pager -->
                    <?php pager("index.php?", $postCount->page, $postCount->pagerCount);
                } ?>

            </div>

            <!-- Blog Sidebar Widgets Column -->
            <?php require_once("includes/sidebar.php"); ?>

        </div>
        <!-- /.row -->

        <hr>

        <!-- Footer -->
        <?php require_once("includes/footer.php"); ?>