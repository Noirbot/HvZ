<?php 
	require("../../scripts/lib.php");

	$faction = verify($gt_name);

	if($faction != 'admin'){
		header( "Location: http://hvz.gatech.edu/faction/$faction.php" );
	}

	$id = $_GET["id"];
	
	$query = "UPDATE users SET mission_count=mission_count+1 WHERE id=$id";

    $db->query($query);
?>