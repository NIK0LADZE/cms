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
                                <i class="fa fa-file"></i> Posts
                            </li>
                        </ol>
                        <?php 
                        if(!isset($_GET["action"])) {
                            echo "<h1>This page doesn't exist.</h1>";
                        } else {
                            switch([$_GET["action"], $_SESSION["role"]]) {
                                case ["view", "Subscriber"]:
                                case ["view", "Moderator"]:
                                case ["view", "Admin"]:
                                    require_once("includes/view_all_posts.php");
                                    break;
                                case ["add", "Moderator"]:
                                case ["add", "Admin"]:
                                    require_once("includes/add_post.php");
                                    break;
                                case ["add", "Subscriber"]:
                                    echo "<h1>You don't have access to this page.</h1>";
                                    break;
                                case ["edit", "Moderator"]:
                                case ["edit", "Admin"]:
                                    require_once("includes/edit_post.php");
                                    break;
                                case ["edit", "Subscriber"]:
                                    echo "<h1>You don't have access to this page.</h1>";
                                    break;
                                default:
                                    echo "<h1>This page doesn't exist.</h1>";
                            }
                        }
                        ?>
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
