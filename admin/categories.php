<?php 
require_once("includes/header.php"); 
require_once($_SERVER['DOCUMENT_ROOT']."/cms/database/categories.php");
$categories = new Categories;
$categories->insert();
$categories->update();
$categories->delete();
$categories->display();
?>

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
                                    foreach ($categories->array as $cat) { ?>
                                        <tr>
                                            <td><p><?php echo $cat['id'];?></p></td>
                                            <td id="title">
                                                <p id="<?php echo $cat['title'];?>"><a href="/cms/category.php?category_title=<?=$cat['title']?>"><?=$cat['title']?></a></p>
                                                <form action="" method="post">
                                                    <input type="hidden" name="old" value="<?php echo $cat['title'];?>">
                                                    <input type="hidden" name="new" class="edit" id="<?php echo $cat['title'].'_for_input';?>" value="<?php echo $cat['title'];?>">
                                                </form>
                                            </td>
                                            <?php if($_SESSION["role"] === "Admin" || $_SESSION["role"] === "Moderator") { ?>
                                                <td style="width: 10%; text-align: center;">
                                                    <button onclick="document.getElementById('<?php echo $cat['title'];?>').style.display = 'none'; document.getElementById('<?php echo $cat['title'];?>_for_input').setAttribute('type', 'text');" name="edit" type="submit"><i class="far fa-edit"></i> Edit</button>
                                                </td>
                                                <td style="width: 12%; text-align: center;">
                                                    <form id="delete" action="" method="post">
                                                        <input type="hidden" name="cat_id" value="<?php echo $cat['id'];?>">
                                                        <button name="delete_category" onclick="javascript: return confirm('Are you sure you want to delete this category?');" type="submit"><i class="far fa-trash-alt"></i> Delete</button>
                                                    </form>
                                                </td>
                                            <?php } ?>
                                        </tr>
                                    <?php } ?>
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
