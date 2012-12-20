<?php
	require("../scripts/lib.php");

	$faction = verify($gt_name);

	if(isset($_POST['slogan'])){
		$slogan = $db->escape_string($_POST['slogan']);
		$slogan = htmlspecialchars($slogan);
		$res = $db->query("UPDATE users SET slogan='$slogan' WHERE gt_name='$gt_name'");
	}
	
	if(isset($_POST['avatar']))
	{
		if (strcasecmp($_POST['avatar'], "admin.png") == 0 || strcasecmp($_POST['avatar'], "human.png") == 0 || strcasecmp($_POST['avatar'], "zombie.png") == 0 || strcasecmp($_POST['avatar'], "inactive.png") == 0)
			header("Location: http://www.lemonparty.org");
		$avatar = $db->escape_string($_POST['avatar']);
		$res = $db->query("UPDATE users SET avatar='$avatar' WHERE gt_name='$gt_name'");
	}

	$a = $_SERVER['HTTP_REFERER'];
	header("Location: $a");
?>
