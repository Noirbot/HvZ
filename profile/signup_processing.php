<?php
	require("../scripts/lib.php");
	
	if( count($_POST) == 0 ){
		if($local){
			header( "Location: http://localhost/profile/signup.php" );
			die();}
		else{
			header( "Location: http://hvz.gatech.edu/profile/signup.php" );
			die();}
	}
	$fname = mysql_real_escape_string($_POST['fname']);
	$lname = mysql_real_escape_string($_POST['lname']);
	$slogan= mysql_real_escape_string($_POST['slogan']);
	$gender= mysql_real_escape_string($_POST['gender']);
	$major = mysql_real_escape_string($_POST['major']);
	$year = mysql_real_escape_string($_POST['year']);

	$fname = htmlspecialchars($fname);
	$lname = htmlspecialchars($lname);
	$slogan = htmlspecialchars($slogan);
	$gender = htmlspecialchars($gender);
	$major = htmlspecialchars($major);
	$year = htmlspecialchars($year);
	

	if( !($fname & $lname ) ){
		//ERROR ERROR
		//Go back, with errors mentioned
	}
	
	else{
		$query = "UPDATE `users` SET fname='$fname', lname='$lname', faction='INACTIVE', slogan='$slogan', gender='$gender', major='$major', year='$year',signed_up='1' WHERE gt_name='$gt_name'";
		mysql_query($query) or die("UPDATE query fail");
		
		if($local){
			header( "Location: http://localhost/profile/" );
			die();}
		else{
			header( "Location: http://hvz.gatech.edu/profile/" );
			die();}
		
	}







?>