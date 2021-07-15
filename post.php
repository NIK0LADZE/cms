<?php require_once("includes/header.php"); ?>

    <!-- Page Content -->
    <div class="container">

        <div class="row">

            <!-- Blog Post Content Column -->
            <div class="col-lg-8">

                <!-- Blog Post -->
                <?php 
                if(isset($_GET["post_id"])) {
                    openConn();
                    $id = $_GET["post_id"];
                    $sql = "SELECT post_author as author, post_title as title, post_image as image, post_views as views, post_date as date, post_content as content FROM posts WHERE post_id='$id'";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute();
                    $post = $stmt->fetch(PDO::FETCH_ASSOC);
                    $count = $conn->query($sql)->rowCount();
                    $views = $post["views"];
                    $views++;
                    $viewSQL = "UPDATE posts SET post_views={$views} WHERE post_id='$id'";
                    $updateViews = $conn->prepare($viewSQL);
                    $updateViews->execute();
                    $conn = 0;
                    if($count == 1) { ?>
                        <!-- Title -->
                        <h1><?php echo $post["title"];?></h1>
        
                        <!-- Author -->
                        <p class="lead">
                            by <a href="/cms/author.php?author=<?=$post["author"]?>"><?=$post["author"]?></a>
                        </p>
        
                        <hr>
        
                        <!-- Date/Time -->
                        <p><span class="glyphicon glyphicon-time"></span> Posted on <?php echo date("F d, Y \a\\t H:i A", strtotime($post["date"]));?></p>
        
                        <hr>
        
                        <!-- Preview Image -->
                        <img class="img-responsive" src="/cms/uploads/<?php echo $post["image"];?>" alt="">
        
                        <hr>
        
                        <!-- Post Content -->
                        <p><?php echo $post["content"];?></p>
        
                        <hr>
        
                        <!-- Blog Comments -->
        
                        <!-- Comments Form -->
                        <div class="well">
                            <h4>Leave a Comment:</h4>
                            <form action="actions/addComment.php" method="post">
                                <?php if(!isset($_SESSION["auth"])) { ?>
                                    <div class="form-group">
                                        <label for="comment_author">Author</label>
                                        <input name="comment_author" type="text" class="form-control" required>
                                    </div>
                                <?php } ?>
                                <div class="form-group">
                                    <?php if(!isset($_SESSION["auth"])) { ?>
                                        <label for="comment_content">Comment</label>
                                    <?php } ?>
                                    <textarea name="comment_content" style="resize: vertical; min-height: 100px;" class="form-control" rows="3" required></textarea>
                                </div>
                                <input type="hidden" name="post_id" value="<?php echo $_GET["post_id"];?>">
                                <button name="addcomment" type="submit" class="btn btn-primary">Submit</button>
                            </form>
                        </div>
        
                        <hr>
        
                        <!-- Comments -->
                        <?php 
                        openConn();
                        $post_id = $_GET["post_id"];
                        $sql = "SELECT users.image as photo, comments.comment_author as author, comments.comment_content as content, comments.comment_date as date 
                        FROM comments LEFT JOIN users ON comments.comment_author = users.username WHERE post_id={$post_id} ORDER BY date DESC";
                        $stmt = $conn->prepare($sql);
                        $stmt->execute();
                        $stmt->setFetchMode(PDO::FETCH_ASSOC);
                        $comments = $stmt->fetchAll();
                        $conn = 0;
                        foreach ($comments as $comment) { ?>
                        <div class="media">
                            <a class="pull-left" href="#">
                                <img class="media-object" width="64px" height="64px" src="/cms/uploads/users/<?php
                                if(isset($comment["photo"])) {
                                    echo $comment["photo"];
                                } else {
                                    echo "no-photo.png";
                                }
                                ?>" alt="User photo">
                            </a>
                            <div class="media-body">
                                <h4 class="media-heading"><?php echo $comment["author"];?>
                                    <small><?php echo date("F d, Y \a\\t H:i A", strtotime($comment["date"]));?></small>
                                </h4>
                                <?= $comment["content"];?>
                            </div>
                        </div>
                        <?php } ?>
                    <?php } else {
                        echo "<h1>This page doesn't exist.</h1>";
                    }
                } else {
                    echo "<h1>This page doesn't exist.</h1>";
                }
                ?>

            </div>

            <!-- Blog Sidebar Widgets Column -->
            <?php require_once("includes/sidebar.php"); ?>

        </div>
        <!-- /.row -->

        <hr>

        <!-- Footer -->
        <?php require_once("includes/footer.php"); ?>