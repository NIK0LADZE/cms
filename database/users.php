<?php
require_once("conn.php");

Class Users extends Connection {
    /* Properties for Users */
    public $data;
    public $array;
    public $perPage = 10;
    public $startPostsFrom;
    public $userCount;
    public $pagerCount;
    public $page;
    public $title;
    
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
                LEFT JOIN posts ON users.user_id=posts.post_author_id
                LEFT JOIN comments ON posts.post_id=comments.post_id OR users.user_id=comments.comment_author_id
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
                        <td><p><a href="/cms/author.php?user_id=<?=$user["id"]?>"><?=$value?></a></p></td>
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

    /* This method checks if user entered correct credentials and proceeds accordingly */
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
                    $_SESSION["id"] = $user["user_id"];
                    $_SESSION["username"] = $user["username"];
                    $_SESSION["fname"] = $user["fname"];
                    $_SESSION["lname"] = $user["lname"];
                    $_SESSION["role"] = $user["role"];
                    $_SESSION["auth"] = "true";
                    $link = explode("&alert", $_SERVER['HTTP_REFERER']);
                    header("Location: ".$link[0]);
                } else {
                    $error = "Incorrect username or password!";
                    // echo $_SERVER['HTTP_REFERER'];
                    if(count(explode("?alert", $_SERVER['HTTP_REFERER'])) != 1
                    || count(explode("?success", $_SERVER['HTTP_REFERER'])) != 1) {
                        $link = explode("?", $_SERVER['HTTP_REFERER']);
                        $link = $link[0];
                    } elseif(count(explode("?", $_SERVER['HTTP_REFERER'])) != 1) {
                        $link = explode("&alert", $_SERVER['HTTP_REFERER']);
                        echo $link = $link[0]."&alert=".$error;
                    } else {
                        echo $link = $_SERVER['HTTP_REFERER']."?alert=$error";
                    }
                    header("Location: ".$link);
                }
            }
        }
    }

    // Checks if username is free
    function checkUsername($username, &$errArr, $id = 0) {
        $query = "SELECT username FROM users WHERE username=? AND NOT user_id=? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$username, $id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($stmt->rowCount()) {
            if($result["username"] === $username) {
                $errArr["usernameError"] = "This username already exists";
                return false;
            }
        } else {
            return true;
        }
    }

    // Checks if email is free
    function checkEmail($email, &$errArr, $id = 0) {
        $query = "SELECT email FROM users WHERE email=? AND NOT user_id=? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$email, $id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($stmt->rowCount()) {
            if($result["email"] === $email) {
                $errArr["emailError"] = "User with this email is already registered";
                return false;
            }
        } else {
            return true;
        }
    }

    // This method registers user
    function register() {
        if(isset($_POST["sign_up"])) {
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                
                $errArr = [];
                $succArr = [];
                // Checks if Username is free
                if($this->checkUsername($_POST["username"], $errArr)) {
                    $succArr["username"] = $username = $_POST["username"];
                }
                $succArr["fname"] = $fname= $_POST["fname"];
                $succArr["lname"] = $lname = $_POST["lname"];
                // Checks if password is entered correctly
                if (strlen($_POST["pass1"]) < 8) {
                    $errArr["passError"] = "Password must be at least 8 characters in length";
                } elseif (!preg_match("/^\S*(?=\S*[a-z])(?=\S*[\W])(?=\S*[A-Z])(?=\S*[\d])\S*$/", $_POST["pass1"])) {
                    $errArr["passError"] = "Password must include one uppercase, lowercase and special characters";
                } else {
                    $pass1 = $_POST["pass1"];
                }
                $role = "Subscriber";
                // Checks if passwords match
                if(isset($pass1)) {
                    if($_POST["pass2"] !== $pass1) {
                        $errArr["verifyPassError"] = "Passwords don't match";
                    } else {
                        $password = password_hash($pass1, PASSWORD_DEFAULT);
                    }
                }
                $succArr["bdate"] = $bdate = $_POST["bdate"];
                // Checks if email is free
                if($this->checkEmail($_POST["email"], $errArr)) {
                    $succArr["email"] = $email = $_POST["email"];
                }

                // Photo Upload System

                $target_dir = $_SERVER['DOCUMENT_ROOT']."/cms/uploads/users/";
                $target_file = $target_dir.basename($_FILES["image"]["name"]);
                $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
                
                if (!empty($_FILES["image"]["name"])) {
                    $checkPhoto = getimagesize($_FILES["image"]["tmp_name"]);
                    if ($checkPhoto !== false) {
                        if ($_FILES["image"]["size"] < 500000000) {
                            if ($fileType == "jpg" || $fileType == "jpeg" || $fileType == "png") {
                                if (!file_exists($target_file)) {
                                    if(count($errArr) == 0) {
                                        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                                        $image = basename($_FILES["image"]["name"]);
                                        } else {
                                        $errArr["photoError"] = "Sorry, there was an error uploading your file.";
                                        }
                                    }
                                } else {
                                    $errArr["photoError"] = "Sorry, file already exists.";
                                }
                            } else {
                                $errArr["photoError"] = "Sorry, only JPG, JPEG & PNG files are allowed.";
                            }
                        } else {
                            $errArr["photoError"] = "Sorry, your file is too large.";
                        }
                    } else {
                        $errArr["photoError"] = "File is not an image.";
                    }
                }
                // Checks if user entered everything correctly and if so proceeds to register the user
                if(count($errArr) == 0) {
                    // If user didn't upload photo
                    if(!isset($image)) {
                        $image = "no-photo.png";
                    }
                    $sql = "INSERT INTO users(username, fname, lname, password, bdate, email, image, role) ";
                    $sql .= "VALUES(?, ?, ?, ?, ?, ?, ?, ?)";
                    $stmt = $this->conn->prepare($sql);
                    $stmt->execute([$username, $fname, $lname, $password, $bdate, $email, $image, $role]);
                    $link = "sign-in.php?success=Registration was successful!";
                    return header("Location: ../$link");
                } else {
                    $link = "sign-up.php?".http_build_query($errArr)."&".http_build_query($succArr);
                    return header("Location: ../$link");
                }
            }
        }
    }

    // This method sends data in user profile page
    function data($id) {
        $query = "SELECT user_id as id, username, fname, lname, bdate, email, image FROM users WHERE user_id=? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        $this->data = $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // This method validates password when user tries to change it
    function checkPass($oldpass, $id) {
        $sql = "SELECT password FROM users WHERE user_id=? LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if(password_verify($oldpass, $result["password"])) {
            return true;
        } else {
            return false;
        }
    }

    // This method edits user data
    function edit() {
        if(isset($_POST["update_user"])) {
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $errArr = [];
                $id = $_POST["id"];
                $fname= $_POST["fname"];
                $lname= $_POST["lname"];
                $bdate = $_POST["bdate"];

                if($this->checkUsername($_POST["username"], $errArr, $_POST["id"])) {
                    $username = $_POST["username"];
                }

                if($this->checkEmail($_POST["email"], $errArr, $_POST["id"])) {
                    $email = $_POST["email"];
                }

                if(!empty($_POST["oldpass"])) {
                    if($this->checkPass($_POST["oldpass"], $_POST["id"])) {
                        if (strlen($_POST["newpass"]) < 8) {
                            $errArr["passError"] = "Password must be at least 8 characters in length";
                        } elseif (!preg_match("/^\S*(?=\S*[a-z])(?=\S*[\W])(?=\S*[A-Z])(?=\S*[\d])\S*$/", $_POST["newpass"])) {
                            $errArr["passError"] = "Password must include one uppercase, lowercase and special characters";
                        } else {
                            if($_POST["newpass"] === $_POST["verifynewpass"]) {
                                $password = password_hash($_POST["newpass"], PASSWORD_DEFAULT);
                            } else {
                                $errArr["verifyPassError"] = "Passwords don't match";
                            }
                        }
                    } else {
                        $errArr["oldPassError"] = "Old password is incorrect!";
                    }
                }

                // Photo Upload System

                $target_dir = $_SERVER['DOCUMENT_ROOT']."/cms/uploads/users/";
                $target_file = $target_dir.basename($_FILES["image"]["name"]);
                $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
                
                if (!empty($_FILES["image"]["name"])) {
                    $checkPhoto = getimagesize($_FILES["image"]["tmp_name"]);
                    if ($checkPhoto !== false) {
                        if ($_FILES["image"]["size"] < 500000000) {
                            if ($fileType == "jpg" || $fileType == "jpeg" || $fileType == "png") {
                                if (!file_exists($target_file)) {
                                    if(count($errArr) == 0) {
                                        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                                        $image = basename($_FILES["image"]["name"]);
                                        } else {
                                        $errArr["photoError"] = "Sorry, there was an error uploading your file.";
                                        }
                                    }
                                } else {
                                    $image = basename($_FILES["image"]["name"]);
                                }
                            } else {
                                $errArr["photoError"] = "Sorry, only JPG, JPEG & PNG files are allowed.";
                            }
                        } else {
                            $errArr["photoError"] = "Sorry, your file is too large.";
                        }
                    } else {
                        $errArr["photoError"] = "File is not an image.";
                    }
                }

                if(count($errArr) == 0) {
                    $sql = "UPDATE users SET fname=:fname, lname=:lname, ";
                    if(isset($username)) {
                        $sql .= "username=:username, ";
                    }
                    if(isset($password)) {
                        $sql .= "password=:password, ";
                    }
                    if(isset($email)) {
                        $sql .= "email=:email, ";
                    }
                    if(isset($image)) {
                        $sql .= "image=:image, ";
                    }
                    $sql .= "bdate=:bdate WHERE user_id=:id;";
                    $stmt = $this->conn->prepare($sql);

                    if(isset($username)) {
                        $stmt->bindParam(':username', $username);
                        $_SESSION["username"] = $username;
                    }
                    if(isset($password)) {
                        $stmt->bindParam(':password', $password);
                    }
                    if(isset($email)) {
                        $stmt->bindParam(':email', $email);
                    }
                    if(isset($image)) {
                        $stmt->bindParam(':image', $image);
                    }
                    $stmt->bindParam(':fname', $fname);
                    $_SESSION["fname"] = $fname;
                    $stmt->bindParam(':lname', $lname);
                    $_SESSION["lname"] = $lname;
                    $stmt->bindParam(':bdate', $bdate);
                    $stmt->bindParam(':id', $id);
                    $stmt->execute();
                    $link = "profile.php?success=Information was updated successfully!";
                    return header("Location: /cms/admin/$link");
                } else {
                    $link = "profile.php?".http_build_query($errArr);
                    return header("Location: /cms/admin/$link");
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
    function check($user_id) {
        $sql = "SELECT COUNT(username) as count FROM users WHERE user_id=? LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$user_id]);
        $count = $stmt->fetch(PDO::FETCH_ASSOC);
        return $count["count"];
    }

    /* This method displays username */
    function title($id) {
        $sql = "SELECT username FROM users WHERE user_id=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        $this->title = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->title = $this->title["username"];
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