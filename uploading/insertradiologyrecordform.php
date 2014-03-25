<?php session_start();
	//Checks login has been done and is an radiologist
	if(isset($_SESSION['user_name'])){
		require('/compsci/webdocs/kjross/web_docs/login/getuserdata.php');
		//Obtaining user data
		$res = getUserData($_SESSION['user_name']);
		if($res[0][2] == 'r'){
?>
<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="../stylesheets/generalstylesheet.css">
		<script src="http://code.jquery.com/jquery-1.9.1.min.js" type="text/javascript" language="javascript"></script>
		<script src="jquery.MultiFile.js" type="text/javascript" language="javascript"></script>
		
		
		<title>Insert Radiology Record</title>
	</head>
	<body> 
		<div id="page-wrap">
			<div id="header">
				<div id="titlebar">
					<h1>Radiology Information System</h1>
				</div>
			</div>
				<div id="content-wrap" class="styleform">
					<h2>Insert Radiology Record</h2>
					<h3>*required field</h3>
					<div id="alertbox">
					</div>
					<form name="form1" action='php/insertradiologyrecord.php' enctype="multipart/form-data" method="post" class='ajaxform'>
						<label for="email">*Email of Doctor:</label><input id="email" name="email" type="text"></br>
						<label for="email2">*Email of Patient:</label><input id="email2" name="email2" type="text"></br>
						<label for="testtype">*Test Type:</label><input id="testtype" name="testtype" type="text"></br>
						<label for="testdate">*Test Date (YYYY-MM-DD):</label><input id="testdate" name="testdate" type="text"></br>
						<label for="pdate">*Prescribing Date (YYYY-MM-DD):</label><input id="pdate" name="pdate" type="text"></br>
						<label for="diagnosis">*Diagnosis:</label><textarea id="diagnosis" rows=2 cols=70 name="diagnosis" type="text"></textarea></br>
						<label for="desc">*Description:</label><textarea id="desc" rows=10 cols=70 name="desc" type="text"></textarea></br>
						<label for="imageupload">*Image(s) to include:</label><input id="imageupload" name="imageuploads[]" type="file" multiple/></br>
						<input type="submit" name="submit" value="Create radiology record">
					</form>
				</div>
			<div id="footer">
			</div>
		</div>	
	</body>
	<script>

jQuery(document).ready(function(){
	jQuery('.ajaxform').submit( function() {
		var formData = new FormData(this);
		$.ajax({
			url     : $(this).attr('action'),
			type    : $(this).attr('method'),
			data    : formData,
			mimeType:"multipart/form-data",
            		contentType: false,
            		cache: false,
            		processData:false,
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
					$('#testtype').val('');
					$('#testdate').val('');
					$('#pdate').val('');
					$('#diagnosis').val('');
					$('#imageupload').val('');
					$('#desc').val('');
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
					if(data['message'].toLowerCase().indexOf('test type') >= 0) {
						$('#testtype').css('border','1px solid red');
						$('#testtype').val('');
					}
					if(data['message'].indexOf('test date') >= 0) {
						$('#testdate').css('border','1px solid red');
						$('#testdate').val('');
					}
					if(data['message'].indexOf('prescribing date') >= 0) {
						$('#pdate').css('border','1px solid red');
						$('#pdate').val('');
					}
					if(data['message'].indexOf('prescribing date') >= 0) {
						$('#pdate').css('border','1px solid red');
						$('#pdate').val('');
					}
					if(data['message'].indexOf('diagnosis') >= 0) {
						$('#diagnosis').css('border','1px solid red');
						$('#diagnosis').val('');
					}
					if(data['message'].indexOf('description') >= 0) {
						$('#desc').css('border','1px solid red');
						$('#desc').val('');
					}
					if(data['message'].indexOf('Date') >= 0) {
						$('#pdate').css('border','1px solid red');
						$('#pdate').val('');
						$('#testdate').css('border','1px solid red');
						$('#testdate').val('');
					}
				}
				//Display message into alert box
				//$('#alertbox').append('<img src="data:image/png;base64,'+ data['message']+'"></img>');
				$('#alertbox').append(data['message']);
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
<?php }else{ echo header('Location:loginform.php');}}
	//Redirect to login if fails
	else {
		header('Location:loginform.php');
	}
?>
