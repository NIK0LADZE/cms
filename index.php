<?php require_once("includes/header.php"); ?>

    <!-- Page Content -->
    <div class="container">

        <div class="row">

            <!-- Blog Entries Column -->
            <div class="col-md-8">

                <!-- Blog Post -->
                <?php
                require_once("database/posts.php");
                $postCount = new Posts\PostPerPage();
                if (isset($postCount->startPostsFrom)) {
                    $posts = new Posts\Display($postCount->startPostsFrom, $postCount->postPerPage); ?>
                    <h1 class="page-header">
                        Page Heading
                        <small>Secondary Text</small>
                    </h1>
                    <?php foreach ($posts->post as $post) { ?>
                    <h2>
                        <a href="post.php?post_id=<?=$post["id"]?>"><?=$post["title"]?></a>
                    </h2>
                    <p class="lead">
                        by <a href="/cms/author.php?author=<?=$post["author"]?>"><?=$post["author"]?></a>
                    </p>
                    <p><span class="glyphicon glyphicon-time"></span> Posted on <?=date("F d, Y \a\\t H:i A", strtotime($post["date"]))?></p>
                    <hr>
                    <img class="img-responsive" src="/cms/uploads/<?=$post["image"];?>" alt="Post Image">
                    <hr>
                    <p><?=substr($post["content"], 0, 200)."..."?></p>
                    <a class="btn btn-primary" href="post.php?post_id=<?=$post["id"];?>">Read More <span class="glyphicon glyphicon-chevron-right"></span></a>
                    <?php } 
                    ?>
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