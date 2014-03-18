<?php
function dbConnect(){
	$conn =  oci_connect('kjross', 'Calgary4life');
	if (!$conn) {
		$e = oci_error();
		trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
	}
	return $conn;
}
?>
