<?php
/*
* Ends a users session and redirects back to loginform.php
*/
 session_start();
 unset($_SESSION['user_name']);
 header('Location:loginform.php');
?>
