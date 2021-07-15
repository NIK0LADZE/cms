<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT']."/cms/includes/db.php");
if(isset($_POST["update_user"])) {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        function checkData($data, $dataName, $id, &$errArr) {
            openConn();
            global $conn;
            $query = "SELECT ".$dataName." FROM users WHERE ".$dataName."='$data' AND NOT id={$id} LIMIT 1";
            $stmt = $conn->prepare($query);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if($stmt->rowCount()) {
                if($result[$dataName] === $data) {
                    $errArr[$dataName."Error"] = "This ".$dataName." already exists";
                }
                $conn = 0;
                return false;
            } else {
                $conn = 0;
                return true;
            }
        }

        function checkPass($oldpass, $id) {
            openConn();
            global $conn;
            $sql = "SELECT password FROM users WHERE id='$id' LIMIT 1";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if(password_verify($oldpass, $result["password"])) {
                $conn = 0;
                return true;
            } else {
                $conn = 0;
                return false;
            }
        }

        $errArr = [];
        $id = $_POST["id"];
        $fname= $_POST["fname"];
        $lname= $_POST["lname"];
        $bdate = $_POST["bdate"];

        if(checkData($_POST["username"], "username", $_POST["id"], $errArr)) {
            $username = $_POST["username"];
        }

        if(checkData($_POST["email"], "email", $_POST["id"], $errArr)) {
            $email = $_POST["email"];
        }

        if(!empty($_POST["oldpass"])) {
            if(checkPass($_POST["oldpass"], $_POST["id"])) {
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
            openConn();
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
            $sql .= "bdate=:bdate WHERE id={$id};";
            $stmt = $conn->prepare($sql);

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
            $stmt->execute();
            $conn = 0;
            $link = "profile.php?success=Information was updated successfully!";
            return header("Location: /cms/admin/$link");
        } else {
            $link = "profile.php?".http_build_query($errArr);
            return header("Location: /cms/admin/$link");
        }
    }
}