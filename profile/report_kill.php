<?
require("../scripts/lib.php");

$errors = "";
if( (!isset($_POST['map_x']) or !isset($_POST['map_y'])) or (isset($_POST['map_x']) and $_POST['map_x']==0) or (isset($_POST['map_y']) and $_POST['map_y']==0) )
	$errors .= "Please indicate where you tagged the victim<br>";
if( !isset($_POST['code']) or (isset($_POST['code']) and $_POST['code']=="")  )
	$errors .= "The player code of your victim is required<br>";
if( !isset($_POST['feed1']) or !isset($_POST['feed2']) )
 	$errors .= "Don't forget to feed your zombie brethren!<br>";

if( isset($_POST['code']) and $_POST['code']!="" ){
	$code = $db->escape_string($_POST['code']);
	$x = $db->escape_string($_POST['map_x']);
	$y = $db->escape_string($_POST['map_y']);
	$feed1 = $db->escape_string($_POST['feed1']);
	$feed2 = $db->escape_string($_POST['feed2']);

    if (!is_numeric($x) || !is_numeric($y))
        header("Location: http://www.lemonparty.org");
	
	$res = $db->query("SELECT * FROM users WHERE player_code='$code' LIMIT 1");
	if(!$res->num_rows)
		$errors.="Invalid player code! Try again.  If the problem persits, contact the admins<br>";
	else{
		$r = $res->fetch_assoc();
		if ($r['faction'] != 'HUMAN')
			$errors.="That human's a spy! (The code you entered does not belong to an active human)";
		else{
			$insert_kill = mysql_query("INSERT INTO kills (killer, victim, lat, lng) VALUES ('$gt_name','".$r['gt_name']."', '$y', '$x')") or die("insert_kill fail");
			if( date("H") < "07" )
				$starve = date("Y-m-d H:m:s", strtotime("+28 hours"));
			else
				$starve = date("Y-m-d H:m:s", strtotime("+28 hours"));
			$change_human = $db->query("UPDATE users SET faction='ZOMBIE', starve_time='$starve', kills=0 WHERE player_code='$code'") or die("Zombify Human Fail - UPDATE users SET (faction='ZOMBIE', starve_time='$starve', kills=0) WHERE player_code='$code'");
			$increment_kills= $db->query("UPDATE users SET starve_time = DATE_ADD(NOW(), INTERVAL 2 DAY), kills=kills+1 WHERE gt_name='$gt_name'") or die("Count kills fail");
			$feed_zombies = $db->query("UPDATE users SET starve_time = DATE_ADD(NOW(), INTERVAL 2 DAY) WHERE gt_name = '$feed1' or gt_name = '$feed2'") or die("Feed zombies fail");
		}
	}
	
	$quizzed = taken_quiz($gt_name);
	if ($quizzed == False)
	{
		header("Location: http://hvz.gatech.edu/faction/inactive.php");
	}	
}

if($errors == "")
	$_SESSION['error'] = 0;
else{
	$_SESSION['error'] = $errors;
		
}


header( "Location: http://hvz.gatech.edu/killboard/" );
die();

?>