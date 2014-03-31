<?php
/*Gets users data based on username. This is used when checking 
 whether user is of certain class or not. Returns to row of users 
 data based off of the sessions username. 

 Uses: dbconnect.php, executecommand.php

*/
function getUserData($username){
	require('../database/dbconnect.php');
	require('../database/executecommand.php');

	//Establish connection to database
	$conn = dbConnect();

	//Executes sql command
	$num = executeCommand($conn,'SELECT user_name, password, class, date_registered, person_id FROM users WHERE user_name =\''.$username.'\'');

	//Closes connection
	oci_close($conn);

	return $num;
}
?>
