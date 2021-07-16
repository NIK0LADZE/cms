<?php require_once("includes/header.php"); ?>

    <!-- Page Content -->
    <div class="container">

        <div class="row">

            <!-- Blog Entries Column -->
            <div class="col-md-8">

                <!-- Blog Post -->
                <?php
                openConn();
                $postPerPage = 10;
                $countSQL = "SELECT COUNT(post_id) as count FROM posts";
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
                $posts = "SELECT * FROM posts ORDER BY post_date DESC LIMIT {$startPostsFrom}, {$postPerPage}"; ?>
                <h1 class="page-header">
                    Page Heading
                    <small>Secondary Text</small>
                </h1>
                <?php foreach ($conn->query($posts) as $post) { ?>
                <h2>
                    <a href="post.php?post_id=<?php echo $post["post_id"];?>"><?php echo $post["post_title"];?></a>
                </h2>
                <p class="lead">
                    by <a href="/cms/author.php?author=<?=$post["post_author"]?>"><?php echo $post["post_author"];?></a>
                </p>
                <p><span class="glyphicon glyphicon-time"></span> Posted on <?php echo date("F d, Y \a\\t H:i A", strtotime($post["post_date"]));?></p>
                <hr>
                <img class="img-responsive" src="/cms/uploads/<?php echo $post["post_image"];?>" alt="Post Image">
                <hr>
                <p><?php echo substr($post["post_content"], 0, 200)."...";?></p>
                <a class="btn btn-primary" href="post.php?post_id=<?php echo $post["post_id"];?>">Read More <span class="glyphicon glyphicon-chevron-right"></span></a>
                <?php } 
                $conn = 0;
                ?>
                <hr>

                <!-- Pager -->
                <?php pager("index.php?", $page, $pagerCount);?>

            </div>

            <!-- Blog Sidebar Widgets Column -->
            <?php require_once("includes/sidebar.php"); ?>

        </div>
        <!-- /.row -->

        <hr>

        <!-- Footer -->
        <?php require_once("includes/footer.php"); ?>