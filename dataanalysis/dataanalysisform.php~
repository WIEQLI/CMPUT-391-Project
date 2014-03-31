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
		<title>Data Analysis</title>
	</head>
	<body> 
		<div id="page-wrap">
			<div id="header">
				<div id="titlebar">
					<h1>Radiology Information System</h1>
				</div>
			</div>
				<div id="content-wrap" class="styleform">
					<h2>Data Analysis</h2>
					<h3>Display number of records for each category</h3>
					<div id="alertbox">
					</div>
					<form name="form1" action='php/dataanalysis.php' method="post" class='ajaxform'>
						<label for="patientid">Patient Id:</label></br><input id="patientid" type="checkbox" class="checkbox" name="patientid" value="patientid">
						<label for="testtype">Test Type:</label><input id="testtype" type="checkbox" class="checkbox" name="testtype" value="testtype">
						<label for="time">Time:</label><input id="time" type="checkbox" class="checkbox" name="time" value="time">
						<label id="timeperiodlabel" for="timeperiod:">Time Period: </label><select name="timeperiod" id="timeperiod">
	        					<option value="w">Weekly</option>
    	    						<option value="m">Monthly</option>
    	    						<option value="y">Yearly</option>
    						</select></br></br>
						<input type="submit" name="submit" value="Generate Report">
					</form>
					</br>
					<table id="report">
					</table>
				</div>
				
			<div id="footer">
			</div>
		</div>	
	</body>
<script>
$("#time").click(function() {
	if($('#timeperiod').is(':visible')) {
		$('#timeperiodlabel').hide();
		$('#timeperiod').hide();
	}
	else {
		$('#timeperiodlabel').show();
		$('#timeperiod').show();
	}
	
});


jQuery(document).ready(function(){
	//Hides elements
	$('#timeperiodlabel').hide();
	$('#timeperiod').hide();

	jQuery('.ajaxform').submit( function() {
		$.ajax({
			url     : $(this).attr('action'),
			type    : $(this).attr('method'),
			data    : $(this).serialize(),
			success : function( data ) {
				//Parses JSON data 
				var data = $.parseJSON(data);
				if(data['status'] == true){
					var rArray = data['rArray'];
					$('#report').html('');	
					var temp = '';
					// Make table title
					temp += '<tr><td>Patient Id:</td><td>Test Type:</td><td>Time Period:</td><td>Number of Images:</td></tr>';	

					//Add rows to table
					for(var i=0; i < rArray.length;i++){
						j = 0;
						temp += '<tr>';
						temp = addParameterToTable(temp,'#patientid',i,j,rArray);
						temp = addParameterToTable(temp[0],'#testtype',i,temp[1],rArray);
						temp = addParameterToTable(temp[0],'#time',i,temp[1],rArray);
						temp = temp[0] + '<td>' + rArray[i][j] + '</td>'+ '</tr>';
					}
					$('#report').append(temp);
				}
				
				else{
					alert(data['message']);
				}
			},
			error   : function(){
				alert('Something wrong');
			}
		});
		return false;
	});
});

function addParameterToTable(temp,parameter,i,j,rArray) {
	if($(parameter).is(':checked')) {
		temp += '<td>'+rArray[i][j]+'</td>';
		j++;
	}
	else{
		temp += '<td></td>';
	}
	return [temp,j];
}
</script>

</html>
<?php }else{ echo header('Location:../login/loginform.php');}}
	//Redirect to login if fails
	else {
		header('Location:../login/loginform.php');
	}
?>

