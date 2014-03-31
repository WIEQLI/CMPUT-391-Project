<?php
//Got date extraction code - based off code from James Hodgson, Tyler Wendlandt, Troy Murphy
// Checks if one of the categories is checked
if(isChecked('testtype') or isChecked('patientid') or isChecked('time')){
	require('../../database/dbconnect.php');
	require('../../database/executecommand2.php');
	$temp = "SELECT ";
	$temp2 = " GROUP BY ";
	
	//Checking what groups are checked to add to group
	if(isChecked('patientid')){
		$temp .= "g.patient_id, ";
		$temp2 .= "g.patient_id, ";
	}
	if(isChecked('testtype')){
		$temp .= "g.test_type, ";	
		$temp2 .= "g.test_type, ";	
	}
	if(isChecked('time')){
		//Group by either weekly, monthly, or yearly
		if($_POST['timeperiod'] == 'w'){
			$temp .= ' to_char(g.test_date,\'IW\'),';
			$temp2 .= ' to_char(g.test_date,\'IW\'),';
		}
		if($_POST['timeperiod'] == 'm'){
			$temp .= ' to_char(g.test_date,\'MON\'),';
			$temp2 .= ' to_char(g.test_date,\'MON\'),';
		}
		if($_POST['timeperiod'] == 'y'){
			$temp .= ' EXTRACT(YEAR from g.test_date),';
			$temp2 .= ' EXTRACT(YEAR from g.test_date),';
		}
	}
	// Trim extra , from end of group line
	$temp2 = rtrim($temp2, " ,");

	$sql = $temp.' COUNT(g.image_id)';
	$sql .= " FROM g_records g";
	$sql .= $temp2;

	//Establish connection to database
	$conn = dbConnect();

	//Executes sql statement
	$resArray = executeCommand2($conn,$sql);

	echo json_encode(array('status'=>true,'rArray'=>$resArray[0]));
}
// If no category is checked
else {
	echo json_encode(array('status'=>false,'message'=>'Please select a category to generate the report on'));
}
// Checks if checkbox is selected
function isChecked($input){
	if(isset($_POST[$input])){
		return true;
	}
	else{
		return false;
	}
}


?>
