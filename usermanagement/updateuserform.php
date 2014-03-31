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
		<title>Update User</title>
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
				<h2>Update User</h2>
				<form id='form1' name="form1" action='php/searchuser.php' method="post" class='ajaxform'>
					<label><u>Find User</u></label><br/>						
					<label for="username">Username:</label><input id="username" name="username" type="text"></br>					
					<input type="submit" name="submit" value="Find user">
				</form>
				<div id="alertbox">
				</div>
				<br/>
				<form id='form2' name="form2" action='php/updateuser.php' method="post" class='ajaxform2'>
					<label><u>Update User</u></label><br/>	
					<input id="person_id" name="person_id" visibility="hidden" type="text">
					<input id="username3" name="username3" visibility="hidden" type="text">
					<label for="username2">Username</label><input id="username2" name="username2" type="text"></br>
					<label for="classes">Classes:</label></br><select name="classes" id="classes">
        						<option value="a">Administrator</option>
        						<option value="p">Patient</option>
        						<option value="d">Doctor</option>
        						<option value="r">Radiologist</option>
    					</select></br></br>
					<input type="submit" name="submit" value="Update user">
				</form>
				<div id="alertbox2">
				</div>
			</div>
			<div id="footer">
			</div>
		</div>
			
	</body>
	<script>
jQuery(document).ready(function(){
	//Hides form
	$('#form2').hide();

	//Hides input that stores person_id
	$('#person_id').hide();

	//Hides input that stores username
	$('#username3').hide();

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

				//Clears input box
				$('#username').val('');

				if(data['status'] == true) {
					//Setting parameters for alertbox
					$("#alertbox").css('color','green');
					

					//Sets values to update form
					$('#username2').val(data['username']);
					$('#username3').val(data['username']);
					$('#password').val(data['password']);
					$('#classes').val(data['class']);
					$('#username2').prop('disabled',true);

					//Shows form
					$('#form2').show();
					
				}
				else {
					$('#username').css('border','1px solid red');

					//Setting parameters for alertbox
					$("#alertbox").css('color','red');
					
					//Hides form
					$('#form2').hide();	
				}	
				//Displays message in alert box
				$("#alertbox").html(data["message"]);		
			},
			error   : function(){
				alert('Something wrong');
			}
		});
		return false;
	});

	jQuery('.ajaxform2').submit( function() {
		$.ajax({
			url     : $(this).attr('action'),
			type    : $(this).attr('method'),
			data    : $(this).serialize(),
			success : function( data ) {
				//Parses JSON data 
				var data = $.parseJSON(data);

				//Resets input highlights
				$('input').css('border','1px solid #999');

				//Process properties based upon the status of errorcodes
				if(data['status'] == 'true') {
					//Change color of alert box
					$("#alertbox2").css('color','green');
				}
				else {
					//Change color of alert box
					$("#alertbox2").css('color','red');
				}
				//Display message into alert box
				$('#alertbox2').html(data['message']);		
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
