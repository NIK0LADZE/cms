<?php
require_once("conn.php");

Class Categories extends Connection {
    /* Properties for Categories */
    public $title;
    public $array;

    /* Opening Database Connection */
    function __construct() {
        $this->openConn();
    }

    // This method counts total amount of categories
    function count() {
        $sql = "SELECT COUNT(cat_id) as count FROM categories";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $count = $stmt->fetch(PDO::FETCH_ASSOC);
        echo $count["count"];
    }

    /* This method checks if category really exists */
    function check($cat_id) {
        $sql = "SELECT COUNT(cat_id) as count FROM categories WHERE cat_id=? LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$cat_id]);
        $count = $stmt->fetch(PDO::FETCH_ASSOC);
        return $count["count"];
    }

    /* This method adds new categories */
    function insert() {
        if(isset($_POST["addcat"])) {
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                if(!empty($_POST["cat_title"])) {
                    $cat_title = $_POST["cat_title"];
                    $count = $this->check($cat_title);
                    if($count == 1) {
                        echo "<p style='color: red; margin: 0;'>This category already exists</p>";
                    } else {
                        $sql = "INSERT INTO categories(cat_title) VALUES(?)";
                        $stmt = $this->conn->prepare($sql);
                        $stmt->execute([$cat_title]);
                    }
                } else {
                    echo "<p style='color: red; margin: 0;'>This field should not be empty</p>";
                }
            }
        }
    }

    /* This method edits category title */
    function update() {
        if(isset($_POST["new"])) {
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $oldCatTitle = $_POST["old"];
                $newCatTitle = $_POST["new"];
                if(!empty($newCatTitle)) {
                    $count = $this->check($newCatTitle);
                    if($count == 1) {
                        echo "<p style='color: red; margin: 0;'>This category already exists</p>";
                    } else {
                        $updateCat = "UPDATE categories SET cat_title=? WHERE cat_title=?";
                        $updateCat = $this->conn->prepare($updateCat);
                        $updateCat->execute([$newCatTitle, $oldCatTitle]);
                    }
                } else {
                    echo "<p style='text-align: center; color: red; margin: 0;'>New value shoud not be empty</p>";
                }
            }
        }
    }

    /* This method displays categories */
    function display() {
        $sql = "SELECT cat_id as id, cat_title as title FROM categories";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $this->array = $stmt->fetchAll();
    }

    /* This method displays category title */
    function title($id) {
        $sql = "SELECT cat_title as title FROM categories WHERE cat_id=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        $this->title = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->title = $this->title["title"];
    }

    /* This method displays categories table in admin panel */
    function table() {
        $this->display();
        foreach ($this->array as $cat) { ?>
            <tr>
                <td><p><?php echo $cat['id'];?></p></td>
                <td id="title">
                    <p id="<?php echo $cat['title'];?>"><a href="/cms/category.php?cat_id=<?=$cat['id']?>"><?=$cat['title']?></a></p>
                    <form action="" method="post">
                        <input type="hidden" name="old" value="<?php echo $cat['title'];?>">
                        <input type="hidden" name="new" class="edit" id="<?php echo $cat['title'].'_for_input';?>" value="<?php echo $cat['title'];?>">
                    </form>
                </td>
                <?php if($_SESSION["role"] === "Admin" || $_SESSION["role"] === "Moderator") { ?>
                    <td style="width: 10%; text-align: center;">
                        <button onclick="document.getElementById('<?php echo $cat['title'];?>').style.display = 'none'; document.getElementById('<?php echo $cat['title'];?>_for_input').setAttribute('type', 'text');" name="edit" type="submit"><i class="far fa-edit"></i> Edit</button>
                    </td>
                    <td style="width: 12%; text-align: center;">
                        <form id="delete" action="" method="post">
                            <input type="hidden" name="cat_id" value="<?php echo $cat['id'];?>">
                            <button name="delete_category" onclick="javascript: return confirm('Are you sure you want to delete this category?');" type="submit"><i class="far fa-trash-alt"></i> Delete</button>
                        </form>
                    </td>
                <?php } ?>
            </tr>
        <?php }
    }

    /* This method deletes category which also results deleting posts related to the category and comments related to posts */
    function delete() {
        if(isset($_POST["delete_category"])) {
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $id = $_POST["cat_id"];
                $sql = "DELETE categories, posts, comments 
                FROM categories 
                LEFT JOIN posts ON categories.cat_id=posts.post_category_id
                LEFT JOIN comments ON posts.post_id=comments.post_id
                WHERE categories.cat_id=?";
                $stmt = $this->conn->prepare($sql);
                $stmt->execute([$id]);
            }
        }
    }

    /* Closing Database Connection */
    function __destruct() {
        $this->conn = 0;
    }
}