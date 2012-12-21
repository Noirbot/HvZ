<?php
	require("../scripts/lib.php");
	
	if( count($_POST) == 0 ){
		header( "Location: http://hvz.gatech.edu/profile/signup.php" );
		die();
	}
	$fname = $db->escape_string($_POST['fname']);
	$lname = $db->escape_string($_POST['lname']);
	$slogan= $db->escape_string($_POST['slogan']);
	$gender= $db->escape_string($_POST['gender']);
	$major = $db->escape_string($_POST['major']);
	$year  = $db->escape_string($_POST['year']);

	$fname = htmlspecialchars($fname);
	$lname = htmlspecialchars($lname);
	$slogan = htmlspecialchars($slogan);
	$gender = htmlspecialchars($gender);
	$major = htmlspecialchars($major);
	$year = htmlspecialchars($year);
	

	if( !($fname && $lname ) ){
		//ERROR ERROR
		//Go back, with errors mentioned
	}
	
	else{
		$query = "UPDATE `users` SET fname='$fname', lname='$lname', faction='INACTIVE', slogan='$slogan', gender='$gender', major='$major', year='$year',signed_up='1' WHERE gt_name='$gt_name'";
        $db->query($query) or die("UPDATE query fail");
		
		header( "Location: http://hvz.gatech.edu/profile/" );
		die();
	}
?>