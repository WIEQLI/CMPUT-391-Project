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
		<title>Insert Family Doctor</title>
	</head>
	<body> 
		<div id="page-wrap">
			<div id="header">
				<div id="titlebar">
					<h1>Radiology Information System</h1>
				</div>
			</div>
				<div id="content-wrap" class="styleform">
					<h2>Insert Family Doctor</h2>
					<h3>*required field</h3>
					<div id="alertbox">
					</div>
					<form name="form1" action='php/insertfamilydoctor.php' method="post" class='ajaxform'>
						<label for="email">*Email from doctor:</label><input id="email" name="email" type="text"></br>
						<label for="email2">*Email from patient:</label><input id="email2" name="email2" type="text"></br>
						<input type="submit" name="submit" value="Create family doctor">
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
					$('#email2').val('');
				}
				else {
					//Change color of text in alert box
					$("#alertbox").css('color','red');
					
					//Sets parameters for input boxes
					if(data['message'].indexOf('doctors') >= 0) {
						$('#email').css('border','1px solid red');
						$('#email').val('');
					}
					if(data['message'].indexOf('patients') >= 0) {
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
<?php }else{ echo header('Location:../login/loginform.php');}}
	//Redirect to login if fails
	else {
		header('Location:../login/loginform.php');
	}
?>
