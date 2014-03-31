<?php
/*
This php function creates a connection to the database. This is based off of the oracle username and password.
it then reutrn the connection to be used on database functions.
*/
function dbConnect(){
$conn = oci_connect("kjross","Calgary4life");
	if (!$conn) {
		$e = oci_error();
		trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
	}
	return $conn;
}
?>

