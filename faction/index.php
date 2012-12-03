<?php 
	
	require("../scripts/lib.php"); 
	
//	if($local)
//		$gt_name='jmartinBANANAPHONE';
//	else
//		$gt_name = $_ENV["REMOTE_USER"];
	
	$faction = verify($gt_name);
	
	$quizzed = taken_quiz($gt_name);
	if ($quizzed == False)
	{
		if($local)
			header("Location: http://localhost/faction/inactive.php");
		else
			header("Location: http://hvz.gatech.edu/faction/inactive.php");
	}
	
	if($local)
		if ($faction != 'admin')
			header( "Location: http://hvz.gatech.edu/faction/$faction.php" );
		else
			header( "Location: http://hvz.gatech.edu/admin/" );
	else
	{
		if ($faction != 'admin')
			header( "Location: http://hvz.gatech.edu/faction/$faction.php" );
		else
			header( "Location: http://hvz.gatech.edu/admin/" );
	}

?>

