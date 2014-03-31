<?php
/*
This module is used to update a person in the database. This module
takes in information from updatepersonform.php, validates it, and if
successful updates the person with the information.

Uses: processfield.php, checkfieldlength.php, checkfieldempty.php, 
dbconnect.php, gettableid.php, executecommand.php
*/
//Inserts general functions
require('processfield.php');
require('checkfieldlength.php');
require('checkfieldempty.php');

//Create errorcode array that hold status of errors and messages
$errorcode = array(true,'');

//Processes fields text
$firstname = processField($_POST['firstname2']);
$lastname = processField($_POST['lastname2']);
$email = processField($_POST['email3']);
$address = processField($_POST['address2']);
$phone = processField($_POST['phone2']);
$person_id = $_POST['person_id'];

//Checks that fields arent empty
$errorcode = checkFieldEmpty($firstname,'Please enter your first name <br/>',$errorcode);
$errorcode = checkFieldEmpty($lastname,'Please enter your last name <br/>',$errorcode);
$errorcode = checkFieldEmpty($email,'Please enter your email <br/>',$errorcode);
$errorcode = checkFieldEmpty($address,'Please enter your address <br/>',$errorcode);
$errorcode = checkFieldEmpty($phone,'Please enter your phone <br/>',$errorcode);

//Checks that fields are appropriate size
$errorcode = checkFieldLength(24,$firstname,"Please enter an first name with less then 24 characters <br/>",$errorcode);
$errorcode = checkFieldLength(24,$lastname,"Please enter an last name with less then 24 characters <br/>",$errorcode);
$errorcode = checkFieldLength(128,$email,"Please enter an email with less then 128 characters <br/>",$errorcode);
$errorcode = checkFieldLength(128,$address,"Please enter an address with less then 128 characters <br/>",$errorcode);
$errorcode = checkFieldLength(10,$phone,"Please enter a phone number with less then 10 characters <br/>",$errorcode);

//If passes all checks
if($errorcode[0]) {
	require('../../database/dbconnect.php');
	require('../../database/gettableid.php');
	require('../../database/executecommand.php');

	//Establish connection to database
	$conn = dbConnect();

	//SQL command for entering values into persons
	$sql = 'UPDATE persons SET first_name = \''.$firstname.'\', last_name = \''.$lastname.'\', address= \''.$address.'\', email =\''.strtolower($email).'\', phone = \''.$phone.'\' WHERE person_id = \''.$person_id.'\''; 

	//Executes sql command
	 $num = executeCommand($conn,$sql);

	//Closes connection
	oci_close($conn);
	
	if($num[1]) {
		//Returns status of .php code and messages
		echo json_encode(array('status'=>'true','message'=>'Person sucessfully updated'));
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
