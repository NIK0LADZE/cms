<?php

function delete($table, $id) {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        openConn();
        global $conn;
        $delete = "DELETE FROM ".$table." where id={$id}";
        $conn->exec($delete);
        return $conn = 0;
    }
}

function countData($table, $rowName) {
    openConn();
    global $conn;
    $query = "SELECT COUNT(".$rowName.") as count FROM ".$table;
    $countData = $conn->prepare($query);
    $countData->execute();
    $count = $countData->fetch(PDO::FETCH_ASSOC);
    echo $count["count"];
}

/* Comments */

function displayComments() {
    openConn();
    global $conn;
    $sql = "SELECT comments.id, posts.post_id as post_id, comments.comment_author as author, posts.post_title as title, comments.comment_content as content, comment_date as date FROM comments INNER JOIN posts ON comments.post_id=posts.post_id";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $comments = $stmt->fetchAll();
    $conn = null;
    foreach ($comments as $comment) { ?>
        <tr>
            <?php foreach ($comment as $key => $value) { ?>
                <?php if($key == "author") { ?>
                    <td><p><a href="/cms/author.php?author=<?=$comment["author"]?>"><?=$value?></a></p></td>
                <?php } elseif($key == "title") { ?>
                    <td><p><a href="/cms/post.php?post_id=<?=$comment["post_id"]?>"><?=$value?></a></p></td>
                <?php } elseif($key !== "post_id") { ?>
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

/* Users */

function setUserRole($post) {
    if(isset($post["set_role"])) {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            openConn();
            global $conn;
            $id = $post["set_role"];
            $role = $post["role_for_user_".$id];
            $sql = "UPDATE users SET role='$role' WHERE id={$id}";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            return $conn = 0;
        }
    }
}

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

function displayUsers() {
    openConn();
    global $conn;
    $sql = "SELECT id, username, fname, lname, bdate, email, image, role FROM users";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $users = $stmt->fetchAll();
    foreach ($users as $user) { ?>
        <tr>
            <?php 
            foreach ($user as $key => $value) { 
                if($key == "image") { ?>
                    <td><p><img width="50px;" height="50px;" src="/cms/uploads/users/<?php echo $value;?>" alt="User image"></p></td>
                <?php } elseif($key == "role") { ?>
                    <td>
                        <?php if($_SESSION["role"] === "Admin") { ?>
                        <select name="role_for_user_<?php echo $user['id'];?>">
                            <?php displayRoles($user["role"]); ?>
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
    return $conn = 0;
}

/* Posts */

function displayPosts() {
    openConn();
    global $conn;
    $sql = "SELECT post_id as id, post_author as author, post_title as title, post_category_id as category, 
    post_image as image, post_tags as tags, post_views as views, post_comment_count as comments, post_date as date FROM posts";
    $row = $conn->query($sql);
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $posts = $stmt->fetchAll();
    foreach ($posts as $post) { ?>
        <tr>
            <?php 
            foreach ($post as $key => $value) { 
                if($key == "image") { ?>
                    <td><p><img width="120vw;" height="40vh;" src="/cms/uploads/<?php echo $value;?>" alt="Post image"></p></td>
                <?php } elseif($key == "comments") { 
                    $post_id = $post["id"];
                    $query = "SELECT COUNT(id) as count FROM comments WHERE post_id='$post_id'";
                    $commentCount = $conn->prepare($query);
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
    return $conn = 0;
}

function clonePost($post) {
    if(isset($post["clone_post"])) {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            openConn();
            global $conn;
            $id = $post["post_id"];
            $sql = "INSERT INTO posts(post_author, post_title, post_category_id, post_image, post_tags, post_comment_count, post_content) 
            SELECT post_author, post_title, post_category_id, post_image, post_tags, post_comment_count, post_content FROM posts WHERE post_id={$id}";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            return $conn = 0;
        }
    }
}

function delPost($post) {
    if(isset($post["delete_post"])) {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            openConn();
            global $conn;
            $id = $post["post_id"];
            $del_post_query = "DELETE FROM posts WHERE post_id={$id}";
            $del_comment_query = "DELETE FROM comments WHERE post_id={$id}";
            $del_post = $conn->prepare($del_post_query);
            $del_comment = $conn->prepare($del_comment_query);
            $del_post->execute();
            $del_comment->execute();
            return $conn = 0;
        }
    }
}

function addPost($post, $files) {
    if(isset($post["add_post"])) {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            openConn();
            global $conn;
            $title = $post["title"];
            $category = $post["category"];
            $author = $_SESSION["username"];
            $status = $post["status"];
            $tags = $post["tags"];
            $content = $post["content"];

            // Photo Upload System
        
            $target_dir = $_SERVER['DOCUMENT_ROOT']."/cms/uploads/";
            $target_file = $target_dir.basename($files["image"]["name"]);
            $uploadOk = 0;
            $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            if (!empty($files["image"]["name"])) {
                $checkPhoto = getimagesize($files["image"]["tmp_name"]);
                if ($checkPhoto !== false) {
                    if ($files["image"]["size"] < 500000000) {
                        if ($fileType == "jpg" || $fileType == "jpeg" || $fileType == "png") {
                            if (!file_exists($target_file)) {
                                if (move_uploaded_file($files["image"]["tmp_name"], $target_file)) {
                                $image = basename($files["image"]["name"]);
                                } else {
                                $photoError = "Sorry, there was an error uploading your file.";
                                }
                            } else {
                                $photoError = "Sorry, file already exists.";
                                echo $photoError;
                            }
                        } else {
                            $photoError = "Sorry, only JPG, JPEG & PNG files are allowed.";
                        }
                    } else {
                        $photoError = "Sorry, your file is too large.";
                    }
                    $uploadOk = 1;
                } else {
                    $photoError = "File is not an image.";
                }
            }

            if(!isset($photoError)) {
                if(!isset($image)) {
                    $image = "no-photo.png";
                }
                $query = "INSERT INTO posts(post_title, post_author, post_category_id, post_status, post_image, post_tags, post_comment_count, post_content) ";
                $query .= "VALUES('$title', '$author', '$category', '$status', '$image', '$tags', '4', '$content')";
                $conn->exec($query);
                echo "<h3 style='color: green;'>Post was published succesfully!</h3>";
                return $conn = 0;
            }
        }
    }
}

function editPost($post, $files, $id) {
    if(isset($post["add_post"])) {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            openConn();
            global $conn;
            $title = $post["title"];
            $category = $post["category"];
            $status = $post["status"];
            $tags = $post["tags"];
            $content = $post["content"];

            // Photo Upload System
        
            $target_dir = $_SERVER['DOCUMENT_ROOT']."/cms/uploads/";
            $target_file = $target_dir.basename($files["image"]["name"]);
            $uploadOk = 0;
            $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            if (!empty($files["image"]["name"])) {
                $checkPhoto = getimagesize($files["image"]["tmp_name"]);
                if ($checkPhoto !== false) {
                    if ($files["image"]["size"] < 500000000) {
                        if ($fileType == "jpg" || $fileType == "jpeg" || $fileType == "png") {
                            if (!file_exists($target_file)) {
                                if (move_uploaded_file($files["image"]["tmp_name"], $target_file)) {
                                $image = basename($files["image"]["name"]);
                                } else {
                                $photoError = "Sorry, there was an error uploading your file.";
                                }
                            } else {
                                $image = basename($files["image"]["name"]);
                            }
                        } else {
                            $photoError = "Sorry, only JPG, JPEG & PNG files are allowed.";
                        }
                    } else {
                        $photoError = "Sorry, your file is too large.";
                    }
                    $uploadOk = 1;
                } else {
                    $photoError = "File is not an image.";
                }
            }

            if(!isset($photoError)) {
                if(!isset($image)) {
                    $image = "no-photo.png";
                }
                $query = "UPDATE posts SET post_title='$title', post_category_id='$category', ";
                $query .= "post_status='$status', post_image='$image', post_tags='$tags', post_content='$content' ";
                $query .= "WHERE post_id='$id'";
                $conn->exec($query);
                echo "<h3 style='color: green;'>Post was editted succesfully!</h3>";
                return $conn = 0;
            }
        }
    }
}

function displayOptions($selected) {
    openConn();
    global $conn;
    $sql = "SELECT cat_title FROM categories";
    $row = $conn->query($sql);
    while ($result = $row->fetch(PDO::FETCH_ASSOC)) { 
        if(isset($selected) && $selected == $result["cat_title"]) { ?>
            <option value="<?php echo $result["cat_title"];?>" selected><?php echo $result["cat_title"];?></option>
        <?php } else { ?>
        <option value="<?php echo $result["cat_title"];?>"><?php echo $result["cat_title"];?></option>
    <?php }
    }
    return $conn = 0;
}

/* Categories */

function displayCats() {
    openConn();
    global $conn;
    $sql = "SELECT * FROM categories";
    foreach ($conn->query($sql) as $cat) { ?>
        <tr>
            <td><p><?php echo $cat['id'];?></p></td>
            <td id="title">
                <p id="<?php echo $cat['cat_title'];?>"><a href="/cms/category.php?category_title=<?=$cat['cat_title']?>"><?=$cat['cat_title']?></a></p>
                <form action="" method="post">
                    <input type="hidden" name="old" value="<?php echo $cat['cat_title'];?>">
                    <input type="hidden" name="new" class="edit" id="<?php echo $cat['cat_title'].'_for_input';?>" value="<?php echo $cat['cat_title'];?>">
                </form>
            </td>
            <?php if($_SESSION["role"] === "Admin" || $_SESSION["role"] === "Moderator") { ?>
                <td style="width: 10%; text-align: center;">
                    <button onclick="document.getElementById('<?php echo $cat['cat_title'];?>').style.display = 'none'; document.getElementById('<?php echo $cat['cat_title'];?>_for_input').setAttribute('type', 'text');" name="edit" type="submit"><i class="far fa-edit"></i> Edit</button>
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
    return $conn = 0;
}

function addCat($data) {
    if(isset($data["addcat"])) {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if(!empty($data["cat_title"])) {
                openConn();
                global $conn;
                $catTitle = $data["cat_title"];
                $checkTitle = "SELECT * FROM categories WHERE cat_title='$catTitle' LIMIT 1";
                $count = $conn->query($checkTitle)->rowCount();
                if($count == 1) {
                    echo "<p style='color: red; margin: 0;'>This category already exists</p>";
                    return $conn = 0;
                } else {
                    $query = "INSERT INTO categories(cat_title) VALUES('$catTitle')";
                    $conn->exec($query);
                    return $conn = 0;
                }
            } else {
                echo "<p style='color: red; margin: 0;'>This field should not be empty</p>";
                return $conn = 0;
            }
        }
    }
}

function updateCat($title) {
    if(isset($title["new"])) {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            openConn();
            global $conn;
            $oldCatTitle = $title["old"];
            $newCatTitle = $title["new"];
            if(!empty($newCatTitle)) {
                $updateCat = "UPDATE categories SET cat_title='$newCatTitle' WHERE cat_title='$oldCatTitle'";
                $conn->exec($updateCat);
                $updatePosts = "UPDATE posts SET post_category_id='$newCatTitle' WHERE post_category_id='$oldCatTitle'";
                $conn->exec($updatePosts);
                return $conn = 0;
            } else {
                echo "<p style='text-align: center; color: red; margin: 0;'>New value shoud not be empty</p>";
                return $conn = 0;
            }
        }
    }
}

?>