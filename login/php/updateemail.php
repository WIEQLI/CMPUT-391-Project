<?php
/*
This module takes in email inputs from updateemailform.php 
and then validates it, and then if sucessfully passes changes
the users password in the database.
*/
//Inserts general functions
require('../../usermanagement/php/processfield.php');
require('../../usermanagement/php/checkfieldlength.php');
require('../../usermanagement/php/checkfieldempty.php');

//Create errorcode array that hold status of errors and messages
$errorcode = array(true,'');

//Processes fields text
$email = processField($_POST['email']);
$email2 = processField($_POST['email2']);

//Checks that fields arent empty
$errorcode = checkFieldEmpty($email,'Please enter your email<br/>',$errorcode);
$errorcode = checkFieldEmpty($email2,'Please enter your email<br/>',$errorcode);

//Checks that fields are appropriate size
$errorcode = checkFieldLength(128,$email,"Please enter an email with less then 128 characters <br/>",$errorcode);
$errorcode = checkFieldLength(128,$email2,"Please enter an email with less then 128 characters <br/>",$errorcode);

if($email != $email2) {
	$errorcode[1] = 'Email are not the same. Please re-enter and make sure they are the same.';
	$errorcode[0] = false;
}

//If passes all checks
if($errorcode[0]) {
	require('../../database/dbconnect.php');
	require('../../database/executecommand.php');

	//Establish connection to database
	$conn = dbConnect();

	//Checks email exists in persons
	$errorcode = checkEmailExists($conn,$email,$errorcode);

	if($errorcode[0]) {
		//Gets next person_id in persons table
		$id = getPersonId($conn,$email);

		//Starts sessions
		session_start();

		//SQL command for entering values into persons
		$sql = 'UPDATE users SET person_id = \''.$id.'\' WHERE user_name = \''.$_SESSION['user_name'].'\''; 

		//Executes sql command
		$num = executeCommand($conn,$sql);

		//Closes connection
		oci_close($conn);
	
		if($num[1]) {
			//Returns status of .php code and messages
			echo json_encode(array('status'=>true,'message'=>'Users email updated sucessfully updated to '.$email));
			
		}
		else {
			//Returns status of .php code and messages
			echo json_encode(array('status'=>false,'message'=>'Error executing command to database'));
			
		}
	}
	else {
		//Closes connection
		oci_close($conn);
	
		//Returns status of .php code and messages
		echo json_encode(array('status'=>$errorcode[0],'message'=>$errorcode[1]));
	}	
}
else {
	//Returns status of .php code and messages
	echo json_encode(array('status'=>$errorcode[0],'message'=>$errorcode[1]));
}

//Checks if email provided exists in persons
function checkEmailExists($conn,$email,$errorcode) {
	//Executes sql command
	$num = executeCommand($conn,'SELECT COUNT(*) from persons p where p.email =\''.$email.'\'');

	//If oci_parse doesnt find email within persons
	if($num[0][0] == 0) {
		$errorcode[1] = 'Person doesnt exist with that email. Insert new email or create person with that email.';
		$errorcode[0] = false;	
	}
	return $errorcode;
}

//Gets person_id from persons table that corresponds with email
function getPersonId($conn,$email){

	//Executes sql command
	$num = executeCommand($conn,'SELECT p.person_id from persons p where p.email =\''.$email.'\'');

	//Returns person_id from persons
	return $num[0][0];
}

?>
