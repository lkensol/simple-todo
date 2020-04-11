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

if($user->is_loggedin()!="")
{
 	$user->redirect('dashboard.php');
}

?>

<form class="form-signin">
<div class="text-center mb-4">
	<img class="mb-4" id="icon" src="assets/img/user.png" alt="user icon">    
</div>

<div class="alert alert-danger" role="alert">    
</div>

<div class="form-label-group">
	<input type="username" id="inputUsername" class="form-control" placeholder="Login" required autofocus="">
	<label for="inputUsername">Login</label>
</div>

<div class="form-label-group">
	<input type="password" id="inputPassword" class="form-control" placeholder="Password" required>
	<label for="inputPassword">Password</label>
</div>

<button class="btn btn-lg btn-primary btn-block" type="submit" id="submit">Submit</button>
<div class="text-center mt-3">
	<a class="underlineHover" href="register.php">Register?</a>
</div>

</form>

<?php require_once "templates/footer.php"; ?>

<script>
$(document).ready(function () {

	$('.alert').hide();  

	$('#submit').click( function (e) {
		e.preventDefault();

		$('.alert').empty();
		$('.alert').hide();

		var pwd = $('#inputPassword').val();
		var name = $('#inputUsername').val();
		if(pwd !='' && name !='') {
			$.ajax({
				type: 'post',
				url: 'login.php',
				data:{
				login: 'login',
				username: name,
				password:pwd
				},
				success: function(res) {
					var jsonData = JSON.parse(res);
					if (jsonData.success == "1")
					{
						location.href = 'dashboard.php';
					}
					else
					{
						$('<p class="mb-0">' + jsonData.msg + '</p>').appendTo('.alert');
						$('.alert').show();
					}
				}
			});
		} else {
			$('<p class="mb-0">Пожалуйста, заполните все поля</p>').appendTo('.alert');
			$('.alert').show();
		}
	});
});

</script>