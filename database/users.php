<?php
require_once("conn.php");

Class Users extends Connection {
    /* Properties for Users */
    public $array;
    public $perPage = 10;
    public $startPostsFrom;
    public $userCount;
    public $pagerCount;
    public $page;
    
    /* Opening Database Connection */
    function __construct() {
        $this->openConn();
    }

    /* This method deletes user and posts and comments related to that user */
    function delete() {
        if(isset($_POST["delete_user"])) {
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $id = $_POST["delete_user"];
                $sql = "DELETE users, posts, comments
                FROM users
                LEFT JOIN posts ON users.username=posts.post_author
                LEFT JOIN comments ON posts.post_id=comments.post_id OR users.username=comments.comment_author
                WHERE user_id=?";
                $stmt = $this->conn->prepare($sql);
                $stmt->execute([$id]);
            }
        }
    }

    /* This method displays user roles */
    function displayRoles($currentRole) {
        $roles = ["Admin", "Moderator", "Subscriber"];
        foreach ($roles as $role) {
            if($role == $currentRole) { ?>
            <option value="<?php echo $role;?>" selected><?php echo $role;?></option>
            <?php } else { ?>
            <option value="<?php echo $role;?>"><?php echo $role;?></option>
            <?php }
        }
    }

    /* This method sets user role */
    function setRole() {
        if(isset($_POST["set_role"])) {
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $id = $_POST["set_role"];
                $role = $_POST["role_for_user_".$id];
                $sql = "UPDATE users SET role=? WHERE user_id=?";
                $stmt = $this->conn->prepare($sql);
                $stmt->execute([$role, $id]);
            }
        }
    }

    /* This method displays users */
    function display() {
        $sql = "SELECT user_id as id, username, fname, lname, bdate, email, image, role FROM users ORDER BY id DESC LIMIT {$this->startPostsFrom}, {$this->perPage}";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $this->array = $stmt->fetchAll();
        foreach ($this->array as $user) { ?>
            <tr>
                <?php 
                foreach ($user as $key => $value) { 
                    if($key == "image") { ?>
                        <td><p><img style="object-fit: cover;" width="50px;" height="50px;" src="/cms/uploads/users/<?php echo $value;?>" alt="User image"></p></td>
                    <?php } elseif($key == "role") { ?>
                        <td>
                            <?php if($_SESSION["role"] === "Admin") { ?>
                            <select name="role_for_user_<?php echo $user['id'];?>">
                                <?php $this->displayRoles($user["role"]); ?>
                            </select>
                            <?php } else { ?>
                                <?php echo $user["role"];?>
                            <?php } ?>
                        </td>
                    <?php } elseif($key == "username") { ?>
                        <td><p><a href="/cms/author.php?author=<?=$user["username"]?>"><?=$value?></a></p></td>
                    <?php } else { ?>
                        <td><p><?php echo $value;?></p></td>
                    <?php }
                } 
                if($_SESSION["role"] === "Admin") { ?>
                <td style="width: 6%;">
                    <button name="set_role" value="<?php echo $user['id'];?>" type="submit"><i class="fas fa-check"></i> Save</button>
                </td>
                <td style="width: 6%;">
                    <button name="delete_user" onclick="javascript: return confirm('Are you sure you want to delete this user?');" value="<?php echo $user['id'];?>" type="submit"><i class="far fa-trash-alt"></i> Delete</button>
                </td>
                <?php } ?>
            </tr>
        <?php }
    }

    function login() {
        if(isset($_POST["sign_in"])) {
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $username = $_POST["username"];
                $password = $_POST["password"];
                $sql = "SELECT * FROM users WHERE username=? LIMIT 1";
                $stmt = $this->conn->prepare($sql);
                $stmt->execute([$username]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                if($username === $user["username"] && password_verify($password, $user["password"])) {
                    $_SESSION["id"] = $user["id"];
                    $_SESSION["username"] = $user["username"];
                    $_SESSION["fname"] = $user["fname"];
                    $_SESSION["lname"] = $user["lname"];
                    $_SESSION["role"] = $user["role"];
                    $_SESSION["auth"] = "true";
                    header("Location: ".$_SERVER['HTTP_REFERER']);
                } else {
                    $error = "Incorrect username or password!";
                    $link = explode("?alert", $_SERVER['HTTP_REFERER']);
                    $link = $link[0];
                    if(count(explode("?", $link)) == 1) {
                        $link = $link."?alert=$error";
                    } else {
                        $link = explode("&alert", $link);
                        $link = $link[0]."&alert=$error";
                    }
                    header("Location: ".$link);
                }
            }
        }
    }

    // This method counts total amount of users
    function count() {
        $sql = "SELECT COUNT(user_id) as count FROM users";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $count = $stmt->fetch(PDO::FETCH_ASSOC);
        echo $count["count"];
    }

    /* This method checks if user really exists */
    function checkUser($username) {
        $sql = "SELECT COUNT(username) as count FROM users WHERE username=? LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$username]);
        $count = $stmt->fetch(PDO::FETCH_ASSOC);
        return $count["count"];
    }

    /* This method counts how many users are on each page in admin panel(users section) and applies it to pagination */
    function perPage() {
        $countSQL = "SELECT COUNT(user_id) as count FROM users";
        $stmt = $this->conn->prepare($countSQL);
        $stmt->execute();
        // Calculates diapason of posts which should be shown on each page
        $this->userCount = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->userCount = $this->userCount["count"];
        $this->pagerCount = ceil($this->userCount / $this->perPage); 
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

    /* Closing Database Connection */
    function __destruct() {
        $this->conn = 0;
    }
}