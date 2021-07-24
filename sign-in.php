<?php 
require_once("includes/header.php"); 
if(isset($_SESSION["auth"])) {
    header("Location: /cms");
}
?>

    <!-- Page Content -->
    <div class="container">

        <div class="row">
            <h1>Authorization Page</h1>
            <?php 
            if(isset($_GET["alert"])) {
                echo "<h3 style='color: red;'>".$_GET['alert']."</h3>";
            } elseif(isset($_GET["success"])) {
                echo "<h3 style='color: green;'>".$_GET['success']."</h3>";
            }
            ?>
            <form action="/cms/actions/auth.php" method="post">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" class="form-control" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" name="password" required>
                </div>
                <div class="form-group">
                    <input class="btn btn-success" type="submit" name="sign_in" value="Sign In">
                </div>
                <p>Not a member yet? <a href="/cms/sign-up.php">Sign up</a></p>
            </form>
        </div>
        <!-- /.row -->

        <hr>

        <!-- Footer -->
<?php require_once("includes/footer.php"); ?>