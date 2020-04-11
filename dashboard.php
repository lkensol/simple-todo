<?php
//start session
session_start();
// print_r($_SESSION);
require_once "templates/header.php";

//including the database connection file
require_once("objects/user.php");
require_once("objects/project.php");
require_once("objects/task.php");
require_once("objects/validation.php");

//Initialize user class
$user = new User();
$pr = new Project();
$projects = $pr->get($_SESSION['user_session']);

// print_r($projects);

if(!$user->is_loggedin()) {
    $user->redirect('index.php');
}

?>


<nav class="navbar navbar-projects navbar-expand-lg fixed-top bg-white border-bottom shadow-sm">
    <a class="navbar-brand my-2 mr-md-auto" href="#">Simple ToDo</a>

    <form class="form-inline" action="logout.php" method="post">
        <input type='hidden' name='logout' value='logout'>
        <button class="btn btn-outline-dark-blue my-2 my-sm-0" type="submit">Log out</button>
    </form>
</nav>

<main role="main" class="container dashboard">
	<div id="projects">
		<?php if(!empty($projects)): ?>
			<?php foreach($projects as $project): ?>
				<div class="project">
					<div class="project_title d-flex align-items-center p-3 mt-3 text-white bg-blue">
						<div class="project_title-left mr-md-auto">
							<i class="fas fa-list-alt"></i>
							<span class="project-title-info"><?=$project['name'] ?></span>
						</div>
						<div class="project_title-right">						
							<a href="#" projectId="<?=$project['id_project']; ?>" class="edit-project btn"><i class="icon fas fa-pen"></i></a>
							<a href="javascript:void(0);" class="" onclick="return confirm('Are you sure to delete data?')?projectAction('delete', '<?=$project['id_project']; ?>'):false;"><i class="icon fas fa-trash-alt"></i></a>
						</div>
					</div>
					<div class="project-input d-flex align-items-center p-3 text-white bg-grey">
						<i class="fas fa-plus"></i>
						<div class="input-group">
							<input type="text" class="form-control" placeholder="Start typing here to create a task..." aria-label="Task title" aria-describedby="task-title">
							<div class="input-group-append">
								<span class="input-group-text" id="task-title" onclick="taskAction('add', <?=$project['id_project']; ?>, $(this).parent().parent().find('.form-control').val())">Add task</span>
							</div>
						</div>
					</div>
					<div class="project-table bg-white">
						<table class="table table-projects">
							<tbody>
								<?php if(!empty($project['tasks'])): ?>
									<?php foreach($project['tasks'] as $task): ?>
										<tr>
											<th class="text-center task-check" style="width:  8.33%">
												<input data-index="0"  name="selectItem" type="checkbox" <?php if($task['status'] == 1) { echo 'checked';} ?> value="0">
											</th>
											<th class ="task-text" ><?=$task['name'] ?></th>
											<th class="text-center" style="width: 16.66%">
												<a href="#" taskId="<?=$task['id_task']; ?>" projectId="<?=$project['id_project']; ?>" class="edit-task" class="btn"><i class="icon fas fa-pen"></i></a>
												<a href="javascript:void(0);" onclick="return confirm('Are you sure to delete data?')?taskAction('delete', '<?php echo $task['id_task']; ?>'):false;"><i class="icon fas fa-trash-alt"></i></a>
											</th>
										</tr>
									<?php endforeach; ?>
								<?php endif; ?>
							</tbody>
						</table>
					</div>
				</div>
			<?php endforeach; ?>
		<?php endif; ?>
	</div>
	
    <div class="text-center mt-5">
        <button type="button" class="btn btn-dark-blue" data-toggle="modal" data-target="#addList"><i class="fas fa-plus"></i> Add TODO List</button>
    </div>

</main>

<!-- Modal -->
<div class="modal fade" id="addList" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Add TODO List</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form>
					<div class="form-group">
						<label for="projectName">List name</label>
						<input class="form-control" type="text" placeholder="Enter name" id="projectName">
					</div>
					
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
						<button type="button" class="btn btn-dark-blue" id="submit">Submit</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<?php require_once "templates/footer.php"; ?>

<script>

	toastr.options = {
	"closeButton": true,
	"debug": false,
	"newestOnTop": true,
	"progressBar": false,
	"positionClass": "toast-top-right",
	"preventDuplicates": false,
	"onclick": null,
	"showDuration": "300",
	"hideDuration": "1000",
	"timeOut": "5000",
	"extendedTimeOut": "1000",
	"showEasing": "swing",
	"hideEasing": "linear",
	"showMethod": "fadeIn",
	"hideMethod": "fadeOut"
	}

	$('body').on('click', '.edit-project', function(){
		var id = $(this).attr('projectId');
		var text = $(this).parent().parent().find('.project-title-info').text();
		var input = $('<input id="attribute" type="text" value="' + text + '" />')
		$(this).parent().parent().find('.project-title-info').text('').append(input);
		input.select();

		input.blur(function() {

			var text = $('#attribute').val();
			projectAction('update', id, text);
			
			$('#attribute').parent().text(text);
			$('#attribute').remove();
		});
	});

	$('body').on('click', '.edit-task', function(){
		var status;
		var id = $(this).attr('taskId');
		var project_id = $(this).attr('projectId');
		var checkbox = $(this).parent().parent().find("input[type='checkbox']");
		if ( checkbox.is(':checked') ) {
			$status = 1;
		} else {
			$status = 0;
		}		
		
		var text = $(this).parent().parent().find('.task-text').text();
		var input = $('<input id="attribute" type="text" value="' + text + '" />')
		$(this).parent().parent().find('.task-text').text('').append(input);
		input.select();

		input.blur(function() {
			var text = $('#attribute').val();
			taskAction('update', id, text, project_id, $status);
			
			$('#attribute').parent().text(text);
			$('#attribute').remove();
		});
	});

	$('body').on('change', "input[type='checkbox']", function(){
		var id = $(this).parent().parent().find('.edit-task').attr('taskId');
		var text = $(this).parent().parent().find('.task-text').text();
		var project_id = $(this).parent().parent().find('.edit-task').attr('projectId');

    	if(this.checked) {
        	$status = 1;
		} else {
			$status = 0;
		}

		taskAction('update', id, text, project_id, $status);
	});

	function getProjects() {
		data = 'project=view';
		$.ajax({
			type: 'post',
			url: 'projects.php',
			data: data,
			success:function(html){				
				$('#projects').html(html);
			}
		});
	}

	function projectAction(type, id, name){
		id = (typeof id == "undefined")?'': id;
		var data = '';
		if(type == 'add'){
			// name
			data = 'project='+type+'&name='+name;
		}else if (type == 'update'){
			// name, project_id
			data = 'project='+type+'&name='+name+'&project_id='+id;
		}else{
			// project_id
			data = 'project='+type+'&project_id='+id;
		}
		$.ajax({
			type: 'post',
			url: 'projects.php',
			data: data,
			dataType: 'json',
			success:function(res){	
				if(res.success) {
					toastr["success"](res.msg);
				} else {
					toastr["error"](res.msg);
				}	
				getProjects();
			}
		});
	}

	function taskAction(type, id, name, project_id, status) {
		id = (typeof id == "undefined")?'': id;
		var data = '';
		if(type == 'add'){
			// name, project_id
			data = 'task='+type+'&project_id='+id+'&name='+name;
		}else if (type == 'update'){
			// task_id, name, project_id, status
			data = 'task='+type+'&task_id='+id+'&project_id='+project_id+'&name='+name+'&status='+status;
		}else{
			// task_id
			data = 'task='+type+'&task_id='+id;
		}
		$.ajax({
			type: 'post',
			url: 'tasks.php',
			data: data,
			dataType: 'json',
			success: function(res) {
				if(res.success) {
					toastr["success"](res.msg);
				} else {
					toastr["error"](res.msg);
				}
				
				getProjects();
			}
		});
	}

	$('#submit').click(function() {
		var name = $('#projectName').val();
		projectAction('add', '', name);
	});

	// Actions on modal show and hidden events
	$(function(){
		
		$('#addList').on('hidden.bs.modal', function(){
			$('#submit').attr("onclick", "");
			$(this).find('form')[0].reset();
		});
	});
	
</script>