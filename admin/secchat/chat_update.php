<?php
require("../../scripts/lib.php");
//session_start();

/*if (strcasecmp($_SERVER['HTTP_REFERER'], "http://hvz.gatech.edu/chat/index.php") != 0 && strcasecmp($_SERVER['HTTP_REFERER'], "http://hvz.gatech.edu/chat/") != 0)
{
	echo $_SERVER['HTTP_REFERER'];
	return;
}*/

//$user_faction = strtolower($_SESSION["faction"]);
$user_faction = "zombie";

$faction = isset($_GET['faction']) ? mysql_real_escape_string($_GET['faction']) : die("Invalid Faction");
//$count = isset($_GET['count']) ? mysql_real_escape_string($_GET['count']) : "";

echo $_SESSION["gtname"] . " | " . $user_faction . " | " . $faction . " | " . $faction . "\n";

if (strcasecmp($faction, "all") == 0 || strcasecmp($faction, $user_faction) == 0 || strcasecmp($user_faction, "admin") == 0)
{
		echo ("Authed");

		$res = mysql_query("SELECT twits.id AS tid, fname, lname, timestamp, faction, comment FROM `twits` JOIN (`users`) ON (twits.user=users.gt_name) WHERE audience='$faction' ORDER BY twits.id");
	
		while( $r = mysql_fetch_array($res)){
			$id=$r['tid'];
			$time = date('D H:i', strtotime($r['timestamp']));
			$fname=$r['fname'];
			$lname=$r['lname'];
			$comment=$r['comment'];
			$chat_faction = $r['faction'];
            $faction_line = strtolower($chat_faction) . "_line";

            /*echo "<tr class='chat_line $faction_line'>\n";
	
			if($user_faction)
				echo "<td><input type='button' value='X' style='float:left; color:red; border:none; font-size:10px; line-height:10px;' onclick='remove_chat($id, \"$faction\")'></td>";
	
			echo "<td><a href='../../profile/view_profile.php?id=$uid'>$fname $lname</a></td><td>$time</td><td><div class='comments'>$comment</div></td>";
	
			echo "</tr>\n";*/
		}
}
?>