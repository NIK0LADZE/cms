<?php require_once("includes/header.php"); ?>

<body>

    <div id="wrapper">

        <!-- Navigation -->
        <?php require_once("includes/nav.php"); ?>

        <div id="page-wrapper">

            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                            Posts Page
                            <small><?php echo $_SESSION["username"];?></small>
                        </h1>
                        <ol class="breadcrumb">
                            <li>
                                <i class="fa fa-dashboard"></i>  <a href="index.php">Dashboard</a>
                            </li>
                            <li class="active">
                                <i class="fa fa-file"></i> Comments
                            </li>
                        </ol>
                        <?php 
                        if(isset($_POST["delete_comment"])) {
                            delete("comments", $_POST["comment_id"]);
                        }
                        ?>
                        <?php 
                        openConn();
                        $postPerPage = 10;
                        $countSQL = "SELECT COUNT(id) as count FROM comments";
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
                        } ?>
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Author</th>
                                    <th>On Post</th>
                                    <th>Content</th>
                                    <th colspan=3>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php displayComments($startPostsFrom, $postPerPage);?>
                            </tbody>
                        </table>
                        <!-- Pager  -->
                        <?php pager("comments.php?", $page, $pagerCount);?>
                    </div>
                </div>
                <!-- /.row -->

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- jQuery -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

</body>

</html>
