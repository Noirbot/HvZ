<?
	require("../scripts/lib.php");
	global $db;

    $faction = verify($_SESSION["gtname"]);

	if ($faction == "admin")
	{
		if (isset($_GET['id']) and is_numeric($_GET['id']))
		{
			$player = $_GET['id'];
            
            $get_query = "SELECT faction, player_code, gt_name, kills FROM users WHERE id=$player";
            
            $get_res = $db->query($get_query)or die($db->error);

            $obj = $get_res->fetch_object();
            
            $kills = $db->query("SELECT * FROM kills WHERE killer=$obj->gt_name");
            
            if ($obj->faction != "ZOMBIE" || $obj->kills != "0")
                header("Location: http://www.youtube.com/watch?v=oHg5SJYRHA0");
            else
            {
                $new_code = md5(md5(md5($obj->gt_name." ".time())));

                $player_code = "";

                for($i=1; $i<=10; $i++){
                    $player_code .= $new_code{32-$i};
                }

                $killer_res = $db->query("SELECT killer FROM kills WHERE victim='$obj->gt_name'") or die($db->error . " SELECT killer FROM kills WHERE victim='$obj->gt_name'");
                $killer = $killer_res->fetch_object();

                if ($killer_res->num_rows == 0)
                    die("WTF NO Killer.");

                $db->query("UPDATE users SET faction='HUMAN', player_code='$player_code' WHERE id=$player") or die($db->error . " UPDATE users SET faction='HUMAN', player_code='$player_code' WHERE id=$player");
                $db->query("UPDATE users SET kills = kills-1 WHERE gt_name ='$killer->killer'") or die($db->error . " UPDATE users SET kills = kills-1 WHERE gt_name = '$killer->killer");
                $db->query("DELETE FROM kills WHERE victim ='$obj->gt_name'") or die($db->error . " DELETE FROM kills WHERE victim = $obj->gt_name");
            }
		}
        
        $return = $_SERVER['HTTP_REFERER'];
        
        header("Location: $return");
	}
    else
        header("Location: http://www.youtube.com/watch?v=oHg5SJYRHA0");
?>