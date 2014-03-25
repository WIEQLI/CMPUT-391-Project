<?php

$s_date = $_POST['date'];
$f_date = $_POST['date2'];
$keywords = $_POST['parameter'];


require('/compsci/webdocs/kjross/web_docs/database/dbconnect.php');
require('/compsci/webdocs/kjross/web_docs/database/executecommand2.php');
require('/compsci/webdocs/kjross/web_docs/database/gettableid.php');

//Establish connection to database

$conn = dbConnect();

$num = executeCommand($conn,'SELECT * from radiology_record');

echo json_encode(array('test'=>count($num[0])));




?>




