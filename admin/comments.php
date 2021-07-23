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
                        require_once($_SERVER['DOCUMENT_ROOT']."/cms/database/comments.php");
                        $comments = new Comments();
                        $comments->delete();
                        $comments->perPage();
                        if(isset($comments->startFrom)) { ?>
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
                                    <?php $comments->table(); ?>
                                </tbody>
                            </table>
                            <!-- Pager  -->
                            <?php pager("comments.php?", $comments->page, $comments->pagerCount);
                        } ?>
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
