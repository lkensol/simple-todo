<?php
session_start();

require_once("objects/user.php");
require_once("objects/validation.php");

//Initialize class
$user = new User();
$validation = new Validation();

if(isset($_POST['do_register'])) {

    $name = $_POST['username'];
    $pwd = $_POST['password'];

    $msg = $validation->check_empty($_POST, array('username', 'password'));

    $check_name = $validation->isNameValid($name);
    $check_pwd = $validation->idPasswordValid($pwd);
    
    if($msg) {
        echo json_encode(array('success' => 0, 'msg' => $msg));
    } elseif(!$check_name) {
        echo json_encode(array('success' => 0, 'msg' => 'Name must be between 3 and 16 characters'));
    }elseif(!$check_pwd) {
        echo json_encode(array('success' => 0, 'msg' => 'Password must be between 6 and 16 characters'));
    } else {
        $res = $user->register($name, $pwd);
        if ($res) {
            echo json_encode(array('success' => 1, 'msg' => $res));
        } else {
            echo json_encode(array('success' => 0, 'msg' => 'This login is busy'));
        }
        
    }    
} 