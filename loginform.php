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
				<div id="content-wrap" >
					<div id='profile'>
					<?php session_start(); ?>
  						<?php if(isset($_SESSION['user_name'])){
							echo "<a href='login/logout.php' id='logout'>Logout</a></br></br>";
							require('/compsci/webdocs/kjross/web_docs/login/getuserdata.php');
							$res = getUserData($_SESSION['user_name']);
							echo '<h1><u>Welcome '.$res[0][0].'</u></h1></br>';
							echo "<a href='login/updateuseremailform.php'>Modify Account</a>&nbsp;&nbsp;&nbsp;&nbsp";
							echo "<a href='login/updateuserpasswordform.php'>Change Password</a>";
							if($res[0][2] == 'a'){
								echo '<h2></br><a href="insertpersonform.php">Insert Person</a></h2>
								<h2><a href="updatepersonform.php">Update Person</a></h2>
								<h2><a href="insertuserform.php">Insert User</a></h2>
								<h2><a href="updateuserform.php">Update User</a></h2>
								<h2><a href="insertfamilydoctorform.php">Insert Family Doctor</a></h2>
								<h2><a href="updatefamilydoctorform.php">Update Family Doctor</a></h2>';
							}
  					?>
  					<?php }else {?>
  						<!-- Login Form -->
						<div class="styleform" id='loginform'>
							<h2>Login</h2>
							<div id="alertbox">
							</div>
							<form name="form1" action='login/login.php' method="post" class='ajaxform'>
								<label for="username">Username:</label><input id="username" name="username" type="text"></br>
								<label for="password">Password:</label><input id="password" name="password" type="password"></br>
								<input type="submit" name="submit" value="Login">

							</form>
						</div>
  					<?php } ?>
					</div>
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
					$("#profile").html("<a href='login/logout.php' id='logout'>Logout</a></br></br>");
					$("#profile").append('<h1><u>Welcome '+data['username']+'</u></h1></br>');
					$("#profile").append('<a href="login/updateuseremailform.php">Modify Account</a>&nbsp;&nbsp;&nbsp;&nbsp');
					$("#profile").append('<a href="login/updateuserpasswordform.php">Change Password</a>');
					if(data['class'] == 'a'){
						$("#profile").append('<h2></br><a href="insertpersonform.php">Insert Person</a></h2>'
						+'<h2><a href="updatepersonform.php">Update Person</a></h2>'
						+'<h2><a href="insertuserform.php">Insert User</a></h2>'
						+'<h2><a href="updateuserform.php">Update User</a></h2>'
						+'<h2><a href="insertfamilydoctorform.php">Insert Family Doctor</a></h2>'
						+'<h2><a href="updatefamilydoctorform.php">Update Family Doctor</a></h2>');
					}
						
					
					
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