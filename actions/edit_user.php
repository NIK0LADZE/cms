<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT']."/cms/database/users.php");
$users = new Users();
$users->edit();