<?php
/*
This module process data from searchform.php. It creates an sql statement based off
of the inputs from this form. This sql statment in turn searches for records with the 
correct results from the sql statement

Uses: dbconnect.php, executecommand.php, executecommand2.php
*/

//Search sql code based off code from James Hodgson, Tyler Wendlandt, Troy Murphy
$s_date = $_POST['date'];
$f_date = $_POST['date2'];
$order = $_POST['order'];
$keywords = str_replace(' ','|',$_POST['parameter']);

//Checks if search parameter(s) exists
if((!empty($s_date) and !empty($f_date)) or !empty($keywords)) {
	require('../../database/dbconnect.php');
	require('../../database/executecommand.php');
	require('../../database/executecommand2.php');
	
	//Create conditions for SQL statment
	$conditions = '';

	//If keyword exists
	if(!empty($keywords)) {
		//Condition to find keywords
		$conditions .= ' AND(';
		$conditions .= ' (contains(p.first_name, \''.$keywords.'\', '.'1'. ') > 0)';
		$conditions .= ' OR (contains(p.last_name, \''.$keywords.'\', '.'2'. ') > 0)';
		$conditions .= ' OR (contains(r2.diagnosis, \''.$keywords.'\', '.'3'. ') > 0)';
		$conditions .= ' OR (contains(r2.description, \''.$keywords.'\','.'4'. ' ) > 0)';
		$conditions .= ')';
	}
	
	//If dates exists
	if(!empty($f_date) and !empty($s_date)) {
		//Condition for date
		$conditions .= ' AND (r2.test_date BETWEEN TO_DATE(\''.$s_date.'\', \'YYYY-MM-DD\') AND TO_DATE(\''.$f_date.'\', \'YYYY-MM-DD\'))';
	}
	//Establish connection to database
	$conn = dbConnect();
	
	//Starts session
	session_start();
	
	//Obtaining user data
	$res = getUserData($conn,$_SESSION['user_name']);

	//Add conditions for user to see their own records
	if($res[0][2] == 'p'){
		$conditions .= ' AND r2.patient_id = '.$res[0][4];
	}
	if($res[0][2] == 'd'){
		$conditions .= ' AND r2.doctor_id = '.$res[0][4];
	}
	if($res[0][2] == 'r'){
		$conditions .= ' AND r2.radiologist_id = '.$res[0][4];
	}

	//Add conditions for order of records
	if($order == 'default' and !empty($keywords)) {
		$conditions .= ' ORDER BY (RANK() OVER (ORDER BY(6*(SCORE(1)+SCORE(2)) + 3*SCORE(3) + SCORE(4)))) DESC';
	}
	else if($order == 'mostrecentlast') {
		$conditions .= ' ORDER BY r2.test_date ASC';
		
	}
	else {
		$conditions .= ' ORDER BY r2.test_date DESC';
	}

	$sql = 'SELECT r2.record_id, p.first_name, p.last_name,d.first_name,d.last_name,r.first_name,r.last_name, r2.test_type, r2.prescribing_date,r2.test_date, r2.diagnosis, r2.description, p2.full_size
		FROM radiology_record r2, persons p, persons d, persons r, pacs_images p2
		WHERE r2.patient_id = p.person_id AND r2.doctor_id = d.person_id AND r2.radiologist_id = r.person_id AND p2.record_id = r2.record_id AND p2.image_id = 1 '.$conditions;
	
	//Executes sql statement
	$resArray = executeCommand2($conn,$sql);

	//Creating array for images
	$iArray = array();

	//Creates radiology record image array which contains all images for radiology records
	for($i=0;$i < count($resArray[0]);$i++){
		array_push($iArray,getRecordImages($conn,$resArray[0][$i][0]));
	}
	echo json_encode(array('status'=>true,'rArray'=>$resArray[0],'iArray'=>$iArray));

}
else {
	echo json_encode(array('status'=>false,'message'=>'Please enter search fields'));	
}

//Gets users data based on username
function getUserData($conn,$username){
	//Executes sql command
	$num = executeCommand($conn,'SELECT user_name, password, class, date_registered, person_id FROM users WHERE user_name =\''.$username.'\'');

	return $num;
}
//Gets all images for radiology record
function getRecordImages($conn,$r_id){
	$sql = 'SELECT p.full_size FROM pacs_images p WHERE p.record_id = \''.$r_id.'\'';

	//Execute sql command
	$num = executeCommand2($conn,$sql);

	//Creating array for images
	$iArray = array();

	//Adds all images to array and base64_encodes them for AJAX passing
	for($i=0;$i < count($num[0]);$i++) {
		array_push($iArray,base64_encode($num[0][$i][0]));
	}
	return $iArray;
}
?>




