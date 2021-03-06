<!--
This form is just to search for radiology records. This modules allows a username to
enter keywords and/or dates to search for records in. In then send this data to searchradiologyrecords.php
and then recieves then results of this module

Uses: jquery.mobile-1.4.2.min.js, jquery-1.9.1.min.js, generalstylesheet.css, and searchradiologyrecord.php
//-->
<?php session_start();
	//Checks login has been done 
	if(isset($_SESSION['user_name'])){
?>
<!DOCTYPE html>
<html>
	<head>
	<link rel="stylesheet" type="text/css" href="../stylesheets/generalstylesheet.css">
	<link rel="stylesheet" href="http://code.jquery.com/mobile/1.4.2/jquery.mobile-1.4.2.min.css" />
	<script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
	<script src="http://code.jquery.com/mobile/1.4.2/jquery.mobile-1.4.2.min.js"></script>
		<title>Search Radiology Records</title>
	</head>
	<body> 
		<div id="page-wrap">
			<div id="header">
				<div id="titlebar">
					<h1>Radiology Information System</h1>
				</div>
			</div>
				
				<div id="content-wrap" >
					<h2>Search Radiology Records</h2>
					<div id="alertbox">
					</div>
					<form name="form1" action='php/searchradiologyrecord.php' enctype="multipart/form-data" method="post" class='ajaxform'>
						<label for="parameter">Key Words (seperate by space):</label><input placeholder="Key Words" id="parameter" name="parameter" type="text">
						<label for="date">Time Periods: (YYYY-MM-DD)</label><input placeholder="Starting" id="date" name="date" type="text">
						<input id="date2" name="date2" placeholder="Ending" type="text">
						<u>Order Records Listed</u>
						<div id="rbuttons">
							<label>Shown by ranking within keywords:</label><input class="radio" type="radio" name="order" value="default" checked/></br>
							<label>Most Recent First:</label><input class="radio" type="radio" name="order" value="mostrecentfirst"/></br>
							<label>Most Recent Last:</label><input class="radio" type="radio" name="order" value="mostrecentlast"/></br>
						</div>
						<input type="submit" name="submit" value="Search">
					</form> 
					<ul data-role="listview" id="menuList">
					</ul>
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
				$('#alertbox').val('');

				if(data['status'] == true) {
					//Load information into arrays
					var rArray = data['rArray'];
					var iArray = data['iArray'];

					//Clears listview
					$('#menuList').empty();
					$('#menuList').listview();
			
					//Goes through records found and displays their information
					for(var i=0;i < rArray.length;i++) {
						$('#menuList').append('<li>');
						//Add images(s) for each radiology record
						for(var j=0; j < iArray[i].length;j++){
							$('#menuList').append('<img height="50px" width="50px" src="data:image/png;base64,'+ iArray[i][j]+ '" onclick=loadrecord(this.src)></img>');
						}
						//Adding 
						$('#menuList').append('<p><b>Record Id:</b>'+rArray[i][0]);
						$('#menuList').append(' <b>Doctor Name:</b>'+rArray[i][3]+' '+rArray[i][4]);
						$('#menuList').append(' <b>Patient Name:</b>'+rArray[i][1]+' '+rArray[i][2]);
						$('#menuList').append(' <b>Radiologist Name:</b>'+rArray[i][5]+' '+rArray[i][6]);
						$('#menuList').append(' </br><b>Test type:</b>'+rArray[i][7]);
						$('#menuList').append(' <b>Prescribing Date:</b>'+rArray[i][8]);
						$('#menuList').append(' <b>Test Date:</b>'+rArray[i][9]);
						$('#menuList').append(' <b>Diagnostic:</b>'+rArray[i][10]);
						$('#menuList').append(' </br><b>Description:</b>'+rArray[i][11]+'</p></li>');
					}
					//If no records are found display message
					if(rArray.length == 0){
						$('#menuList').append('<li><p>No radiology records found</p></li>');
					}
				}
				else {
					$('#alertbox').html(data['message']);
				}
					
			},
			error   : function(){
				alert('Something wrong');
			}
		});
		return false;
	});
});
//Loads image in window when clicked
function loadrecord(scr){
	var record =  window.open('','Radiology Record','width=1000,height=1000');
	var script = '<script>function fullsize(){ document.getElementById("reg").style.width="100%";document.getElementById("reg").style.height="100%";}'+'<'+'/'+'script>';
	var html = '<!DOCTYPE html><html><head><title></title></head><body><img id="reg" onclick="fullsize()" width="50%" height="50%" src="'+scr+'"></img></body>'+script+'</html>';
    	record.document.open();
    	record.document.write(html);
	record.document.close();
}

</script>

</html>
<?php 	}
	//Redirect to login if fails
	else {
		header('Location:../login/loginform.php');
	}
?>
