<html>
	<head>
	<link rel="stylesheet" type="text/css" href="stylesheets/generalstylesheet.css">
	<script type="text/javascript" src="jquery1.1.min.js"></script>
		<title>Update Family Doctor</title>
	</head>
	<body> 
		<div id="page-wrap">
			<div id="header">
				<div id="titlebar">
					<h1>Radiology Information System</h1>
				</div>
			</div>
				<div id="content-wrap" class="styleform">
					<h2>Update Family Doctor</h2>
					<h3>*required field</h3>
					<div id="alertbox">
					</div>
					
					<form id='form1' name="form1" action='usermanagement/php/searchfamilydoctor.php' method="post" class='ajaxform'>
						<h4><u>Search for family doctor</u></h4>
						<label for="email">*Email from doctor:</label><input id="email" name="email" type="text"></br>
						<label for="email2">*Email from patient:</label><input id="email2" name="email2" type="text"></br>
						<input type="submit" name="submit" value="Search family doctor">
					</form></br>
					<form id='form2' name="form2" action='usermanagement/php/updatefamilydoctor.php' method="post" class='ajaxform2'>
						<input id="doctor_id" name="doctor_id" visibility="hidden" type="text">
						<input id="patient_id" name="patient_id" visibility="hidden" type="text">
						<h4><u>Enter emails for doctor and patient to update</u></h4>
						<label for="email3">*Email from doctor:</label><input id="email3" name="email3" type="text"></br>
						<label for="email4">*Email from patient:</label><input id="email4" name="email4" type="text"></br>
						<input type="submit" name="submit" value="Update family doctor">
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
	//Hides forms
	$('#form2').hide();
	$('#doctor_id').hide();
	$('#patient_id').hide();

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
					alert(data['d_email']);
					alert(data['p_email']);

					//Populate inputs for transering data
					$('#doctor_id').val(data['d_email']);
					$('#patient_id').val(data['p_email']);

					//Shows form
					$('#form2').show();
					$("#alertbox2").html('');

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
	jQuery('.ajaxform2').submit( function() {
		$.ajax({
			url     : $(this).attr('action'),
			type    : $(this).attr('method'),
			data    : $(this).serialize(),
			success : function( data ) {
				//Parses JSON data 
				var data = $.parseJSON(data);
				alert(data['d']);
				
				//Resets input highlights
				$('input').css('border','1px solid #999');

				//Sets parameters for form based on resulting data
				if(data['status'] == true) {
					
					//Change color of text in alert box
					$("#alertbox2").css('color','green');

					//Clears input boxes
					$('#email3').val('');
					$('#email4').val('');

					//Shows form
					$('#form2').hide();

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
				$("#alertbox2").html(data["message"]);

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
