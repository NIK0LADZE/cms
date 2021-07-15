<?php require_once("includes/header.php"); ?>

    <!-- Page Content -->
    <div class="container">

        <div class="row">

            <!-- Blog Entries Column -->
            <div class="col-md-8">

                <h1 class="page-header">
                    Page Heading
                    <small>Secondary Text</small>
                </h1>

                <!-- Blog Post -->
                <?php
                openConn();
                $posts = "SELECT * FROM posts ORDER BY post_date DESC";
                foreach ($conn->query($posts) as $post) { ?>
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
                <ul class="pager">
                    <li class="previous">
                        <a href="#">&larr; Older</a>
                    </li>
                    <li class="next">
                        <a href="#">Newer &rarr;</a>
                    </li>
                </ul>

            </div>

            <!-- Blog Sidebar Widgets Column -->
            <?php require_once("includes/sidebar.php"); ?>

        </div>
        <!-- /.row -->

        <hr>

        <!-- Footer -->
        <?php require_once("includes/footer.php"); ?>