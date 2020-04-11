<?php
session_start();

require_once("objects/task.php");
require_once("objects/validation.php");

//Initialize class
$task = new Task();

if(isset($_POST['task'])) {

    $post = $_POST['task'];
    $user_id = $_SESSION['user_session'];

    if($post == 'add') {
        $msg = '';
        $status = $verr = 0;

        $name = $_POST['name']; 
        $project_id = $_POST['project_id']; 

        if(empty($name)){
            $verr = 1;
            $msg .= 'Some problem occurred, please try again.<br/>';
        }

        if(empty($project_id)){
            $verr = 1;
            $msg .= 'Please enter your name.<br/>';
        }
        
        if($verr == 0) {
            $update = $task->create($name, $project_id, $user_id);
            if($update) {
                $status = 1;
                $msg .= 'Task has been added successfully.';
            } else {
                $msg .= 'Some problem occurred, please try again.';
            }
        } else {
            $msg .= 'Some problem occurred, please try again.';
        }
                
        // Return response as JSON format
        $response = array(
            'success' => $status,
            'msg' => $msg
        );
        echo json_encode($response);
    } elseif ($post == 'update') {
        $msg = '';
        $status = $verr = 0;

        $id_task = $_POST['task_id'];
        $name = $_POST['name']; 
        $project_id = $_POST['project_id']; 
        $project_status = $_POST['status']; 
        
        if(empty($id_task)){
            $verr = 1;
            $msg .= 'Some problem occurred, please try again.<br/>';
        }

        if(empty($name)){
            $verr = 1;
            $msg .= 'Some problem occurred, please try again.<br/>';
        }

        if(empty($project_id)){
            $verr = 1;
            $msg .= 'Please enter your name.<br/>';
        }
        
        if($verr == 0) {
            $update = $task->update($id_task, $name, $project_status);
            if($update) {
                $status = 1;
                $msg .= 'Task has been updated successfully.';
            } else {
                $msg .= 'Some problem occurred, please try again.';
            }
        } else {
            $msg .= 'Some problem occurred, please try again.';
        }
                
        // Return response as JSON format
        $response = array(
            'success' => $status,
            'msg' => $msg
        );
        echo json_encode($response);

    } elseif ($post == 'delete') {
        $msg = '';
        $status = 0;
        if(!empty($_POST['task_id'])) {
            $id_task = $_POST['task_id'];
            $delete = $task->delete($id_task);
            if($delete) {
                $status = 1;
                $msg .= 'Task has been deleted successfully.';
            } else {
                $msg .= 'Some problem occurred, please try again.';
            } 
        } else {
            $msg .= 'Some problem occurred, please try again.';
        }

        // Return response as JSON format
        $response = array(
            'success' => $status,
            'msg' => $msg
        );
        echo json_encode($response);
        
    } 
        
} 