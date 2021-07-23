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
                            Categories Page
                            <small><?php echo $_SESSION["username"];?></small>
                        </h1>
                        <ol class="breadcrumb">
                            <li>
                                <i class="fa fa-dashboard"></i>  <a href="index.php">Dashboard</a>
                            </li>
                            <li class="active">
                                <i class="fa fa-file"></i> Categories
                            </li>
                        </ol>
                        <?php if($_SESSION["role"] === "Admin" || $_SESSION["role"] === "Moderator") { ?>
                            <div class="col-xs-6">
                                <form action="" method="post">
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="cat_title">
                                    </div>
                                    <div class="form-group">
                                        <input class="btn btn-primary" type="submit" name="addcat" value="Add Category">
                                    </div>
                                </form>
                            </div>
                        <?php 
                        $size = 6;
                        } else {
                          $size = 12;  
                        } ?>
                        <div class="col-xs-<?=$size?>">
                                    
                            <table class="table table-bordered table-hover cat-table">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th colspan=3>Category</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    require_once($_SERVER['DOCUMENT_ROOT']."/cms/database/categories.php");
                                    $categories = new Categories;
                                    $categories->insert();
                                    $categories->update();
                                    $categories->delete();
                                    $categories->table(); 
                                    ?>
                                </tbody>
                            </table>
                        </div>
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
