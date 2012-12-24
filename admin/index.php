<?php 
	require("../scripts/lib.php"); 

	$faction = verify($gt_name);

	if($faction != 'admin'){
		header( "Location: http://hvz.gatech.edu/faction/$faction.php" );
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Humans vs Zombies - Georgia Tech</title>
<link type="text/css" rel="stylesheet" href="../css/base.css">

</head>
<body>
<div id="header">
	<div class="hiddenItem">
		<a href="#start" accesskey="2">Skip to content.</a>
		<a href="#navigation" accesskey="3">Skip to navigation.</a>
	</div>
	<div id="logoWrapper" class="clearfix">
		<h1 id="logo">
			<a href="http://www.gatech.edu" accesskey="1"> </a>
		</h1>
	<div id="siteLogo">
	Humans vs Zombies</div>
	</div>
	<div id="utilityBar"></div>
	<div id="breadcrumb"><form action='../logout.php'><input type='submit' value='Logout' style='float:right; margin-right:250px;'/></form></div>
</div>
<div id="meat">
	<div id="support"><a name="navigation"></a>
		<?php printSideBar(); ?>
	</div>
	<div id="deathbar">
		<?php print_deathbar(); ?>
	</div>
	<div id="content" class="">
		<!--<h2><a href="table.php">Player Table</a></h2>-->
		<h2><a href="search_table.php">New Player Table</a></h2>
		<h2><a href="add_mission.php">Add Mission</a></h2>

		<h2><a href="past_missions.php">Edit Missions</a></h2>
			
	
		<h2><a href="send_email.php">Email Players</a></h2>
	
	
		<!--<h2><a href="npc.php">Talk Like an NPC</a></h2>-->
	
		<h2><a href="../faction/zombie.php">Zombie View</a></h2>
		<h2><a href="../faction/human.php">Human View</a></h2>
        <h2><a href="betachat.php">Chat Control</a></h2>
        <h2><a href="credit/gtidLookup.php">Grant Mission Credit</a></h2>
   </div>
</div>
</body>
</html>