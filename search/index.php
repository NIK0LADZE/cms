<?php require_once($_SERVER['DOCUMENT_ROOT']."/cms/includes/header.php"); ?>

    <!-- Page Content -->
    <div class="container">

        <div class="row">

            <!-- Blog Entries Column -->
            <div class="col-md-8">

                <!-- First Blog Post -->
                <?php search($_GET["keyword"]);?>

            </div>

            <!-- Blog Sidebar Widgets Column -->
            <?php require_once("../includes/sidebar.php"); ?>

        </div>
        <!-- /.row -->

        <hr>

<?php require_once("../includes/footer.php"); ?>