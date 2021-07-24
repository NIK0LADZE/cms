<?php
require_once("conn.php");

class Comments extends Connection {
    /* Properties for Comments */
    public $array;
    public $perPage = 10;
    public $startFrom;
    public $commentCount;
    public $pagerCount;
    public $page;

    /* Opening Database Connection */
    function __construct() {
        $this->openConn();
    }

    // This method counts total amount of comments
    function count() {
        $sql = "SELECT COUNT(comment_id) as count FROM comments";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $count = $stmt->fetch(PDO::FETCH_ASSOC);
        echo $count["count"];
    }

    // This method displays comments for posts
    function display() {
        $sql = "SELECT users.image as photo, users.username as author, comments.comment_content as content, comments.comment_date as date 
        FROM comments INNER JOIN users ON comments.comment_author_id = users.user_id WHERE comments.post_id=? ORDER BY date DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$_GET["post_id"]]);
        $this->array = $stmt->fetchAll();
    }

    // This method displays comments table in admin panel
    function table() {
        $sql = "SELECT comments.comment_id as id, posts.post_id as post_id, users.user_id as user_id, users.username as author, posts.post_title as title, comments.comment_content as content, comments.comment_date as date FROM comments
        INNER JOIN posts ON comments.post_id = posts.post_id
        INNER JOIN users ON comments.comment_author_id = users.user_id
        ORDER BY comments.comment_date DESC LIMIT {$this->startFrom}, {$this->perPage}";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $comments = $stmt->fetchAll();
        foreach ($comments as $comment) { ?>
            <tr>
                <?php foreach ($comment as $key => $value) { ?>
                    <?php if($key == "author") { ?>
                        <td><p><a href="/cms/author.php?user_id=<?=$comment["user_id"]?>"><?=$value?></a></p></td>
                    <?php } elseif($key == "title") { ?>
                        <td><p><a href="/cms/post.php?post_id=<?=$comment["post_id"]?>"><?=$value?></a></p></td>
                    <?php } elseif($key !== "post_id" && $key !== "user_id") { ?>
                    <td><?= $value ?></td>
                <?php }
                } 
                if($_SESSION["role"] === "Admin" || $_SESSION["role"] === "Moderator") { ?>
                    <td style="width: 6%;">
                        <form action="" method="post">
                            <input type="hidden" name="comment_id" value="<?php echo $comment['id'];?>">
                            <button name="delete_comment" onclick="javascript: return confirm('Are you sure you want to delete this comment?');" type="submit"><i class="far fa-trash-alt"></i> Delete</button>
                        </form>
                    </td>
                <?php } ?>
            </tr>
        <?php }
    }

    // This method adds new comments
    function insert($post_id, $author, $comment) {
        $sql = "INSERT INTO comments(post_id, comment_author_id, comment_content) ";
        $sql .= "VALUES(?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$post_id, $author, $comment]);
    }

    /* This method deletes comment */
    function delete() {
        if(isset($_POST["delete_comment"])) {
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $id = $_POST["comment_id"];
                $sql = "DELETE FROM comments WHERE comment_id=?";
                $stmt = $this->conn->prepare($sql);
                $stmt->execute([$id]);
            }
        }
    }

    /* This method counts how many comments are on each page in admin panel(comments section) and applies it to pagination */
    function perPage() {
        $countSQL = "SELECT COUNT(comment_id) as count FROM comments";
        $stmt = $this->conn->prepare($countSQL);
        $stmt->execute();
        // Calculates diapason of posts which should be shown on each page
        $this->commentCount = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->commentCount = $this->commentCount["count"];
        $this->pagerCount = ceil($this->commentCount / $this->perPage); 
        if(isset($_GET["page"])) {
            if($_GET["page"] == 1) {
                $this->page = 1;
                $this->startFrom = 0;
            } elseif($_GET["page"] == 0 || $_GET["page"] > $this->pagerCount || empty($_GET["page"])) {
                echo "<h1>This page doesn't exist.</h1>";
            } else {
                $this->page = $_GET["page"];
                $this->startFrom = ($this->page - 1) * $this->perPage;
            }
        } else {
            $this->page = 1;
            $this->startFrom = 0;
        }
    }

    /* Closing Database Connection */
    function __destruct() {
        $this->conn = 0;
    }
}