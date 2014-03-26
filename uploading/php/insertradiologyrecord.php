<?php

//Inserts general functions
require('/compsci/webdocs/kjross/web_docs/usermanagement/php/processfield.php');
require('/compsci/webdocs/kjross/web_docs/usermanagement/php/checkfieldlength.php');
require('/compsci/webdocs/kjross/web_docs/usermanagement/php/checkfieldempty.php');

//Create errorcode array that hold status of errors and messages
$errorcode = array(true,'');

//Processes fields text
$d_email = strtolower(processField($_POST['email']));
$p_email = strtolower(processField($_POST['email2']));
$test_type = processField($_POST['testtype']);
$test_date = processField($_POST['testdate']);
$p_date = processField($_POST['pdate']);
$diagnosis = processField($_POST['diagnosis']);
$desc = processField($_POST['desc']);

//Checks that fields arent empty
$errorcode = checkFieldEmpty($d_email,'Please enter the doctors email <br/>',$errorcode);
$errorcode = checkFieldEmpty($p_email,'Please enter the patients email <br/>',$errorcode);
$errorcode = checkFieldEmpty($test_type,'Please enter a test type <br/>',$errorcode);
$errorcode = checkFieldEmpty($test_date,'Please enter a test date <br/>',$errorcode);
$errorcode = checkFieldEmpty($p_date,'Please enter a prescribing date <br/>',$errorcode);
$errorcode = checkFieldEmpty($diagnosis,'Please enter a diagnosis <br/>',$errorcode);
$errorcode = checkFieldEmpty($desc,'Please enter a description <br/>',$errorcode);

//Checks that fields are appropriate size
$errorcode = checkFieldLength(128,$d_email,"Please enter a doctors email with less then 128 characters <br/>",$errorcode);
$errorcode = checkFieldLength(128,$p_email,"Please enter a patients email with less then 128 characters <br/>",$errorcode);
$errorcode = checkFieldLength(24,$test_type,"Please enter a test type with less then 24 characters <br/>",$errorcode);
$errorcode = checkFieldLength(128,$diagnosis,"Please enter a diagnosis with less then 128 characters <br/>",$errorcode);
$errorcode = checkFieldLength(1024,$desc,"Please enter a description with less then 1024 characters <br/>",$errorcode);

//Checks that dates ar in correct format
$errorcode = verifyDate($p_date,$errorcode);
$errorcode = verifyDate($test_date,$errorcode);

//If passes all checks
if($errorcode[0]) {
	require('/compsci/webdocs/kjross/web_docs/database/dbconnect.php');
	require('/compsci/webdocs/kjross/web_docs/database/executecommand.php');
	require('/compsci/webdocs/kjross/web_docs/database/gettableid.php');

	//Establish connection to database
	$conn = dbConnect();

	//Checks email exists in persons
	$errorcode = checkEmailExists($conn,$d_email,$errorcode,'Doctor doesnt exist with that email. Insert new email or create person with that email.');
	$errorcode = checkEmailExists($conn,$p_email,$errorcode,'Patient doesnt exist with that email. Insert new email or create person with that email.');

	//If emails exist
	if($errorcode[0]) {
	
		//Gets next person_id in persons table
		$id = getPersonId($conn,$d_email);
		$id2 = getPersonId($conn,$p_email);

		//Gets record id
		$record_id = getTableId($conn,'radiologyrecord');

		//Starts session
		session_start();

		//Get radiologist data
		$res = getUserData($conn,$_SESSION['user_name']);

		//SQL command for entering values into users
		$sql = 'INSERT INTO radiology_record (record_id, patient_id, doctor_id, radiologist_id, test_type, prescribing_date, test_date, diagnosis, description) VALUES (\''.$record_id.'\',\''.$id2.'\',\''.$id.'\',\''.$res[0][4].'\',\''.$test_type.'\',TO_DATE(\''.$p_date.'\',\'YYYY-MM-DD\'),TO_DATE(\''.$test_date.'\',\'YYYY-MM-DD\'),\''.$diagnosis.'\',\''.$desc.'\')';

		//Executes sql command
		$num = executeCommand($conn,$sql);
		
		//Cycles through uploaded images and adds them to pacs_images
		$i = 1;
		foreach ($_FILES['imageuploads']['tmp_name'] as $key => $tmp_name) {
			//Creates smaller images
			 $path = resize($_FILES['imageuploads']['tmp_name'][$key],0.5,$_FILES['imageuploads']['type'][$key],false);
			 $path2 = resize($_FILES['imageuploads']['tmp_name'][$key],0.25,$_FILES['imageuploads']['type'][$key],true);
				
			//Uploads images to db
    			uploadImage($conn,file_get_contents($_FILES['imageuploads']['tmp_name'][$key]),file_get_contents($path),file_get_contents($path2),$record_id,$i);

			//Destroys images;
			//imagedestroy($path);
			//imagedestroy($path2);
			$i++;
		}
		//Closes connection
		oci_close($conn);

		if($num[1]) {
			//Returns status of .php code and messages
			echo json_encode(array('status'=>true,'message'=>'Radiology Record Successfully added'));
		}
		else {
			//Returns status of .php code and messages
			echo json_encode(array('status'=>false,'message'=>'Error executing command to database'));
		}
	}	
	else {
		//Closes connection
		oci_close($conn);

		//Returns status of .php code and messages
		echo json_encode(array('status'=>$errorcode[0],'message'=>$errorcode[1]));
	}	
}
else {
	//Returns status of .php code and messages
	echo json_encode(array('status'=>$errorcode[0],'message'=>$errorcode[1]));
}

//Gets person_id from persons table that corresponds with email
function getPersonId($conn,$email){

	//Executes sql command
	$num = executeCommand($conn,'SELECT p.person_id from persons p where p.email =\''.$email.'\'');

	//Returns person_id from perons
	return $num[0][0];
}

//Checks if email provided exists in persons
function checkEmailExists($conn,$email,$errorcode,$message) {
	//Executes sql command
	$num = executeCommand($conn,'SELECT COUNT(*) from persons p where p.email =\''.$email.'\'');

	//If oci_parse doesnt find email within persons
	if($num[0][0] == 0) {
		$errorcode[1] = $message;
		$errorcode[0] = false;	
	}
	return $errorcode;
}

//Verify that date provided is correct
function verifyDate($date,$errorcode){
	if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$date)) {
		$temp = explode("-",$date);
		if(!checkdate($temp[1],$temp[2],$temp[0])){
			$errorcode[1] = $errorcode[1].'Invalid Date entered re-enter</br>';
			$errorcode[0] = false;
		}
    }
	else {
        $errorcode[1] = $errorcode[1].'Invalid Date please check format and re-enter</br>';
		$errorcode[0] = false;
    }
	return $errorcode;
}

//Gets users data based on username
function getUserData($conn,$username){
	//Executes sql command
	$num = executeCommand($conn,'SELECT user_name, password, class, date_registered, person_id FROM users WHERE user_name =\''.$username.'\'');

	return $num;
}

//Upload Images based off code from James Hodgson, Tyler Wendlandt, Troy Murphy SQL 
function uploadImage($conn,$image,$image2,$image3,$record_id,$i){
	$blob = oci_new_descriptor($conn, OCI_D_LOB);
	$blob2 = oci_new_descriptor($conn, OCI_D_LOB);
	$blob3 = oci_new_descriptor($conn, OCI_D_LOB);

	$sql = 'insert into pacs_images (record_id, image_id, thumbnail, regular_size, full_size) values(:recordid, :imageid, empty_blob(), empty_blob(), empty_blob()) returning thumbnail, regular_size, full_size into :thumbnail, :regularsize, :fullsize';

	$result = oci_parse($conn, $sql);

	oci_bind_by_name($result, ':recordid', $record_id);
	oci_bind_by_name($result, ':imageid', $i);
	oci_bind_by_name($result, ':regularsize',$blob, -1, OCI_B_BLOB);
	oci_bind_by_name($result, ':thumbnail',$blob2, -1, OCI_B_BLOB);
	oci_bind_by_name($result, ':fullsize',$blob3, -1, OCI_B_BLOB);
	
	oci_execute($result, OCI_DEFAULT);

	if($blob->save($image) && $blob2->save($image2) && $blob3->save($image3)) {
		oci_commit($conn);
	}
	else {
		oci_rollback($conn);
	}
	//oci_free_statement($result);
	$blob->free();
	$blob2->free();
	$blob3->free();
}
//Resizes images based upon images given
function resize($filename,$percent,$type,$isSmall) {
	$original_info = getimagesize($filename);
	$original_w = $original_info[0];
	$original_h = $original_info[1];
	if($type == 'image/jpeg') {
		$original_img = imagecreatefromjpeg($filename);
	}
	if($type == 'image/jpg') {
		$original_img = imagecreatefromjpg($filename);
	}
	if($type == 'image/png') {
		$original_img = imagecreatefrompng($filename);
	}
	$thumb_w = $original_w * $percent;
	$thumb_h = $original_h * $percent;
	$thumb_img = imagecreatetruecolor($thumb_w, $thumb_h);
	imagecopyresampled($thumb_img, $original_img,0, 0,0, 0,$thumb_w, $thumb_h,$original_w, $original_h);
	if($type == 'image/jpeg') {
		
		if($isSmall) {
			imagejpeg($thumb_img, 'temp2.jpeg');
			return 'temp2.jpeg';
		}
		else {
			imagejpeg($thumb_img, 'temp.jpeg');
			return 'temp.jpeg';
		}
	}
	if($type == 'image/jpg') {
		
		if($isSmall) {
			imagejpg($thumb_img, 'temp2.jpg');
			return 'temp2.jpg';
		}
		else {
			imagejpg($thumb_img, 'temp.jpg');
			return 'temp.jpg';
		}
	}
	if($type == 'image/png') {
		
		if($isSmall) {
			imagepng($thumb_img, 'temp2.png');
			return 'temp2.png';
		}
		else {
			imagepng($thumb_img, 'temp.png');
			return 'temp.png';
		}
	}
	//destroyimage($thumb_img);
	//destroyimage($original_img);
}




?>
