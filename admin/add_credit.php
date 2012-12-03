<?php 
	require("../scripts/lib.php"); 

	$faction = verify($gt_name);

	if($faction != 'admin'){
		if($local)
			header( "Location: http://localhost/faction/$faction.php" );
		else
			header( "Location: http://hvz.gatech.edu/faction/$faction.php" );
	}

	$id = $_GET["id"];
	
	$query = "UPDATE users SET mission_count=mission_count+1 WHERE id=$id";

	mysql_query($query);
?>