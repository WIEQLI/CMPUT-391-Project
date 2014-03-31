<?php
/*
This database function takes in a database connection and a sql statement. It then executes
the sql statment and returns array(values,condition). Where condition is the result of the sql
statment whether it executed successfully or not. The values is the single row from the results of the
sql statment.

Uses: $conn (database connection), $sql (SQL Statement)

*/
function executeCommand($conn,$sql) {
	//Prepares sql using connection and returns statement identifier	
	$stid = oci_parse($conn,$sql);

	//Executes statement returned from oci_parse
	$res = oci_execute($stid);

	//If error, retrieve the error using oci_error() function and output an error message
	if (!$res) {
		$err = oci_error($stid);
		echo htmlentities($err['message']);
		return array(null,false);
	}
	else{
		//Fetches results of oci_parse and outputs it as array
		$num = oci_fetch_array($stid,OCI_NUM);

		// Frees the statement identifier
		oci_free_statement($stid);

		return array($num,true);
	}
}

?>
