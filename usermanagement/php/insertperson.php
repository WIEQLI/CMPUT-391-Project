<?php
//Inserts general functions
require('/compsci/webdocs/kjross/web_docs/usermanagement/php/processfield.php');
require('/compsci/webdocs/kjross/web_docs/usermanagement/php/checkfieldlength.php');
require('/compsci/webdocs/kjross/web_docs/usermanagement/php/checkfieldempty.php');

//Create errorcode array that hold status of errors and messages
$errorcode = array(true,'');

//Processes fields text
$firstname = processField($_POST['firstname']);
$lastname = processField($_POST['lastname']);
$email = processField($_POST['email']);
$address = processField($_POST['address']);
$phone = processField($_POST['phone']);

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
	require('/compsci/webdocs/kjross/web_docs/database/dbconnect.php');
	require('/compsci/webdocs/kjross/web_docs/database/gettableid.php');
	require('/compsci/webdocs/kjross/web_docs/database/executecommand.php');

	//Establish connection to database
	$conn = dbConnect();

	//Checks if email is unique
	$errorcode = checkEmailUnique($conn,strtolower($email),$errorcode);

	//If is unique
	if($errorcode[0]) {
	
		//Gets next person_id in persons table
		$id = getTableId($conn,'persons');

		//SQL command for entering values into persons
		$sql = 'INSERT INTO persons VALUES (\''.$id.'\',\''.$firstname.'\',\''.$lastname.'\',\''.$address.'\',\''.strtolower($email).'\',\''.$phone.'\')';

		//Executes sql command
		 $num = executeCommand($conn,$sql);

		//Closes connection
		oci_close($conn);

		if($num[1]) {
			//Returns status of .php code and messages
			echo json_encode(array('status'=>'true','message'=>'Person created: '.$email.'<br/>'));
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

//Checks if email is unique
function checkEmailUnique($conn,$email,$errorcode) {

	//Executes sql command
	$num = executeCommand($conn,'SELECT COUNT(*) from persons p where p.email =\''.$email.'\'');

	//If oci_parse finds email in use return false and alert user
	if($num[0][0] >= 1) {
		$errorcode[1] = 'Email is already in use please enter a valid email';
		$errorcode[0] = false;	
	}
	return $errorcode;
}
?>
