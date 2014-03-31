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
		<title>Insert Person</title>
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
					<h2>Insert Person</h2>
					<h3>*required field</h3>
					<div id="alertbox">
					</div>
					<form name="form1" action='php/insertperson.php' method="post" class='ajaxform'>
						
						<label for="firstname">*First Name:</label><input id="firstname" name="firstname" type="text"></br>
						<label for="lastname">*Last Name:</label><input id="lastname" name="lastname" type="text"></br>
						<label for="email">*Email:</label><input id="email" name="email" type="text"></br>
						<label for="password">*Address:</label><input id="address" name="address" type="text"></br>
						<label for="phone">*Phone:</label><input id="phone" name="phone" type="text"></br>
						<input type="submit" name="submit" value="Create person">
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
					$('#firstname').val('');
					$('#lastname').val('');
					$('#email').val('');
					$('#address').val('');
					$('#phone').val('');
				}
				else {
					//Change color of text in alert box
					$("#alertbox").css('color','red');
					
					//Sets parameters for input boxes
					if(data['message'].indexOf('first') >= 0) {
						$('#firstname').css('border','1px solid red');
						$('#firstname').val('');
					}
					if(data['message'].indexOf('last') >= 0) {
						$('#lastname').css('border','1px solid red');
						$('#lastname').val('');
					}
					if(data['message'].toLowerCase().indexOf('email') >= 0) {
						$('#email').css('border','1px solid red');
						$('#email').val('');
					}
					if(data['message'].indexOf('address') >= 0) {
						$('#address').css('border','1px solid red');
						$('#address').val('');
					}
					if(data['message'].indexOf('phone') >= 0) {
						$('#phone').css('border','1px solid red');
						$('#phone').val('');
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
