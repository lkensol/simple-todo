<?php
session_start();

require_once("objects/project.php");
require_once("objects/validation.php");

//Initialize class
$project = new Project();

if(isset($_POST['project'])) {

    $post = $_POST['project'];
    $user_id = $_SESSION['user_session'];

    if($post == 'add') {
        $msg = '';
        $status = $verr = 0;

        $name = $_POST['name']; 

        if(empty($name)){
            $verr = 1;
            $msg .= 'Some problem occurred, please try again.<br/>';
        }
        
        if($verr == 0) {
            $add = $project-> create($user_id, $name);
            if($add) {
                $status = 1;
                $msg .= 'Project has been added successfully.';
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

        $name = $_POST['name']; 
        $id_project = $_POST['project_id']; 
        
        if(empty($name)){
            $verr = 1;
            $msg .= 'Some problem occurred, please try again.<br/>';
        }

        if(empty($id_project)){
            $verr = 1;
            $msg .= 'Please enter your name.<br/>';
        }
        
        if($verr == 0) {
            $update = $project->update($id_project, $name);
            if($update) {
                $status = 1;
                $msg .= 'Project has been updated successfully.';
            } else {
                $msg .= 'Project has been not updated.';
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
        $id_project = $_POST['project_id'];
        $delete = $project->delete($id_project);
        if($delete) {
            $status = 1;
            $msg .= 'Project has been deleted successfully.';
        } else {
            $msg .= 'Some problem occurred, please try again.';
        }
        
        // Return response as JSON format
        $response = array(
            'success' => $status,
            'msg' => $msg
        );
        echo json_encode($response);
    } elseif ($post == 'view') {
        $projects = $project->get($user_id);

        if(!empty($projects)){
            foreach($projects as $project) {
                echo '<div class="project">';
                echo '<div class="project_title d-flex align-items-center p-3 mt-3 text-white bg-blue">';
                echo '<div class="project_title-left mr-md-auto">';
                echo '<i class="fas fa-list-alt"></i>';
                echo '<span class="project-title-info">'.$project['name'].'</span></div>';
                echo '<div class="project_title-right">';					
                echo '<a href="#" projectId="'.$project['id_project'].'" class="edit-project btn"><i class="icon fas fa-pen"></i></a>
                <a href="javascript:void(0);" onclick="return confirm(\'Are you sure to delete data?\')?projectAction(\'delete\', \''.$project['id_project'].'\'):false;"><i class="icon fas fa-trash-alt"></i></a>';
                echo '</div>';
                echo '</div>';
                echo '<div class="project-input d-flex align-items-center p-3 text-white bg-grey">';
                echo '<i class="fas fa-plus"></i>';
                echo '<div class="input-group">';
                echo '<input type="text" class="form-control" placeholder="Start typing here to create a task..." aria-label="Task title" aria-describedby="task-title">';
                echo '<div class="input-group-append">';
                echo '<span class="input-group-text" id="task-title" onclick="taskAction(\'add\', '.$project['id_project'].', $(this).parent().parent().find(\'.form-control\').val())">Add task</span>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
                echo '<div class="project-table bg-white">';
                echo '<table class="table table-project">';
                echo '<tbody class="tbody-project" id="project-'.$project['id_project'].'">';
                    if(!empty($project['tasks'])){
                        foreach($project['tasks'] as $task){
                            echo '<tr data-id="'.$task['id_task'].'">';
                            echo '<th class="text-center task-check" style="width:  8.33%">';
                            echo '<input data-index="0"  name="selectItem" type="checkbox" ';
                            if($task['status'] == 1) { echo 'checked';};
                            echo' value="0">';
                            echo '</th>';
                            echo '<th class ="task-text" >'.$task['name'].'</th>';
                            echo '<th class="text-center" style="width: 16.66%">';
                            echo '<a href="#" class="" class="btn"><i class="fas fa-sort icon handle"></i></a>';
                            echo '<a href="#" taskId="'.$task['id_task'].'" projectId="'.$project['id_project'].'" class="edit-task" class="btn"><i class="icon fas fa-pen"></i></a>
                            <a href="javascript:void(0);" onclick="return confirm(\'Are you sure to delete data?\')?taskAction(\'delete\', \''.$task['id_task'].'\'):false;"><i class="icon fas fa-trash-alt"></i></a>';
                            echo '</th>';
                            echo '</tr>';
                        }
                    }
                    echo '</tbody>';
                    echo '</table>';
                    echo '</div>';
                    echo '</div>';
            }
        }
    }
        
} 