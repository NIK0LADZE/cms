<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT']."/cms/includes/db.php");

if(isset($_POST["sign_in"])) {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        openConn();
        $username = $_POST["username"];
        $password = $_POST["password"];
        $sql = "SELECT * FROM users WHERE username='$username' LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
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