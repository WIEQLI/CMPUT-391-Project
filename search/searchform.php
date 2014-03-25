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
						<label for="parameter">Key Words (Seperate by space):</label><input placeholder="Key Words" id="parameter" name="parameter" type="text">
						<label for="date">Time Periods: (YYYY-MM-DD)</label><input placeholder="Starting" id="date" type="text">
						<input id="date2" placeholder="Ending" type="text">
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
				var vals = data['test'];
				$('#menuList').empty();
				$('#menuList').listview();
				$('#menuList').append(' <li><a href="#">'+vals+'</a></li>').listview('refresh');	
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
		header('Location:../loginform.php');
	}
?>
