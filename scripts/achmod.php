<?
	require("lib.php");
	global $ach_db;
	//if ($faction == "admin")
	{
		if (isset($_GET['aid']) and isset($_GET['name']) and isset($_GET['mode']))
		{
			$player = $_GET['name'];

			if (strcasecmp($player, "iwiden3") == 0)
				die();

			$ach_id = $_GET['aid'];
			$add_query = "INSERT INTO `ach_gets` (`user`, `ach_id`) VALUES ('$player', $ach_id)";
			$rem_query = "DELETE FROM `ach_gets` WHERE `user` = '$player' AND `ach_id` = $ach_id";
			
			if ($_GET['mode'] == "add")
			{
				$ach_db->query($add_query);
				
			}
			else
			{
				$ach_db->query($rem_query);
			}
		}
	}
?>