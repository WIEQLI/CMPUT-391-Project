<?php
/*
This module takes in inputs from updateuserpasswordform.php, validates
the input, and if succesfully passes updates a users password.

Uses: processfield.php, checkfieldlength.php, checkfieldempty.php, 
dbconnect.php, and executecommand.php
*/
//Inserts general functions
require('../../usermanagement/php/processfield.php');
require('../../usermanagement/php/checkfieldlength.php');
require('../../usermanagement/php/checkfieldempty.php');

//Create errorcode array that hold status of errors and messages
$errorcode = array(true,'');

//Processes fields text
$password = processField($_POST['password']);
$password2 = processField($_POST['password2']);

//Checks that fields arent empty
$errorcode = checkFieldEmpty($password,'Please enter your password<br/>',$errorcode);
$errorcode = checkFieldEmpty($password2,'Please enter your password<br/>',$errorcode);

//Checks that fields are appropriate size
$errorcode = checkFieldLength(24,$password,"Please enter an password with less then 24 characters <br/>",$errorcode);
$errorcode = checkFieldLength(24,$password2,"Please enter an password with less then 24 characters <br/>",$errorcode);

if($password != $password2) {
	$errorcode[1] = 'passwords are not the same. Please re-enter and make sure they are the same.';
	$errorcode[0] = false;
}

//If passes all checks
if($errorcode[0]) {
	require('../../database/dbconnect.php');
	require('../../database/executecommand.php');

	//Establish connection to database
	$conn = dbConnect();

	//Starts sessions
	session_start();

	//SQL command for entering values into persons
	$sql = 'UPDATE users SET password = \''.$password.'\' WHERE user_name = \''.$_SESSION['user_name'].'\''; 

	//Executes sql command
	$num = executeCommand($conn,$sql);

	//Closes connection
	oci_close($conn);

	if($num[1]) {
		//Returns status of .php code and messages
		echo json_encode(array('status'=>true,'message'=>'Users password updated sucessfully updated to '.$password));
	
	}
	else {
		//Returns status of .php code and messages
		echo json_encode(array('status'=>false,'message'=>'Error executing command to database'));
	
	}
}
else {
	//Returns status of .php code and messages
	echo json_encode(array('status'=>$errorcode[0],'message'=>$errorcode[1]));
}

?>
