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
                            Profile Page
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
                        openConn();
                        $id = $_SESSION["id"];
                        $query = "SELECT id, username, fname, lname, bdate, email, image FROM users WHERE id='$id' LIMIT 1";
                        $stmt = $conn->prepare($query);
                        $stmt->execute();
                        $userData = $stmt->fetch(PDO::FETCH_ASSOC);
                        $conn = 0;
                        ?>
                        <div style="display: flex; justify-content: center; width: 100%; margin: 30px 0;">
                            <img style="border-radius: 50%; " src="/cms/uploads/users/<?php echo $userData["image"];?>" width="300px" height="300px" alt="User Photo">
                        </div>
                        <?php 
                        if(isset($_GET["success"])) {
                            echo "<h3 style='color: green;'>".$_GET['success']."</h3>";
                        }
                        ?>
                        <form action="/cms/actions/edit_user.php" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="id" value="<?php echo $userData["id"];?>">
                            <div class="form-group">
                                <label for="username">Username</label>
                                <?php 
                                if(isset($_GET["usernameError"])) { ?>
                                <span style="color: red;">* <?php echo $_GET["usernameError"];?></span>
                                <?php } ?>
                                <input type="text" class="form-control" name="username" value="<?php echo $userData["username"];?>" required>
                            </div>
                            <div class="form-group">
                                <label for="fname">First name</label>
                                <input type="text" class="form-control" name="fname" value="<?php echo $userData["fname"];?>">
                            </div>
                            <div class="form-group">
                                <label for="lname">Last name</label>
                                <input type="text" class="form-control" name="lname" value="<?php echo $userData["lname"];?>">
                            </div>
                            <div class="form-group">
                                <label for="oldpass">Old password</label>
                                <?php 
                                if(isset($_GET["oldPassError"])) { ?>
                                <span style="color: red;">* <?php echo $_GET["oldPassError"];?></span>
                                <?php } ?>
                                <input type="password" class="form-control" name="oldpass">
                            </div>
                            <div class="form-group">
                                <label for="newpass">New password</label>
                                <?php 
                                if(isset($_GET["passError"])) { ?>
                                <span style="color: red;">* <?php echo $_GET["passError"];?></span>
                                <?php } ?>
                                <input type="password" class="form-control" name="newpass">
                            </div>
                            <div class="form-group">
                                <label for="verifynewpass">Verify new password</label>
                                <?php 
                                if(isset($_GET["verifyPassError"])) { ?>
                                <span style="color: red;">* <?php echo $_GET["verifyPassError"];?></span>
                                <?php } ?>
                                <input type="password" class="form-control" name="verifynewpass">
                            </div>
                            <div class="form-group">
                                <label for="bdate">Date of birth</label>
                                <input type="date" class="form-control" name="bdate" value="<?php echo $userData["bdate"];?>">
                            </div>
                            <div class="form-group">
                                <label for="email">Email address</label>
                                <?php 
                                if(isset($_GET["emailError"])) { ?>
                                <span style="color: red;">* <?php echo $_GET["emailError"];?></span>
                                <?php } ?>
                                <input type="email" class="form-control" name="email" value="<?php echo $userData["email"];?>">
                            </div>
                            <div class="form-group">
                                <label for="image">Image</label>
                                <?php 
                                if(isset($_GET["photoError"])) { ?>
                                <span style="color: red;">* <?php echo $_GET["photoError"];?></span>
                                <?php } ?>
                                <input type="file" name="image" id="image">
                            </div>
                            <div class="form-group">
                                <input class="btn btn-success" type="submit" name="update_user" value="Save">
                            </div>
                        </form>
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
