<?php session_start();
	//Checks login has been done 
	if(isset($_SESSION['user_name'])){
?>
<html>
	<head>
	<link rel="stylesheet" type="text/css" href="../stylesheets/generalstylesheet.css">
	<script type="text/javascript" src="../jquery1.1.min.js"></script>
		<title>Update Email</title>
	</head>
	<body> 
		<div id="page-wrap">
			<div id="header">
				<div id="titlebar">
					<h1>Radiology Information System</h1>
				</div>
			</div>
				<a href="loginform.php">Back</a>
				<div id="content-wrap" class="styleform">
					<h2>Update Email</h2>
					<h3>*required field</h3>
					<div id="alertbox">
					</div>
					<form name="form1" action='php/updateemail.php' method="post" class='ajaxform'>
						<label for="email">*Email:</label><input id="email" name="email" type="text"></br>
						<label for="email2">*Confirm Email:</label><input id="email2" name="email2" type="text"></br>
						<input type="submit" name="submit" value="Update Email">
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
				if(data['status'] == true) {
					
					//Change color of text in alert box
					$("#alertbox").css('color','green');

					//Clears input boxes
					$('#email').val('');
					$('#email2').val('');
				}
				else {
					//Change color of text in alert box
					$("#alertbox").css('color','red');
					
					//Sets parameters for input boxes
					if(data['message'].indexOf('email') >= 0 || data['message'].indexOf('Email') >= 0) {
						$('#email').css('border','1px solid red');
						$('#email').val('');
						$('#email2').css('border','1px solid red');
						$('#email2').val('');
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
<?php 	}
	//Redirect to login if fails
	else {
		header('Location:loginform.php');
	}
?>
