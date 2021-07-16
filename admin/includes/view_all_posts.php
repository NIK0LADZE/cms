                        <?php delPost($_POST);?>
                        <?php clonePost($_POST);?>
                        <?php 
                        openConn();
                        $postPerPage = 8;
                        $countSQL = "SELECT COUNT(post_id) as count FROM posts";
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
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Author</th>
                                    <th>Title</th>
                                    <th>Category</th>
                                    <th style="width: 0;">Image</th>
                                    <th>Tags</th>
                                    <th>Views</th>
                                    <th>Comments</th>
                                    <th colspan=4>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php displayPosts($startPostsFrom, $postPerPage);?>
                            </tbody>
                        </table>
                        <!-- Pager  -->
                        <?php pager("posts.php?action=".$_GET["action"]."&", $page, $pagerCount);?>