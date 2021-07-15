<div class="col-md-4">

                <!-- Blog Search Well -->
                <div class="well">
                    <h4>Blog Search</h4>
                    <form action="/cms/search" method="get">
                    <div class="input-group">
                        <input name="keyword" type="text" class="form-control">
                        <span class="input-group-btn">
                            <button class="btn btn-default" type="search">
                                <span class="glyphicon glyphicon-search"></span>
                            </button>
                        </span>
                    </div>
                    </form>
                    <!-- /.input-group -->
                </div>

                <!-- Login Form -->
                <?php 
                if(!isset($_SESSION["auth"]) || $_SESSION["auth"] !== "true") { ?>
                        <div class="well">
                            <h4>Authorization</h4>
                            <?php 
                            if(isset($_GET["alert"])) {
                                echo "<p style='color: red; text-align: center;'>".$_GET['alert']."</p>";
                            }
                            ?>
                            <form action="/cms/actions/auth.php" method="post">
                            <div class="form-group">
                                <input name="username" type="text" class="form-control" placeholder="Enter your username..." required>
                            </div>
                            <div class="input-group">
                                <input name="password" type="password" class="form-control" placeholder="Enter your password..." required>
                                <span class="input-group-btn">
                                    <button class="btn btn-success" type="submit" name="sign_in">Sign In</button>
                                </span>
                            </div>
                            <p style="text-align: center; margin-top: 10px;">Not a member yet? <a href="/cms/sign-up.php">Sign up</a></p>
                            </form>
                            <!-- /.input-group -->
                        </div>
                <?php } ?>

                <!-- Blog Categories Well -->
                <div class="well">
                    <h4>Blog Categories</h4>
                    <div class="row">
                        <div class="col-lg-6">
                            <ul class="list-unstyled">
                                <?php displayCategories(); ?>
                            </ul>
                        </div>
                        <!-- /.col-lg-6 -->
                        <div class="col-lg-6">
                            <ul class="list-unstyled">
                                <li><a href="#">Category Name</a>
                                </li>
                                <li><a href="#">Category Name</a>
                                </li>
                                <li><a href="#">Category Name</a>
                                </li>
                                <li><a href="#">Category Name</a>
                                </li>
                            </ul>
                        </div>
                        <!-- /.col-lg-6 -->
                    </div>
                    <!-- /.row -->
                </div>

                <!-- Side Widget Well -->
                <div class="well">
                    <h4>Side Widget Well</h4>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Inventore, perspiciatis adipisci accusamus laudantium odit aliquam repellat tempore quos aspernatur vero.</p>
                </div>

            </div>