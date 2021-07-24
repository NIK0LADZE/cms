<?php 
require_once("includes/header.php"); 
if(isset($_SESSION["auth"])) {
    header("Location: /cms");
}
?>

    <!-- Page Content -->
    <div class="container">

        <div class="row">
            <h1>Registration Page</h1>
            <form action="actions/reg.php" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="username">Username</label>
                        <?php 
                        if(isset($_GET["usernameError"])) { ?>
                        <span style="color: red;">* <?php echo $_GET["usernameError"];?></span>
                        <?php } ?>
                    <input type="text" class="form-control" name="username" value="<?php
                    if(isset($_GET["username"])) {
                        echo $_GET["username"];
                    }
                    ?>" required>
                </div>
                <div class="form-group">
                    <label for="fname">First name</label>
                    <input type="text" class="form-control" name="fname" value="<?php
                    if(isset($_GET["fname"])) {
                        echo $_GET["fname"];
                    }
                    ?>" required>
                </div>
                <div class="form-group">
                    <label for="lname">Last name</label>
                    <input type="text" class="form-control" name="lname" value="<?php
                    if(isset($_GET["lname"])) {
                        echo $_GET["lname"];
                    }
                    ?>" required>
                </div>
                <div class="form-group">
                    <label for="pass1">Password</label>
                        <?php 
                        if(isset($_GET["passError"])) { ?>
                        <span style="color: red;">* <?php echo $_GET["passError"];?></span>
                        <?php } ?>
                    <input type="password" class="form-control" name="pass1" required>
                </div>
                <div class="form-group">
                    <label for="pass2">Verify password</label>
                        <?php 
                        if(isset($_GET["verifyPassError"])) { ?>
                        <span style="color: red;">* <?php echo $_GET["verifyPassError"];?></span>
                        <?php } ?>
                    <input type="password" class="form-control" name="pass2" required>
                </div>
                <div class="form-group">
                    <label for="bdate">Date of birth</label>
                    <input type="date" class="form-control" name="bdate" value="<?php
                    if(isset($_GET["bdate"])) {
                        echo $_GET["bdate"];
                    }
                    ?>" required>
                </div>
                <div class="form-group">
                    <label for="email">Email address</label>
                        <?php 
                        if(isset($_GET["emailError"])) { ?>
                        <span style="color: red;">* <?php echo $_GET["emailError"];?></span>
                        <?php } ?>
                    <input type="email" class="form-control" name="email" value="<?php
                    if(isset($_GET["email"])) {
                        echo $_GET["email"];
                    }
                    ?>" required>
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
                    <input class="btn btn-success" type="submit" name="sign_up" value="Sign Up">
                </div>
                <p>Already a member? <a href="/cms/sign-in.php">Sign in</a></p>
            </form>
        </div>
        <!-- /.row -->

        <hr>

        <!-- Footer -->
<?php require_once("includes/footer.php"); ?>