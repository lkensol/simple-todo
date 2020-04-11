<?php
session_start();

require_once("objects/user.php");
require_once("objects/validation.php");

//Initialize class
$user = new User();
$validation = new Validation();

if(isset($_POST['login'])) {

    $name = $_POST['username'];
    $pwd = $_POST['password'];

    $msg = $validation->check_empty($_POST, array('username', 'password'));
    
    if($msg) {
        echo json_encode(array('success' => 0, 'msg' => $msg));
    } else {
        $login = $user->login($name, $pwd);
        if ($login) {
            // login Success
            echo json_encode(array('success' => 1, 'msg' => 'Data is correct!'));
	    } else {
            // Login Failed
            echo json_encode(array('success' => 0, 'msg' => 'Incorrect login or password'));
	    }
    }    
}