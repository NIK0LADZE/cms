<?php require_once($_SERVER['DOCUMENT_ROOT']."/cms/includes/header.php"); ?>

    <!-- Page Content -->
    <div class="container">

        <div class="row">

            <!-- Blog Entries Column -->
            <div class="col-md-8">

                <!-- Search Results -->
                <?php 
                require_once("../database/posts.php");
                $posts = new Posts();
                $posts->perPage();
                $posts->search();
                $count = count($posts->array);
                
                if($count == 0) {
                    echo "<h2>NO RESULT!</h2>";
                } else { ?>
                    <h1 class="page-header">
                        Search
                        <small><?=$_GET["keyword"]?></small>
                    </h1>
                    <?php require_once("../includes/posts.php"); ?>
                    <hr>

                    <!-- Pagination -->
                    <?php pager("?keyword=".$_GET['keyword']."&", $posts->page, $posts->pagerCount);?>
                <?php } ?>

            </div>

            <!-- Blog Sidebar Widgets Column -->
            <?php require_once("../includes/sidebar.php"); ?>

        </div>
        <!-- /.row -->

        <hr>

<?php require_once("../includes/footer.php"); ?>