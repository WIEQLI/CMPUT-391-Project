<?php

//Inserts general functions
require('/compsci/webdocs/kjross/web_docs/usermanagement/php/processfield.php');
require('/compsci/webdocs/kjross/web_docs/usermanagement/php/checkfieldlength.php');
require('/compsci/webdocs/kjross/web_docs/usermanagement/php/checkfieldempty.php');

//Create errorcode array that hold status of errors and messages
$errorcode = array(true,'');

//Processes fields text
$email = processField($_POST['email']);

//Check that fields arent empty
$errorcode = checkFieldEmpty($email,'Please enter an email <br/>',$errorcode);

//Checks that fields are appropriate size
$errorcode = checkFieldLength(128,$email,"Please enter an email with less then 128 characters <br/>",$errorcode);

if($errorcode[0] == 'true') {
	require('/compsci/webdocs/kjross/web_docs/database/dbconnect.php');
	require('/compsci/webdocs/kjross/web_docs/database/gettableid.php');
	require('/compsci/webdocs/kjross/web_docs/database/executecommand.php');
	
	//Establish connection to database
	$conn = dbConnect();
	
	$row = findEmail($conn,$email,$errorcode);

	//Closes connection
	oci_close($conn);
	
	if($row[0][0] == 'true') {
		echo json_encode(array('status'=>$row[0][0],'message'=>'Person Found','email'=>$row[1][0][0],'firstname'=>$row[1][0][1],'lastname'=>$row[1][0][2],'address'=>$row[1][0][3],'phone'=>$row[1][0][4],'person_id'=>$row[1][0][5]));
	}
	else {
		echo json_encode(array('status'=>$row[0][0],'message'=>$row[0][1]));
	}
}
else {
	//Returns status of .php code and messages
	echo json_encode(array('status'=>$errorcode[0],'message'=>$errorcode[1]));
}

function findEmail($conn,$email,$errorcode) {

	//Executes sql command
	$num = executeCommand($conn,'SELECT p.email, p.first_name, p.last_name, p.address, p.phone, p.person_id from persons p where p.email =\''.$email.'\'');
	
	//If oci_parse finds email in use return false and alert user
	if($num[0][0] != $email) {
		$errorcode[1] = 'Email for person doesnt exists';
		$errorcode[0] = false;	
	}
	return array($errorcode,$num);
}

?>
