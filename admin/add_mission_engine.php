<?php 
	require("../scripts/lib.php"); 

	$faction = verify($gt_name);
	
	if($faction != 'admin'){
		if($local)
			header( "Location: http://localhost/faction/$faction.php" );
		else
			header( "Location: http://hvz.gatech.edu/faction/$faction.php" );
	}

	$name = (isset($_POST['name']) and $_POST['name']) ? mysql_real_escape_string($_POST['name']) : 'error';
	$start_datetime = ($_POST['start_datetime'] == 'YYYY-MM-DD 23:59:59') ? 'error' : mysql_real_escape_string($_POST['start_datetime']);
	$end_datetime = ($_POST['end_datetime'] == 'YYYY-MM-DD 23:59:59') ? 'error' : mysql_real_escape_string($_POST['end_datetime']);
	$release_datetime = ($_POST['release_datetime'] == 'YYYY-MM-DD 23:59:59') ? 'error' : mysql_real_escape_string($_POST['release_datetime']);
	$location = (isset($_POST['location']) and $_POST['location']) ? mysql_real_escape_string($_POST['location']) : 'error';
	$faction = mysql_real_escape_string($_POST['faction']);
	$description = (isset($_POST['description']) and $_POST['description']) ? mysql_real_escape_string($_POST['description']) : 'error';

	if( $name == 'error' or $start_datetime == 'error' or $end_datetime == 'error' or $release_datetime == 'error' or $location == 'error' or $faction == 'error' or $description == 'error')
		$error = "Oh noes! You filled something out wrong!";
	else{
		mysql_query("INSERT INTO missions (name, start_datetime, end_datetime, release_datetime, location, faction, description) VALUES ('$name', '$start_datetime', '$end_datetime', '$release_datetime', '$location', '$faction', '$description')") or die(mysql_error());
		}
	($error) ? die($error) : $a=1;

	header("Location: http://hvz.gatech.edu/admin/add_mission.php");
?>