<?php
//Checks text if empty and alerts user and returns false if empty
function checkFieldEmpty($text,$message,$errorcode){
	if(empty($text)) {
		$errorcode[1] = $errorcode[1].$message;
		$errorcode[0] = false;
	}
	return $errorcode;
}
?>




