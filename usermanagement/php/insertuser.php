<?php
/*
This module inserts a user into the database. This module takes
inputs from insertuserform.php validates it and if successful 
inserts a user into the database.

Uses: processfield.php, checkfieldlength.php, checkfieldempty.php,
dbconnect.php, executecommand.php
*/
//Inserts general functions
require('processfield.php');
require('checkfieldlength.php');
require('checkfieldempty.php');


//Create errorcode array that hold status of errors and messages
$errorcode = array(true,'');

//Processes fields text
$email = processField($_POST['email']);
$username = strtolower(processField($_POST['username']));
$password = processField($_POST['password']);
$password2 = processField($_POST['password2']);

$class = $_POST['classes'];

//Checks that fields arent empty
$errorcode = checkFieldEmpty($email,'Please enter your email <br/>',$errorcode);
$errorcode = checkFieldEmpty($username,'Please enter your username <br/>',$errorcode);
$errorcode = checkFieldEmpty($password,'Please enter a password <br/>',$errorcode);

//Checks that fields are appropriate size
$errorcode = checkFieldLength(128,$email,"Please enter an email with less then 128 characters <br/>",$errorcode);
$errorcode = checkFieldLength(24,$username,"Please enter a username with less then 24 characters <br/>",$errorcode);
$errorcode = checkFieldLength(24,$password,"Please enter a password with less then 24 characters <br/>",$errorcode);

//Checks password is same when confirming
$errorcode = checkPasswordSame($password,$password2,$errorcode);

//If passes all checks
if($errorcode[0]) {
	require('../../database/dbconnect.php');
	require('../../database/executecommand.php');

	//Establish connection to database
	$conn = dbConnect();

	//Checks if username is unique
	$errorcode = checkUsernameUnique($conn,$username,$errorcode);

	//Checks email exists in persons
	$errorcode = checkEmailExists($conn,$email,$errorcode);

	//If is unique
	if($errorcode[0]) {
	
		//Gets next person_id in persons table
		$id = getPersonId($conn,$email);
		
		//SQL command for entering values into users
		$sql = 'INSERT INTO users (user_name, password, class, person_id, date_registered) VALUES (\''.$username.'\',\''.$password.'\',\''.$class.'\',\''.$id.'\',sysdate)';

		//Executes sql command
		$num = executeCommand($conn,$sql);

		//Closes connection
		oci_close($conn);

		if($num[1]) {
			//Returns status of .php code and messages
			echo json_encode(array('status'=>'true','message'=>'User created: '.$username.'<br/>'));
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

//Checks if username is unique
function checkUsernameUnique($conn,$username,$errorcode) {

	//Executes sql command
	$num = executeCommand($conn,'SELECT COUNT(*) from users u where u.user_name =\''.$username.'\'');

	//If oci_parse finds email in use return false and alert user
	if($num[0][0] >= 1) {
		$errorcode[1] = 'Username is already in use please enter a valid username';
		$errorcode[0] = false;	
	}
	return $errorcode;
}

//Gets person_id from persons table that corresponds with email
function getPersonId($conn,$email){

	//Executes sql command
	$num = executeCommand($conn,'SELECT p.person_id from persons p where p.email =\''.$email.'\'');

	//Returns person_id from perons
	return $num[0][0];
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
//Checks password is same when confirming
function checkPasswordSame($password,$password2,$errorcode) {
	if($password != $password2) {
		$errorcode[1] = 'password fields are not the same';
		$errorcode[0] = false;	
	}
	return $errorcode;
}

?>
