<html>
	<head>
	<link rel="stylesheet" type="text/css" href="stylesheets/generalstylesheet.css">
	<script type="text/javascript" src="jquery1.1.min.js"></script>
		<title>Login Module</title>
	</head>
	<body> 
		<div id="page-wrap">
			<div id="header">
				<div id="titlebar">
					<h1>Radiology Information System</h1>
				</div>
			</div>
				<div id="content-wrap" class="styleform">
					<?php session_start(); ?>
						<div id='profile'>
  						<?php if(isset($_SESSION['user_name'])){
  					?>
  					 	<a href='login/logout.php' id='logout'>Logout</a>
  					<?php }else {?>
  						<!-- Login Form -->
						<div id='loginform'>
							<h2>Login</h2>
							<div id="alertbox">
							</div>
							<form name="form1" action='login/login.php' method="post" class='ajaxform'>
								<label for="username">Username:</label><input id="username" name="username" type="text"></br>
								<label for="password">Password:</label><input id="passowrd" name="password" type="password"></br>
								<input type="submit" name="submit" value="Login">
							</form>
						</div>
						</div>
  					<?php } ?>
				</div>
			<div id="footer">
			</div>
		</div>	
	</body>
	<script>
jQuery(document).ready(function(){
	jQuery('.ajaxform').submit( function() {
		$.ajax({
			url     : $(this).attr('action'),
			type    : $(this).attr('method'),
			data    : $(this).serialize(),
			success : function( data ) {

				//Parses JSON data 
				var data = $.parseJSON(data); 
 
				$('#username').val('');
				$('#password').val('');

				if(data['status'] == true) {
					//Change color of text in alert box
					$("#alertbox").css('color','green');
					
					//Gets rid of login form
					$("#login_form").fadeOut("normal");
					
					//Adds code to profile
					$("#profile").html("<a href='login/logout.php' id='logout'>Logout</a>");
					$("#profile").append('<h3>Welcome '+data['username']+'!!!</h3>');
					
				}
				else {
					//Change color of text in alert box
					$("#alertbox").css('color','red');
				}
				//Display message into alert box
				$("#alertbox").html(data["message"]);
					
			},
			error   : function(){
				alert('Something wrong');
			}
		});
		return false;
	});
});
	
	</script>

</html>
