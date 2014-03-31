<?php
/*
This module is used to update a user in the database. This
module takes information from updateuserform.php, validates it,
and if successful updates the user with information

Uses: dbconnect.php, gettableid.php, executecommand.php
*/
//Processes fields text
$class = $_POST['classes'];
$username  = $_POST['username3'];

require('../../database/dbconnect.php');
require('../../database/gettableid.php');
require('../../database/executecommand.php');

//Establish connection to database
$conn = dbConnect();

//SQL command for entering values into persons
$sql = 'UPDATE users SET class = \''.$class.'\'WHERE user_name = \''.$username.'\''; 

//Executes sql command
$num = executeCommand($conn,$sql);

//Closes connection
oci_close($conn);

if($num[1]) {
	//Returns status of .php code and messages
	echo json_encode(array('status'=>'true','message'=>'User sucessfully updated','class'=>$username));
}
else {
	//Returns status of .php code and messages
	echo json_encode(array('status'=>false,'message'=>'Error executing command to database'));
}
?>
