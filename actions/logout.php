<?php
session_start();
$_SESSION = [];
header("Location: ".$_SERVER["HTTP_REFERER"]);