<?php session_start();
	//Checks login has been done 
	if(isset($_SESSION['user_name'])){
?>
<html>
	<head>
	<link rel="stylesheet" type="text/css" href="../stylesheets/generalstylesheet.css">
	<script type="text/javascript" src="../jquery1.1.min.js"></script>
		<title>Update Password</title>
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
					<h2>Update Password</h2>
					<h3>*required field</h3>
					<div id="alertbox">
					</div>
					<form name="form1" action='php/updateuserpassword.php' method="post" class='ajaxform'>
						<label for="password">*Password:</label><input id="password" name="password" type="text"></br>
						<label for="password2">*Confirm Password:</label><input id="password2" name="password2" type="text"></br>
						<input type="submit" name="submit" value="Update password">
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

				//Clears input boxes
				$('#password').val('');
				$('#password2').val('');

				//Sets parameters for form based on resulting data
				if(data['status'] == true) {
					
					//Change color of text in alert box
					$("#alertbox").css('color','green');
				}
				else {
					//Change color of text in alert box
					$("#alertbox").css('color','red');
					
					//Sets parameters for input boxes
					if(data['message'].indexOf('password') >= 0 || data['message'].indexOf('passwords') >= 0) {
						$('#password').css('border','1px solid red');
						$('#password2').css('border','1px solid red');
						$('#password').val('');
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
<?php }
	//Redirect to login if fails
	else {
		header('loginform.php');
	}
?>
