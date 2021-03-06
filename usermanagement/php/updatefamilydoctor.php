<?php
/*
This module is used to update a family doctor entry in the database. This function
takes data from updatefamilydoctorform.php and update the entry accordingly 
to the database.

Uses: processfield.php, checkfieldlength.php, checkfieldempty.php, dbconnect.php,
gettableid.php, executecommand.php
*/
//Inserts general functions
require('processfield.php');
require('checkfieldlength.php');
require('checkfieldempty.php');

//Create errorcode array that hold status of errors and messages
$errorcode = array(true,'');

//Processes fields text
$d_email = strtolower(processField($_POST['email3']));
$p_email = strtolower(processField($_POST['email4']));

$curr_demail = $_POST['doctor_id'];
$curr_pemail = $_POST['patient_id'];

//Checks that fields arent empty
$errorcode = checkFieldEmpty($d_email,'Please enter your doctors email <br/>',$errorcode);
$errorcode = checkFieldEmpty($p_email,'Please enter your patients email <br/>',$errorcode);

//Checks that fields are appropriate size
$errorcode = checkFieldLength(128,$d_email,"Please enter a doctors email with less then 128 characters <br/>",$errorcode);
$errorcode = checkFieldLength(128,$p_email,"Please enter a patients email with less then 128 characters <br/>",$errorcode);

if($errorcode[0]) {
	require('../../database/dbconnect.php');
	require('../../database/gettableid.php');
	require('../../database/executecommand.php');
	
	//Establish connection to database
	$conn = dbConnect();

	//Checks if person belong to right class
	$errorcode = checkIsClass($conn,$d_email,$errorcode,'d');
	$errorcode = checkIsClass($conn,$p_email,$errorcode,'p');
	
	//If people belong to right class
	if($errorcode[0]) {
		$row = checkFamilyDoctorExist($conn,$p_email,$d_email,$errorcode);

		if($row[0][0] == 'true') {
			//SQL command for entering values into family_doctor
			$sql = 'UPDATE family_doctor SET doctor_id = \''.$row[1][0][0].'\', patient_id= \''.$row[2][0][0].'\' WHERE doctor_id = \''.$curr_demail.'\' AND patient_id = \''.$curr_pemail.'\''; 

			//Executes sql command
			$num = executeCommand($conn,$sql);

			//Closes connection
			oci_close($conn);

			if($num[1]) {
				echo json_encode(array('status'=>$row[0][0],'message'=>'Family doctor updated Doctor: '.$p_email.' Patient: '.$d_email,'d_email'=>$row[1][0][0],'p_email'=>$row[1][0][1],'d'=>$curr_demail));
			}
			else {
				//Returns status of .php code and messages
				echo json_encode(array('status'=>false,'message'=>'Error executing command to database'));
			}
		}
		else {
			//Closes connection
			oci_close($conn);

			echo json_encode(array('status'=>$row[0][0],'message'=>$row[0][1]));
		}
	}
	else {
		//Closes connection
		oci_close($conn);

		echo json_encode(array('status'=>$errorcode[0],'message'=>$errorcode[1]));
	}
}
else {
	//Returns status of .php code and messages
	echo json_encode(array('status'=>$errorcode[0],'message'=>$errorcode[1]));
}

function findUsername($conn,$username,$errorcode) {

	//Executes sql command
	$num = executeCommand($conn,'SELECT u.user_name, u.password, u.class, u.person_id, u.date_registered FROM users u WHERE u.user_name =\''.$username.'\'');
	
	//If oci_parse finds email in use return false and alert user
	if($num[0][0] != $username) {
		$errorcode[1] = 'Username for user doesnt exist';
		$errorcode[0] = false;	
	}
	return array($errorcode,$num);
}

//Checks if person is a doctor/patient
function checkIsClass($conn,$email,$errorcode,$class) {
	//Executes sql command
	$num = executeCommand($conn,'SELECT COUNT(*) FROM persons p, users u WHERE p.email =\''.$email.'\' AND p.person_id = u.person_id AND u.class = \''.$class.'\'');

	//If oci_parse doesnt find email within persons
	if($num[0][0] == 0) {
		$errorcode[1] =  $errorcode[1].$email.' is either not a patient/doctor please make sure you enter persons with right class. <br/>';
		$errorcode[0] = false;	
	}
	return $errorcode;
}

function checkFamilyDoctorExist($conn,$p_email,$d_email,$errorcode) {
	//Getting person_id for doctor
	$p1 = executeCommand($conn,'SELECT p.person_id FROM persons p WHERE p.email =\''.$d_email.'\'');
	
	//Getting person_id for patient
	$p2 = executeCommand($conn,'SELECT p.person_id FROM persons p WHERE p.email =\''.$p_email.'\'');

	//Check family_doctor row is unique
	$num = executeCommand($conn,'SELECT COUNT(*) FROM family_doctor f WHERE f.doctor_id =\''.$p1[0][0].'\' AND f.patient_id =\''.$p2[0][0].'\'');

	//If oci_parse doesnt find email within persons
	if($num[0][0] != 0) {
		$errorcode[1] = 'Family doctor entry with patients and doctors specified already exists';
		$errorcode[0] = false;	
	}
	//Both person_id's are the same
	if($p1[0][0] == $p2[0][0]) {
		$errorcode[1] = 'Emails are the same make sure doctors and patients emails are unique';
		$errorcode[0] = false;	
	}
	return array($errorcode,$p1,$p2);
}

?>
