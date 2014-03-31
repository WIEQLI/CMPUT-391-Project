<!--
This form is used to insert a user into the database. An administrator enters
a email of a person, username, password, and class for user to be under. This
information is then sent to insertuser.php where if successfully completes the
user will be added to the database

Uses: getuserdata.php, generalstylesheet.css, jquery1.1.min.js, insertuser.php
//-->
<?php session_start();
	//Checks login has been done and is an administrator
	if(isset($_SESSION['user_name'])){
		require('../login/getuserdata.php');
		//Obtaining user data
		$res = getUserData($_SESSION['user_name']);
		if($res[0][2] == 'a'){
?>
<html>
	<head>
	<link rel="stylesheet" type="text/css" href="../stylesheets/generalstylesheet.css">
	<script type="text/javascript" src="../jquery1.1.min.js"></script>
		<title>Insert User</title>
	</head>
	<body> 
		<div id="page-wrap">
			<div id="header">
				<div id="titlebar">
					<h1>Radiology Information System</h1>
				</div>
			</div>
				<div id="content-wrap" class="styleform">
					<h2><a class="button" href="../login/loginform.php">Back</a></h2>
					<h2>Insert User</h2>
					<h3>*required field</h3>
					<div id="alertbox">
					</div>
					<form name="form1" action='php/insertuser.php' method="post" class='ajaxform'>
						<label for="email">*Email from person:</label><input id="email" name="email" type="text"></br>
						<label for="username">*Username:</label><input id="username" name="username" type="text"></br>
						<label for="password">*Password:</label><input id="password" name="password" type="password"></br>
						<label for="password2">*Confirm Password:</label><input id="password2" name="password2" type="password"></br>
						<label for="classes">Classes:</label></br>
								<select name="classes" id="classes">
	        						<option value="a">Administrator</option>
    	    						<option value="p">Patient</option>
    	    						<option value="d">Doctor</option>
    	    						<option value="r">Radiologist</option>
    						</select></br></br>
						<input type="submit" name="submit" value="Create user">
					</form>
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

				//Resets input highlights
				$('input').css('border','1px solid #999');

				//Sets parameters for form based on resulting data
				if(data['status'] == 'true') {
					
					//Change color of text in alert box
					$("#alertbox").css('color','green');

					//Clears input boxes
					$('#email').val('');
					$('#username').val('');
					$('#password').val('');
					$('#password2').val('');
				}
				else {
					//Change color of text in alert box
					$("#alertbox").css('color','red');
					
					//Sets parameters for input boxes
					if(data['message'].indexOf('username') >= 0) {
						$('#username').css('border','1px solid red');
						$('#username').val('');
					}
					if(data['message'].indexOf('email') >= 0) {
						$('#email').css('border','1px solid red');
						$('#email').val('');
					}
					if(data['message'].toLowerCase().indexOf('password') >= 0) {
						$('#password').css('border','1px solid red');
						$('#password').val('');
						$('#password2').css('border','1px solid red');
						$('#password2').val('');
					}
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
<?php }else{ echo header('Location:../login/loginform.php');}}
	//Redirect to login if fails
	else {
		header('Location:../login/loginform.php');
	}
?>
