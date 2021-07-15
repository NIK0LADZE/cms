<?php
require_once($_SERVER['DOCUMENT_ROOT']."/cms/includes/db.php");

if(isset($_POST["sign_up"])) {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        function checkUser($username, $email, &$errArr) {
            openConn();
            global $conn;
            $query = "SELECT username, email FROM users WHERE username=? OR email=? LIMIT 1";
            $stmt = $conn->prepare($query);
            $stmt->execute([$username, $email]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if($stmt->rowCount()) {
                if($result["username"] === $username) {
                    $errArr["usernameError"] = "This username already exists";
                }
        
                if($result["email"] === $email) {
                    $errArr["emailError"] = "User with this email is already registered";
                }
                $conn = 0;
                return false;
            } else {
                $conn = 0;
                return true;
            }
        }
        
        $errArr = [];
        $succArr = [];
        
        if(checkUser($_POST["username"], $_POST["email"], $errArr)) {
            $succArr["username"] = $username = $_POST["username"];
        }
        $succArr["fname"] = $fname= $_POST["fname"];
        $succArr["lname"] = $lname = $_POST["lname"];
        if (strlen($_POST["pass1"]) < 8) {
            $errArr["passError"] = "Password must be at least 8 characters in length";
        } elseif (!preg_match("/^\S*(?=\S*[a-z])(?=\S*[\W])(?=\S*[A-Z])(?=\S*[\d])\S*$/", $_POST["pass1"])) {
            $errArr["passError"] = "Password must include one uppercase, lowercase and special characters";
        } else {
            $pass1 = $_POST["pass1"];
        }
        $role = "Subscriber";
        if(isset($pass1)) {
            if($_POST["pass2"] !== $pass1) {
                $errArr["verifyPassError"] = "Passwords don't match";
            } else {
                $password = password_hash($pass1, PASSWORD_DEFAULT);
            }
        }
        $succArr["bdate"] = $bdate = $_POST["bdate"];
        // $bdate = explode("-", $bdate);
        // $bdate = array_reverse($bdate);
        // $bdate = implode(".", $bdate);
        if(checkUser($_POST["username"], $_POST["email"], $errArr)) {
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

        if(count($errArr) == 0) {
            if(!isset($image)) {
                $image = "no-photo.png";
            }
            openConn();
            $sql = "INSERT INTO users(username, fname, lname, password, bdate, email, image, role) ";
            $sql .= "VALUES(:username, :fname, :lname, :password, :bdate, :email, :image, :role)";
            $stmt = $conn->prepare($sql);

            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':fname', $fname);
            $stmt->bindParam(':lname', $lname);
            $stmt->bindParam(':password', $password);
            $stmt->bindParam(':bdate', $bdate);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':image', $image);
            $stmt->bindParam(':role', $role);

            $stmt->execute();
            $conn = 0;
            $link = "sign-up.php?success=Registration was successful!";
            return header("Location: ../$link");
        } else {
            $link = "sign-up.php?".http_build_query($errArr)."&".http_build_query($succArr);
            return header("Location: ../$link");
        }
    }
}
