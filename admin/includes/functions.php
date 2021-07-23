<?php

/* Posts */

function displayPosts($startPostsFrom, $postPerPage) {
    openConn();
    global $conn;
    $sql = "SELECT post_id as id, post_author as author, post_title as title, post_category_id as category, 
    post_image as image, post_tags as tags, post_views as views, post_comment_count as comments, post_date as date FROM posts ORDER BY post_date DESC LIMIT {$startPostsFrom}, {$postPerPage}";
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
            $title = $post["title"];
            $category = $post["category"];
            $author = $_SESSION["username"];
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
                openConn();
                global $conn;
                $query = "INSERT INTO posts(post_title, post_author, post_category_id, post_image, post_tags, post_comment_count, post_content) ";
                $query .= "VALUES('$title', '$author', '$category', '$image', '$tags', '4', '$content')";
                $conn->exec($query);
                echo "<h3 style='color: green;'>Post was published succesfully!</h3>";
                return $conn = 0;
            } else {
                $alert = "<p style='color: red;'>".$photoError."</p>";
                echo $alert;
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
            $tags = $post["tags"];
            $content = $post["content"];

            // Photo Upload System
        
            $target_dir = $_SERVER['DOCUMENT_ROOT']."/cms/uploads/";
            $target_file = $target_dir.basename($files["image"]["name"]);
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
                } else {
                    $photoError = "File is not an image.";
                }
            }

            if(!isset($photoError)) {
                $query = "UPDATE posts SET post_title='$title', post_category_id='$category', ";
                if(isset($image)) {
                    $query .= "post_image='$image', ";
                }
                $query .= "post_tags='$tags', post_content='$content' ";
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

?>