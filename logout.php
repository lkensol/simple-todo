<?php
session_start();
//including the database connection file
require_once("objects/user.php");

//Initialize user class
$user = new User();

if(isset($_POST['logout'])) {
    $user->logout();
    $user->redirect("index.php");
}
