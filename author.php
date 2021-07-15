<?php require_once("includes/header.php"); ?>

    <!-- Page Content -->
    <div class="container">

        <div class="row">

            <!-- Blog Entries Column -->
            <div class="col-md-8">

                <!-- Blog Post -->
                <?php
                if(isset($_GET["author"])) {
                    openConn();
                    $author = $_GET["author"];
                    $authors = "SELECT username as author FROM users WHERE username='$author' LIMIT 1";
                    $authorCount = $conn->query($authors)->rowCount();
                    $posts = "SELECT * FROM posts WHERE post_author='$author' ORDER BY post_date DESC";
                    $count = $conn->query($posts)->rowCount();
                    if($count == 0) {
                        if($authorCount == 0) {
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