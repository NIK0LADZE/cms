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
                    $postPerPage = 10;
                    $author = $_GET["author"];
                    $countSQL = "SELECT COUNT(post_id) as count FROM posts WHERE post_author='$author'";
                    $stmt = $conn->prepare($countSQL);
                    $stmt->execute();
                    $postCount = $stmt->fetch(PDO::FETCH_ASSOC);
                    $postCount = $postCount["count"];
                    $pagerCount = ceil($postCount / $postPerPage); 
                    if(isset($_GET["page"])) {
                        if($_GET["page"] == 1) {
                            $page = 1;
                            $startPostsFrom = 0;
                        } elseif($_GET["page"] == 0 || $_GET["page"] > $pagerCount || empty($_GET["page"])) {
                            echo "<h1>This page doesn't exist.</h1>";
                            return 0;
                        } else {
                            $page = $_GET["page"];
                            $startPostsFrom = ($page - 1) * $postPerPage;
                        }
                    } else {
                        $page = 1;
                        $startPostsFrom = 0;
                    }
                    $authors = "SELECT username as author FROM users WHERE username='$author' LIMIT 1";
                    $authorCount = $conn->query($authors)->rowCount();
                    $posts = "SELECT * FROM posts WHERE post_author='$author' ORDER BY post_date DESC LIMIT {$startPostsFrom}, {$postPerPage}";
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
                        <?php pager("author.php?author=".$author."&", $page, $pagerCount);?>
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