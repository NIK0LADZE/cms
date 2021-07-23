<?php
require_once("conn.php");

class Posts extends Connection {
    /* Properties for Posts */
    public $data;
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

    // This method adds new posts
    function insert() {
        if(isset($_POST["add_post"])) {
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $title = $_POST["title"];
                $category = $_POST["category"];
                $author = $_SESSION["username"];
                $tags = $_POST["tags"];
                $content = $_POST["content"];
                
                // Photo Upload System
                
                $target_dir = $_SERVER['DOCUMENT_ROOT']."/cms/uploads/";
                $target_file = $target_dir.basename($_FILES["image"]["name"]);
                $uploadOk = 0;
                $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
                
                if (!empty($_FILES["image"]["name"])) {
                    $checkPhoto = getimagesize($_FILES["image"]["tmp_name"]);
                    if ($checkPhoto !== false) {
                        if ($_FILES["image"]["size"] < 500000000) {
                            if ($fileType == "jpg" || $fileType == "jpeg" || $fileType == "png") {
                                if (!file_exists($target_file)) {
                                    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                                        $image = basename($_FILES["image"]["name"]);
                                    } else {
                                        $photoError = "Sorry, there was an error uploading your file.";
                                    }
                                } else {
                                    $photoError = "Sorry, file already exists.";
                                }
                            } else {
                                $photoError = "Sorry, only JPG, JPEG & PNG files are allowed.";
                            }
                        } else {
                            $photoError = "Sorry, your file is too large.";
                        }
                    } else {
                        $photoError = "File is not an image.";
                    }
                }
                
                if(!isset($photoError)) {
                    if(!isset($image)) {
                        $image = "no-photo.png";
                    }
                    $sql = "INSERT INTO posts(post_title, post_author, post_category, post_image, post_tags, post_content) ";
                    $sql .= "VALUES(?, ?, ?, ?, ?, ?)";
                    $stmt = $this->conn->prepare($sql);
                    $stmt->execute([$title, $author, $category, $image, $tags, $content]);
                    echo "<h3 style='color: green;'>Post was published succesfully!</h3>";
                } else {
                    $alert = "<p style='color: red;'>".$photoError."</p>";
                    echo $alert;
                }
            }
        }
    }

    // This method edits posts
    function edit() {
        if(isset($_POST["add_post"])) {
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $title = $_POST["title"];
                $category = $_POST["category"];
                $tags = $_POST["tags"];
                $content = $_POST["content"];
                $id = $_GET["post_id"];

                // Photo Upload System
            
                $target_dir = $_SERVER['DOCUMENT_ROOT']."/cms/uploads/";
                $target_file = $target_dir.basename($_FILES["image"]["name"]);
                $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

                if (!empty($_FILES["image"]["name"])) {
                    $checkPhoto = getimagesize($_FILES["image"]["tmp_name"]);
                    if ($checkPhoto !== false) {
                        if ($_FILES["image"]["size"] < 500000000) {
                            if ($fileType == "jpg" || $fileType == "jpeg" || $fileType == "png") {
                                if (!file_exists($target_file)) {
                                    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                                    $image = basename($_FILES["image"]["name"]);
                                    } else {
                                    $photoError = "Sorry, there was an error uploading your file.";
                                    }
                                } else {
                                    $image = basename($_FILES["image"]["name"]);
                                }
                            } else {
                                $photoError = "Sorry, only JPG, JPEG & PNG files are allowed.";
                            }
                        } else {
                            $photoError = "Sorry, your file is too large.";
                        }
                    } else {
                        $photoError = "File is not an image.";
                    }
                }

                if(!isset($photoError)) {
                    $query = "UPDATE posts SET post_title=:title, post_category=:category, ";
                    if(isset($image)) {
                        $query .= "post_image=:image, ";
                    }
                    $query .= "post_tags=:tags, post_content=:content ";
                    $query .= "WHERE post_id=:id";
                    $stmt = $this->conn->prepare($query);
                    $stmt->bindParam(':title', $title);
                    $stmt->bindParam(':category', $category);
                    $stmt->bindParam(':tags', $tags);
                    $stmt->bindParam(':content', $content);
                    $stmt->bindParam(':id', $id);
                    if(isset($image)) {
                        $stmt->bindParam(':image', $image);
                    }
                    $stmt->execute();
                    echo "<h3 style='color: green;'>Post was editted succesfully!</h3>";
                }
            }
        }
    }

    function data($id) {
        $sql = "SELECT post_title as title, post_category as category, post_image as image,
        post_tags as tags, post_content as content FROM posts WHERE post_id=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        $this->data = $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // This method displays category options when adding or editing posts
    function options() {
        $sql = "SELECT cat_title FROM categories";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $options = $stmt->fetchAll();
        foreach ($options as $option) {
            if($this->data["category"] == $option["cat_title"]) { ?>
                <option value="<?=$option["cat_title"];?>" selected><?=$option["cat_title"];?></option>
            <?php } else { ?>
            <option value="<?=$option["cat_title"];?>"><?=$option["cat_title"];?></option>
        <?php }
        }
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
                        <td><p><img style="object-fit: cover;" width="120vw;" height="40vh;" src="/cms/uploads/<?php echo $value;?>" alt="Post image"></p></td>
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

    // This method clones posts
    function clone() {
        if(isset($_POST["clone_post"])) {
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $id = $_POST["post_id"];
                $sql = "INSERT INTO posts(post_author, post_title, post_category, post_image, post_tags, post_comment_count, post_content) 
                SELECT post_author, post_title, post_category, post_image, post_tags, post_comment_count, post_content FROM posts WHERE post_id=?";
                $stmt = $this->conn->prepare($sql);
                $stmt->execute([$id]);
            }
        }
    }

    // This method deletes posts and comments related to that post
    function delete() {
        if(isset($_POST["delete_post"])) {
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $id = $_POST["post_id"];
                $sql = "DELETE posts, comments 
                FROM posts
                LEFT JOIN comments ON posts.post_id=comments.post_id
                WHERE posts.post_id=?";
                $stmt = $this->conn->prepare($sql);
                $stmt->execute([$id]);
            }
        }
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