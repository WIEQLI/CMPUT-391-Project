<?php
//Checks if field is appropraite size
function checkFieldLength($maxlength,$text,$message,$errorcode) {
	if(strlen($text) > $maxlength) {
		$errorcode[1] = $errorcode[1].$message;
		$errorcode[0] = false;
	}
	return $errorcode;
}
?>
