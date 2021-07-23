<?php require_once($_SERVER['DOCUMENT_ROOT']."/cms/includes/pager.php"); ?>
<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <title>Blog Home - Start Bootstrap Template</title>

    <!-- Bootstrap Core CSS -->
    <link href="/cms/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="/cms/css/blog-home.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

    <!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand active" href="/cms/">Home</a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <?php 
                    require_once($_SERVER['DOCUMENT_ROOT']."/cms/database/categories.php");
                    $categories = new Categories();
                    $categories->display(); 
                    $categories = $categories->array; 
                    foreach ($categories as $key => $category) {
                        echo "<li><a href='/cms/category.php?category={$category['title']}'>{$category['title']}</a></li>";
                    }
                    if(isset($_SESSION["auth"])) {
                        if($_SESSION["auth"] === "true") { ?>
                            <li><a href='/cms/admin'>Admin</a></li>
                        <?php }
                    } ?>
                </ul>
                <?php
                    if(isset($_SESSION["auth"])) {
                        if($_SESSION["auth"] === "true") { ?>
                        <ul style="float: right;" class="nav navbar-nav">
                            <li><a href='/cms/actions/logout.php'>Logout</a></li>
                        </ul>
                        <?php }
                    } ?>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>
    <!-- Tracks navigation and sets active links -->
    <script>
        $(document).ready(function(){
            var activeurl = "<?=$_SERVER['REQUEST_URI']?>".split("/");
            activeurl[activeurl.length - 1] = "/cms/" + activeurl[activeurl.length - 1];
            $('.nav.navbar-nav a[href="'+activeurl[activeurl.length - 1]+'"]').parent('li').addClass('active');
            if(activeurl[activeurl.length - 1] == "/cms/") {
                $('.navbar-header>a').removeClass('navbar-brand').addClass('home');
            }
        })
    </script>