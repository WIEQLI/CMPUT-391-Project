<?php
/*
This database function is used to grab the next element in a sequence table. This function
takes in a database connection ($conn) and a parameter (name of sequence). It returns the next 
element in the sequence.

Uses: database connection ($conn) and name of sequence ($parameter)

*/
function getTableId($conn,$parameter){
	
	//Prepares sql using connection and returns statement identifier	
	$stid = oci_parse($conn,'SELECT seq_'.$parameter.'_id.NEXTVAL from dual');

	//Executes statement returned from oci_parse
	$res = oci_execute($stid);

	//If error, retrieve the error using oci_error() function and output an error message
	if (!$res) {
		$err = oci_error($stid);
		echo htmlentities($err['message']);
	}

	//Fetches results of oci_parse and outputs it as array
	$num = oci_fetch_array($stid,OCI_NUM);

	// Frees the statement identifier
	oci_free_statement($stid);

	return (int) $num[0];
}
?>
