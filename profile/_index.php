<?php
	require("../scripts/lib.php");

	$faction = verify($gt_name);

    if (isset($_POST['id']) && is_numeric($_POST['id']))
		$id = $_POST['id'];
	else
		header("Location: http://www.youtube.com/watch?v=oHg5SJYRHA0");

    $res = $db->query("SELECT gt_name FROM users WHERE id=$id");
    $r = $res->fetch_object();
    
    if (!($r->gt_name == $_SESSION["gtname"] || $faction == 'admin'))
        header("Location: http://www.lemonparty.org");

	if(isset($_POST['slogan'])){
		$slogan = $db->escape_string($_POST['slogan']);
		$slogan = htmlspecialchars($slogan);
		$res = $db->query("UPDATE users SET slogan='$slogan' WHERE id='$id'");
	}
	
	if(isset($_POST['avatar']))
	{
		if (strcasecmp($_POST['avatar'], "admin.png") == 0 || strcasecmp($_POST['avatar'], "human.png") == 0 || strcasecmp($_POST['avatar'], "zombie.png") == 0 || strcasecmp($_POST['avatar'], "inactive.png") == 0)
			header("Location: http://www.lemonparty.org");
		$avatar = $db->escape_string($_POST['avatar']);
		$res = $db->query("UPDATE users SET avatar='$avatar' WHERE id='$id'");
	}

    if(isset($_POST['faction']))
    {
        if ($faction != "admin")
            header("Location: http://www.lemonparty.org");
        
		$faction = $_POST['faction'];
		$signed_up = isset($_POST['signed_up'])?1:0;
		$rules_quiz = isset($_POST['rules_quiz'])?1:0;
        
        $optional = "";
        
        if (isset($_POST['starve_time'])) {
            $starve_time = $_POST['starve_time'];
            $optional .= ", starve_time='$starve_time'";
        }
        
        if ($faction != '---')
            $optional .= ", faction='$faction'";


        $query = "UPDATE users SET slogan='$slogan', signed_up='$signed_up', rules_quiz='$rules_quiz' $optional WHERE id='$id'";
        $db->query($query) or die($db->error);
	}

	$a = $_SERVER['HTTP_REFERER'];
	header("Location: $a");
?>