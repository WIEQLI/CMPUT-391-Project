<?php
//Inserts general functions
require('../../usermanagement/php/processfield.php');
require('../../usermanagement/php/checkfieldlength.php');
require('../../usermanagement/php/checkfieldempty.php');

//Create errorcode array that hold status of errors and messages
$errorcode = array(true,'');

//Processes fields text
$diagnosis = strtolower(processField($_POST['diagnosis']));

//Check that fields arent empty
$errorcode = checkFieldEmpty($diagnosis,'Please enter a diagnosis <br/>',$errorcode);

//Checks that fields are appropriate size
$errorcode = checkFieldLength(36,$diagnosis,"Please enter a diagnosis with less then 40 characters <br/>",$errorcode);

if($errorcode[0] == 'true') {
	require('../../database/dbconnect.php');
	require('../../database/gettableid.php');
	require('../../database/executecommand2.php');
	
	//Establish connection to database
	$conn = dbConnect();
	
	$row = findRecords($conn,$diagnosis,$errorcode);

	//Closes connection
	oci_close($conn);
	
	if($row[0][0] == 'true') {
		//Create JSON object with status and results
		$query = array();
		$query['status'] = $row[0][0];
		$query['message'] = 'Records Found';
		$query['results'] = $row[1];
		echo json_encode($query);
	}
	else {
		$query = array();
		$query['status'] = $row[0][0];
		$query['message'] = $row[0][1];
		echo json_encode($query);
	}
}
else {
	//Returns status of .php code and messages
	echo json_encode(array('status'=>$errorcode[0],'message'=>$errorcode[1]));
}


function findRecords($conn,$diagnosis,$errorcode) {

	//Executes sql command
	$num = executeCommand2($conn,'SELECT p.first_name, p.address, p.phone, r.test_date FROM persons p, radiology_record r WHERE r.diagnosis LIKE \'%' .$diagnosis. '%\' AND p.person_id = r.patient_id');

	
	//If first name in list is blank return an errorcode
	if(sizeof($num[0][0][0]) == 0) {
		$errorcode[1] = 'No Records Found';
		$errorcode[0] = false;	
	}
	return array($errorcode,$num[0]);
}

?>
