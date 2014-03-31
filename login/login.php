<?php
/* This module takes in input from a username and password entered from
   loginform.php. This module process the inputs validated them and if the 
   username and password is correct returns row of the user for further 
   processing in loginform.php
*/

//Inserts general functions
require('../usermanagement/php/processfield.php');
require('../usermanagement/php/checkfieldlength.php');
require('../usermanagement/php/checkfieldempty.php');

//Create errorcode array that hold status of errors and messages
$errorcode = array(true,'');

//Posts information from fields into variables 
$username = strtolower(processField($_POST['username']));
$password = processField($_POST['password']);

//Checks that fields arent empty
$errorcode = checkFieldEmpty($username,'Please enter your username <br/>',$errorcode);
$errorcode = checkFieldEmpty($password,'Please enter a password <br/>',$errorcode);

//Checks that fields are appropriate size
$errorcode = checkFieldLength(24,$username,"Please enter a username with less then 24 characters <br/>",$errorcode);
$errorcode = checkFieldLength(24,$password,"Please enter a password with less then 24 characters <br/>",$errorcode);

//If passes all checks
if($errorcode[0]) {
	require('/compsci/webdocs/kjross/web_docs/database/dbconnect.php');
	require('/compsci/webdocs/kjross/web_docs/database/executecommand.php');

	
	//Starts session
	session_start();
	
	//Establish connection to database
	$conn = dbConnect();

	//Executes sql command
	$num = executeCommand($conn,'SELECT u.user_name,u.class FROM users u WHERE u.user_name =\''.$username.'\' AND u.password =\''.$password.'\'');

	//Closes connection
	oci_close($conn);

	
	//If doesnt find the right count
	if($num[0]) {
		echo json_encode(array('status'=>true,'message'=>'Found User','username'=>$num[0][0],'class'=>$num[0][1]));	
		$_SESSION['user_name'] = $username;
	}
	else {
		echo json_encode(array('status'=>false,'message'=>'Invalid username/password please re-enter and try again'));
		
	}
}
else {
	//Returns status of .php code and messages
	echo json_encode(array('status'=>$errorcode[0],'message'=>$errorcode[1]));
}
?>
