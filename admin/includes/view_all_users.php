                        <?php 
                        if(isset($_POST["delete_user"])) {
                            delete("users", $_POST["delete_user"]);
                        }
                        ?>
                        <?php setUserRole($_POST);?>
                        <?php 
                        openConn();
                        $postPerPage = 10;
                        $countSQL = "SELECT COUNT(id) as count FROM users";
                        $stmt = $conn->prepare($countSQL);
                        $stmt->execute();
                        $postCount = $stmt->fetch(PDO::FETCH_ASSOC);
                        $postCount = $postCount["count"];
                        $pagerCount = ceil($postCount / $postPerPage); 
                        if(isset($_GET["page"])) {
                            if($_GET["page"] == 1) {
                                $page = 1;
                                $startPostsFrom = 0;
                            } elseif($_GET["page"] == 0 || $_GET["page"] > $pagerCount || empty($_GET["page"])) {
                                echo "<h1>This page doesn't exist.</h1>";
                                return 0;
                            } else {
                                $page = $_GET["page"];
                                $startPostsFrom = ($page - 1) * $postPerPage;
                            }
                        } else {
                            $page = 1;
                            $startPostsFrom = 0;
                        } ?>
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
                                    <?php displayUsers($startPostsFrom, $postPerPage);?>
                                </tbody>
                            </table>
                        </form>
                        <!-- Pager  -->
                        <?php pager("users.php?", $page, $pagerCount);?>