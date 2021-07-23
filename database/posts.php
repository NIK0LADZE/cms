<?php
require_once("conn.php");

class Posts extends Connection {
    /* Properties for Posts */
    public $array;
    public $perPage = 10;
    public $startPostsFrom;
    public $postCount;
    public $pagerCount;
    public $page;

    /* Opening Database Connection */
    function __construct() {
        $this->openConn();
    }

    /* This method counts total amount of posts */
    function count() {
        $sql = "SELECT COUNT(post_id) as count FROM posts";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $count = $stmt->fetch(PDO::FETCH_ASSOC);
        echo $count["count"];
    }

    /* This method displays posts */
    function display($content = 1) {
        $sql = "SELECT post_id as id, post_title as title, post_author as author, post_category as category,
        post_image as image, post_views as views, post_content as content, post_date as date FROM posts
        WHERE ";
        // Displays post on post page
        if(isset($_GET["post_id"])) {
            $content = $_GET["post_id"];
            $sql .= "post_id=?";
            $this->startPostsFrom = 0;
            $this->perPage = 1;
        // Displays posts on selected category page
        } elseif(isset($_GET["category"])) {
            $sql .= "post_category=?";
        // Displays posts on selected user page
        } elseif(isset($_GET["author"])) {
            $sql .= "post_author=?";
        // Displays posts on home page
        } else {
            $sql .= "1=?";
        }
        // Sets diapason for posts to be shown on each page
        $sql .= " ORDER BY date DESC LIMIT {$this->startPostsFrom}, {$this->perPage}";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$content]);
        return $this->array = $stmt->fetchAll();
    }

    // This method displays posts table in admin panel
    function table() {
        $this->perPage = 8;
        $sql = "SELECT post_id as id, post_author as author, post_title as title, post_category as category, 
        post_image as image, post_tags as tags, post_views as views, post_comment_count as comments, post_date as date FROM posts ORDER BY post_date DESC LIMIT {$this->startPostsFrom}, {$this->perPage}";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $posts = $stmt->fetchAll();
        foreach ($posts as $post) { ?>
            <tr>
                <?php 
                foreach ($post as $key => $value) { 
                    if($key == "image") { ?>
                        <td><p><img width="120vw;" height="40vh;" src="/cms/uploads/<?php echo $value;?>" alt="Post image"></p></td>
                    <?php } elseif($key == "comments") { 
                        $post_id = $post["id"];
                        $query = "SELECT COUNT(comment_id) as count FROM comments WHERE post_id='$post_id'";
                        $commentCount = $this->conn->prepare($query);
                        $commentCount->execute();
                        $count = $commentCount->fetch(PDO::FETCH_ASSOC);
                        ?>
                        <td><?php echo $count["count"];?></td>
                    <?php } elseif($key == "title") { ?>
                        <td><p><a href="/cms/post.php?post_id=<?=$post["id"]?>"><?=$value?></a></p></td>
                    <?php } elseif($key == "author") { ?>
                        <td><p><a href="/cms/author.php?author=<?=$post["author"]?>"><?=$value?></a></p></td>
                    <?php } elseif($key == "category") { ?>
                        <td><p><a href="/cms/category.php?category_title=<?=$post["category"]?>"><?=$value?></a></p></td>
                    <?php } else { ?>
                        <td><p><?php echo $value;?></p></td>
                    <?php }
                } 
                if($_SESSION["role"] === "Admin" || $_SESSION["role"] === "Moderator") { ?>
                    <td style="width: 6%;">
                        <button><a style="color: black;" href="posts.php?action=edit&post_id=<?php echo $post['id'];?>"><i class="far fa-edit"></i> Edit</a></button>
                    </td>
                    <td style="width: 6%;">
                        <form action="" method="post">
                            <input type="hidden" name="post_id" value="<?php echo $post['id'];?>">
                            <button name="clone_post" onclick="javascript: return confirm('Are you sure you want to clone this post?');" type="submit"><i class="far fa-copy"></i> Clone</button>
                        </form>
                    </td>
                    <td style="width: 6%;">
                        <form action="" method="post">
                            <input type="hidden" name="post_id" value="<?php echo $post['id'];?>">
                            <button name="delete_post" onclick="javascript: return confirm('Are you sure you want to delete this post?');" type="submit"><i class="far fa-trash-alt"></i> Delete</button>
                        </form>
                    </td>
                <?php } ?>
            </tr>
        <?php }
    }

    /* This method counts how many times each post was viewed */
    function updateViews($views, $id) {
        $viewSQL = "UPDATE posts SET post_views=? WHERE post_id=?";
        $updateViews = $this->conn->prepare($viewSQL);
        $updateViews->execute([$views, $id]);
    }

    /* This method counts how many posts are on different pages and applies it to pagination */
    function perPage($content = 1) {
        $countSQL = "SELECT COUNT(post_id) as count FROM posts WHERE ";
        if(isset($_GET["keyword"])) {
            $countSQL .= "post_tags LIKE ";
            // Setting keywords to search in posts
            $keywords = explode(" ", $_GET["keyword"]);
            $keywords = "%".implode("% %", $keywords)."%";
            $keywords = explode(" ", $keywords);
            // Checking for each keyword
            for ($i = 0; $i < count($keywords); $i++) {
                if($i > 0) {
                    $countSQL .= "OR";
                }
                $countSQL .= "?";
            } 
        // Calculates amount of posts in selected category
        } elseif(isset($_GET["category"])) {
            $countSQL .= "post_category=?";
        // Calculates amount of posts by selected user
        } elseif(isset($_GET["author"])) {
            $countSQL .= "post_author=?";
        // Displays all posts for home page
        } else {
            $countSQL .= "1=?";
        }
        $stmt = $this->conn->prepare($countSQL);
        // This line only gets executed when user searches for posts
        if(isset($_GET["keyword"])) {
            $stmt->execute($keywords);
        // This line gets executed in any other occasions
        } else {
            $stmt->execute([$content]);
        }
        // Calculates diapason of posts which should be shown on each page
        $this->postCount = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->postCount = $this->postCount["count"];
        $this->pagerCount = ceil($this->postCount / $this->perPage); 
        if(isset($_GET["page"])) {
            if($_GET["page"] == 1) {
                $this->page = 1;
                $this->startPostsFrom = 0;
            } elseif($_GET["page"] == 0 || $_GET["page"] > $this->pagerCount || empty($_GET["page"])) {
                echo "<h1>This page doesn't exist.</h1>";
            } else {
                $this->page = $_GET["page"];
                $this->startPostsFrom = ($this->page - 1) * $this->perPage;
            }
        } else {
            $this->page = 1;
            $this->startPostsFrom = 0;
        }
    }

    /* Search method */
    function search() {
        if(empty($_GET["keyword"])) {
            return header("Location: /cms/");
        } else {
            // Setting keywords to search in posts
            $keywords = explode(" ", $_GET["keyword"]);
            $keywords = "%".implode("% %", $keywords)."%";
            $keywords = explode(" ", $keywords);
            $sql = "SELECT post_id as id, post_title as title, post_author as author, post_category as category,
            post_image as image, post_views as views, post_content as content, post_date as date FROM posts WHERE post_tags LIKE ";
            // Checking for each keyword
            for ($i = 0; $i < count($keywords); $i++) {
                if($i > 0) {
                    $sql .= "OR";
                }
                $sql .= "?";
            } 
            $sql .= " ORDER BY post_date DESC LIMIT {$this->startPostsFrom}, {$this->perPage}";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($keywords);
            return $this->array = $stmt->fetchAll();
        }
    }

    /* Closing Database Connection */
    function __destruct() {
        $this->conn = 0;
    }
}