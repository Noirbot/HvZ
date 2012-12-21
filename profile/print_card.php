<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Player Card</title>
<link type="text/css" rel="stylesheet" href="/css/base.css">
</head>
<body>
	<?php 
		require("../scripts/lib.php"); 
		
    	$gt_name = $_ENV["REMOTE_USER"];
		
		$res = $db->query("SELECT * FROM `users` WHERE `gt_name`='$gt_name'") or die("User Query Fail");
		$r = $res->fetch_assoc();
	?><br />

	<div id='player_card' style="margin-left:20px;">
		<div id="tally">Zombie Tally</div>
		<img src="../images/hvz.png" width="55%" /><br />
		<h1><?php echo $r["fname"]." ".$r['lname']; ?></h1>
		
		<ul><font face="monospace" size="4">ID:</font>
			<?php 
				foreach(str_split($r["player_code"]) as $char){
					echo "<li>$char</li>\n";
				}
			?>
		</ul>
	</div>
	
	<p>Print it! Cut it out! Surrender it upon being tagged!</p>	
	
	<p><a href="index.php">Back to my profile!</a>

<body>
</body>
</html>