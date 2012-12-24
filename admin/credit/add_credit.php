<?php 
	require("../../scripts/lib.php");

	$faction = verify($_SESSION["gtname"]);

	if($faction != 'admin'){
		header( "Location: http://hvz.gatech.edu/faction/$faction.php" );
	}

	$id = $_POST["id"];
    $late = $_POST["late"];
	
	if (!strcasecmp($late, "true"))
        $query = "UPDATE users SET late_mission=late_mission+1 WHERE id=$id";
    else
        $query = "UPDATE users SET early_mission=early_mission+1 WHERE id=$id";

    $db->query($query);
?>