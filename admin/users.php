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
                            Users Page
                            <small><?php echo $_SESSION["username"];?></small>
                        </h1>
                        <ol class="breadcrumb">
                            <li>
                                <i class="fa fa-dashboard"></i>  <a href="index.php">Dashboard</a>
                            </li>
                            <li class="active">
                                <i class="fa fa-file"></i> Users
                            </li>
                        </ol>
                        <?php 
                        require_once($_SERVER['DOCUMENT_ROOT']."/cms/database/users.php");
                        $users = new Users();
                        $users->perPage();
                        $users->setRole();
                        $users->delete();
                        if(isset($users->startPostsFrom)) { ?>
                            <form action="" method="post">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>User</th>
                                            <th>First name</th>
                                            <th>Last name</th>
                                            <th>Date of birth</th>
                                            <th>Email</th>
                                            <th style="width: 0;">Image</th>
                                            <th colspan=3>Role</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $users->display(); ?>
                                    </tbody>
                                </table>
                            </form>
                            <!-- Pager  -->
                            <?php pager("users.php?", $users->page, $users->pagerCount);
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
