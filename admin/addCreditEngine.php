<?php
	require("../scripts/lib.php"); 

	$faction = verify($gt_name);
	
	if($faction != 'admin'){
		if($local)
			header( "Location: http://localhost/faction/$faction.php" );
		else
			header( "Location: http://hvz.gatech.edu/faction/$faction.php" );
	}

	if ($_FILES["file"]["error"] > 0)
	{
		echo "Error: " . $_FILES["file"]["error"] . "<br />";
	}
	else
	{
		//echo "Upload: " . $_FILES["file"]["name"] . "<br />";
		//echo "Type: " . $_FILES["file"]["type"] . "<br />";
		//echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
		//echo "Stored in: " . $_FILES["file"]["tmp_name"] . "</br>";
		move_uploaded_file($_FILES["file"]["tmp_name"], "csvStore/temp.csv");

		$fp = fopen("csvStore/temp.csv", "r");
		$fail = "GTIDs Not In Database: ";

		while (!feof($fp))
		{
			$line = fgets($fp);
			$gtid = substr($line, 0, 9);
			$query = "SELECT late_mission, early_mission, mission_count FROM users WHERE gtid = '$gtid'";
			$res = mysql_query($query) or die(mysql_error());
			$row = mysql_fetch_row($res);
			
			if (!$row)
			{
				$fail .= $gtid . " ";
				continue;
			}

			if (strcasecmp($row[0], "1") == 0)
				mysql_query("UPDATE users SET mission_count = mission_count+1 WHERE gtid = '$gtid'");
			else
				mysql_query("UPDATE users SET late_mission = 1 WHERE gtid = '$gtid'");
		}

		$fail = urlencode($fail);
		header("Location: http://hvz.gatech.edu/admin/addMissionCredit.php?error=$fail");
	}
?>