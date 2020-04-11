<?php
  //start session
  session_start();
  require_once "templates/header.php";

  //including the database connection file
  require_once("objects/user.php");
  require_once("objects/project.php");
  require_once("objects/task.php");
  require_once("objects/validation.php");

  //Initialize user class
  $user = new User();

  if($user->is_loggedin() !="") {
    $user->redirect("dashboard.php");
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

  <div class="form-label-group">
    <input type="password" id="inputConfirmPassword" class="form-control" placeholder="Confirm password" required>
    <label for="inputConfirmPassword">Confirm password</label>
  </div>
  
  <button class="btn btn-lg btn-primary btn-block" type="submit" id="submit">Registration</button>
  <div class="text-center mt-3">
    <a class="underlineHover" href="index.php">Sign in?</a>
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
		var conf_pwd = $('#inputConfirmPassword').val();
		var name = $('#inputUsername').val();

		if(name =='' || pwd =='' || conf_pwd =='') {
			$('<p class="mb-0">Please fill in all fields</p>').appendTo('.alert');
			$('.alert').show();
		}
		else if(pwd != conf_pwd)
		{
			$('<p class="mb-0">Password mismatch</p>').appendTo('.alert');
			$('.alert').show();
		} else {
			$.ajax({
				type: 'post',
				url: 'do_register.php',
				data:{
					do_register: 'do_register',
					username: name,
					password:pwd
				},
				success: function(res) {
					var jsonData = JSON.parse(res);
					if (jsonData.success == "1")
					{
						$('<p class="mb-0">You have successfully registered. Please log in</p>').appendTo('.alert');
						$('.alert').show();
					}
					else
					{
						$('<p class="mb-0">' + jsonData.msg + '</p>').appendTo('.alert');
						$('.alert').show();
					}
				}
			});
		}
	});
});

</script>