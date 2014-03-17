<?php
//Gets rid of any unneeded characters for security purposes and return whether is blank or not
function processField($text) {
	$text = trim($text);
	$text = stripslashes($text);
	$text = htmlspecialchars($text);
	return $text;
}
?>
