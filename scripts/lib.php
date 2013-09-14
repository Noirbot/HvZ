<?php
	session_start();
    $current_game = "fall2013";
    $db_users = array(
    	"achievements" => "achieve",
        "spring2012" => "Spr12",
        "fall2012" => "Fall12",
        "spring2013" => "Spr13",
        "fall2013" => "Fall13"
    );
    $db_pass = array(
    	"achievements" => "legopibb",
        "spring2012" => "pandapool",
        "fall2012" => "legopibb",
        "spring2013" => "sextowel",
        "fall2013" => "badgermoaner"
    );

    $gt_name = isset($_ENV["REMOTE_USER"]) ? $_ENV["REMOTE_USER"] : "";
    $_SESSION["gtname"] = $gt_name;

    //$db = mysql_connect("web-db1.gatech.edu:3306",$db_users[$current_game],$db_pass[$current_game]) or die("DBC Remote Fail");
    $db = new mysqli("web-db1.gatech.edu",$db_users[$current_game],$db_pass[$current_game],$current_game,3306);
    if ($db->errno) {
        die('Connect Error (' . $db->errno . ') ' . $db->error);
    }

    $ach_db = new mysqli("web-db1.gatech.edu",$db_users["achievements"],$db_pass["achievements"],"achievements",3306);
    if ($db->connect_errno) {
        die('Connect Error (' . $db->connect_errno . ') ' . $db->connect_error);
    }


    //mysql_select_db($current_game);
	
	$oz_flush_dates = array(
		"spring2012" => "27 February 2012 23:00",
		"fall2012" => "17 September 2012 23:00",
		"spring2013" => "4 March 2013 23:00",
		"fall2013" => "16 September 2013 23:00"
		);
	$oz_flushed = false;

    $quiz_open_dates = array(
        "spring2012" => "28 February 2012",
        "fall2012" => "10 September 2012",
        "spring2013" => "25 February 2013",
        "fall2013" => "9 September 2013"
    );

    $quiz_close_dates = array(
        "spring2012" => "28 February 2012 17:00",
        "fall2012" => "17 September 2012 17:00",
        "spring2013" => "4 March 2013 17:00",
		"fall2013" => "16 September 2013 17:00"
    );

	if(!isset($_SESSION['sorted'])){
		$_SESSION['Hsort']=array('lname'=>'ASC','fname'=>'ASC');
		$_SESSION['Zsort']=array('lname'=>'ASC','fname'=>'ASC','kills'=>'DESC', 'starve_time'=>'ASC');
		$_SESSION['sorted']=1;
	}


function verify($gt_name){
	global $db;

	$res = $db->query("SELECT * FROM `users` where `gt_name`='$gt_name'") or die("Verify Query Fail");
	
	if($res->num_rows == 0) {
		$db->query("INSERT INTO `users` (gt_name) VALUES ('$gt_name')") or die("Addition Query Fail $gt_name");
        $res2 = $db->query("SELECT id FROM `users` where `gt_name`='$gt_name'");
        $r2 = $res2->fetch_object();
        $_SESSION["id"] = $r2->id;
        $_SESSION["faction"] = "INACTIVE";
	}
	
	// $r = mysql_fetch_assoc($res);
	$r = $res->fetch_assoc();

	if($r["signed_up"]){
		if($r['faction'])
		{
			$_SESSION["faction"] = $r["faction"];
            $_SESSION["id"] = $r["id"];
			return strtolower($r['faction']);
		}
	}

    header( "Location: http://hvz.gatech.edu/profile/signup.php" );
    die();
}

function first_time($gt_name){
	global $db;

	//$res= mysql_query("SELECT signed_up FROM `users` where `gt_name`='$gt_name'", $db) or die("Query Fail");
	$res = $db->query("SELECT signed_up FROM `users` where `gt_name`='$gt_name'") or die("Query Fail");

	//$r = mysql_fetch_array($res);
	$r = $res->fetch_assoc();
	return $r['signed_up']=='0' ? True : False;
}

function taken_quiz($gt_name) {
	global $db;
	
	//$res = mysql_query("SELECT rules_quiz FROM `users` where `gt_name`='$gt_name'", $db) or die("Query Fail");
	$res = $db->query("SELECT rules_quiz FROM `users` where `gt_name`='$gt_name'") or die("Query Fail");

	// $r = mysql_fetch_array($res);
	$r = $res->fetch_assoc();

	return $r['rules_quiz']=='1' ? True : False;
}

function printSideBar(){
	echo "<ul id='mainNav'>\n".
		"            \t<li><a href='/'>Home</a></li>\n".
		"            \t<li><a href='/profile/'>Profile</a></li>\n".
		"            \t<li><a href='/faction/'>Faction HQ</a></li>\n".
		"		\t<li><a href='/killboard/'>Kill Board</a></li>\n".
		"		\t<li><a href='/achievements/'>Achievements</a></li>\n".
		"		\t<li><a href='/chat/'>Chat Room</a></li>\n".
		"            \t<li><a href='/rules/'>Rules</a></li>\n".
		"            \t<li><a href='/the_admins.php'>The Admins</a></li>\n".
		"            \t<li><a href='/contact.php'>Contact</a></li>\n".
		"        </ul>\n";
}


function beta_print_chat($show_del, $chat_faction, $count){
	global $db;

	if ($count != "")
		$cmd = "SELECT * FROM (SELECT twits.id AS tid, users.id AS uid, fname, lname, oz, timestamp, faction, comment FROM `twits` JOIN (`users`) ON (twits.user=users.gt_name) WHERE audience='$chat_faction' ORDER BY twits.id DESC $count) AS `chats` ORDER BY tid";
	else
		$cmd = "SELECT twits.id AS tid, users.id AS uid, fname, lname, oz, timestamp, faction, comment FROM `twits` JOIN (`users`) ON (twits.user=users.gt_name) WHERE audience='$chat_faction' ORDER BY twits.id";
	
	$res = $db->query($cmd);

	while( $r = $res->fetch_assoc()){
		$id=$r['tid'];
		$uid=$r['uid'];
		$time = date('D H:i', strtotime($r['timestamp']));
		$fname=$r['fname'];
		$lname=$r['lname'];
		$comment=htmlspecialchars_decode($r['comment']);
		$faction = ($r['oz'] == 1) ? "HUMAN" : $r['faction'];
        $faction_line = strtolower($faction) . "_line";

		echo "<tr class='chat_line $faction_line'>\n";

        if($show_del)
            echo "<td><input type='button' value='X' style='float:left; color:red; border:none; font-size:10px; line-height:10px;' onclick='remove_chat($id, \"$chat_faction\")'></td>";

        echo "<td><a href='../profile/index.php?id=$uid'>$fname $lname</a></td><td>$time</td><td><div class='comments'><p>$comment</p></div></td>";

        echo "</tr>\n";
	}
}

//function print_chat($user_faction,$chat_faction, $count){
//
//    $res = mysql_query("SELECT twits.id, fname, lname, oz, timestamp, faction, comment FROM `twits` JOIN (`users`) ON (twits.user=users.gt_name) WHERE audience='$chat_faction' ORDER BY twits.id DESC $count");
//
//    while( $r = mysql_fetch_array($res)){
//        $id=$r['id'];
//        $time = date('D H:i', strtotime($r['timestamp']));
//        $fname=$r['fname'];
//        $lname=$r['lname'];
//        $comment=$r['comment'];
//        $fact = ($r['oz'] == 1) ? "human" : strtolower($r['faction']);
//        echo "<div class='chat_item'>\n".
//            "\t<img src='../images/avatars/tiny_$fact.png' width='50px' />\n".
//            "\t<h1>$fname $lname</h1> <h2>$time</h2>\n";
//        if($user_faction=="admin")
//            echo "<input type='button' value='X' style='float:right; color:red; border:none;' onclick='remove_chat($id)'>";
//
//        echo "\t<p>$comment</p>\n".
//            "</div>\n";
//    }
//}

function print_missions($faction){
	global $db;

	// $res = mysql_query("SELECT * FROM `missions` WHERE (`faction`='$faction' OR `faction`='ALL' )AND `release_datetime` < NOW() ORDER BY `start_datetime` DESC") or die("Query Fail");
	$res = $db->query("SELECT * FROM `missions` WHERE (`faction`='$faction' OR `faction`='ALL' )AND `release_datetime` < NOW() ORDER BY `start_datetime` DESC") or die("Query Fail");

	while($r = $res->fetch_assoc()){
		
		$start = date('D H:i', strtotime($r['start_datetime']));
		$end =   date('D H:i', strtotime($r['end_datetime']));
		
		echo	"\n<div id='mission".$r['id']."' class='mission'>\n".
					"\t<h3>".$r['name']."</h3>\n".
					"\t\t<p class='mission_particulars'>\n".
		//echo $start." ".$end;
						"\t\t\t<strong>Start:</strong> $start <br>\n".
						"\t\t\t<strong>End</strong>: $end <br>\n".
						"\t\t\t<strong>Location:</strong> ".$r['location']."<br>\n".
					"\t\t</p>\n".
					"\t\t<p class='mission_description'>".$r['description']."</p>\n".
			 	"\t</div>\n";
		
	}

	
}

function print_quiz(){
    global $db;

	// $rules_res = mysql_query("SELECT * FROM `rules_quiz_questions`");
	$rules_res = $db->query("SELECT * FROM `rules_quiz_questions`");

	$i=0;
	while( $qr = $rules_res->fetch_assoc()){
		// $ans_res = mysql_query("SELECT * FROM `rules_quiz_answers` WHERE `question_id` ='".$qr['id']."'");
		$ans_res = $db->query("SELECT * FROM `rules_quiz_answers` WHERE `question_id` ='".$qr['id']."'");
		echo "\n<p class='question";
		if (isset($_SESSION["wrong"]) && in_array($qr['id'], $_SESSION["wrong"]))
			echo " wrong";
		echo "'>".++$i.".  ".$qr["question"]."</p>";

			while( $ar = $ans_res->fetch_assoc()){
				echo "\n\t\t<input type='radio' name='q".$qr["id"]."' value='".$ar['id']."'>  ".$ar['answer']."</input><br />";

			}

	}
}

function print_deathbar() {
	return;
	global $oz_flushed, $current_game, $db;
    $ozs[] = array();
	$oz_flushed = is_oz_flushed($current_game);
	
	if($oz_flushed){
		$query = "SELECT k_u.fname, k_u.lname, v_u.fname, v_u.lname, k.time, k_u.gt_name
			FROM kills k
			JOIN users k_u ON k.killer = k_u.gt_name
			JOIN users v_u ON k.victim = v_u.gt_name 
			ORDER BY k.time DESC
			LIMIT 5";
	} else {
		$query = "SELECT k_u.fname, k_u.lname, v_u.fname, v_u.lname, k.time, k_u.gt_name
			FROM kills AS k
			JOIN users AS k_u ON k.killer = k_u.gt_name
			JOIN users AS v_u ON k.victim = v_u.gt_name 
			WHERE v_u.oz = 0
			ORDER BY k.time DESC
			LIMIT 5";
	}

	$db_res = $db->query($query) or die($db->error);

	if ($db_res->num_rows == 0)
	{
		return;
	}
	
	if (!$oz_flushed)
	{
		$index = 0;
		$query = "SELECT gt_name FROM users WHERE oz=1";
		$oz_res = $db->query($query) or die($db->error);
		while ($one_oz = $oz_res->fetch_array(MYSQLI_NUM))
		{
			$ozs[$index] = $one_oz[0];
			$index++;
		}
	}
	
	echo ('<h2 style="padding-left:5px;">Fresh Kills</h2>');
	
	while($r = $db_res->fetch_assoc()){
		echo	"<div class='deathbar_item'>\n".
				"\t<h1>".$r["v_u.fname"]." ".$r["v_u.lname"]."</h1>\n";
				
				$time = date('D H:i', strtotime($r["k.time"]));
				echo "<p style='margin:0; padding:0; padding-left:5px; line-height:12px; font-size:12px;'>";
				echo "&nbsp;&nbsp;&nbsp;<strong>Killed by:</strong> ";
				if (!$oz_flushed)
				{
					$killed_by_oz = false;
						foreach($ozs as $oz_id)
					{
						if ($r["k_u.gt_name"] == $oz_id)
						{
							$killed_by_oz = true;
						}
					}
					
					if ($killed_by_oz == true)
					{
						echo "The OZ";
					}
					else
					{
						echo $r["k_u.fname"] . " " . $r["k_u.lname"];
					}
				}
				else
				{
					echo $r["k_u.fname"] . " " . $r["k_u.lname"];
				}
				echo "<br>&nbsp;&nbsp;&nbsp;<strong>On</strong> $time<br>";
				echo "</p>";
				echo "</div>\n";
	}
}



function print_killboard($faction, $sort_array, $sort_by){
	global $oz_flushed, $current_game, $gt_name, $db;
	$oz_flushed = is_oz_flushed($current_game);

	if($faction == "HUMAN"){
		$name_sort = $sort_array['lname'];
		if($oz_flushed)
			$query = "SELECT * FROM users WHERE (faction='HUMAN' AND fname != 'Feed' AND lname != 'Feed') AND rules_quiz = 1 ORDER BY lname $name_sort, fname $name_sort, id ASC";
		else
			$query = "SELECT * FROM users WHERE ((faction='HUMAN' OR oz=1) AND fname != 'Feed' AND lname != 'Feed') AND rules_quiz = 1 ORDER BY lname $name_sort, fname  $name_sort, id ASC";
		$kb_res = $db->query($query) or die($db->error);
		while( $r = $kb_res->fetch_assoc()){
			echo	"<div class='killboard_item'><li>\n";
			
			if ($r["avatar"] != "")
			{
				echo ("\t<img src='../images/avatars/" . $r['avatar'] . "' width='50' />\n");
			}
			else
			{
				echo ("\t<img src='../images/avatars/tiny_human.png' width='50' />\n");
			}
				
			echo ("\t<a class='kill_name' href='../profile/index.php?id=" . $r['id'] . "' ><span class='name'>" . $r['fname'] . " " . $r['lname'] . "</span></a>\n");
			if( $r['gt_name']=='iwiden3' and $gt_name != 'iwiden3')
				echo("\t<p class='skinny_lines'>Quieres los logros? Pues ganalos.</p>\n</li>\n</div>");
			else{
				echo("\t<p class='skinny_lines'>".$r['slogan']."</p></li>\n</div>");
			}
		}	
	}
	else if($faction == "ZOMBIE"){
        $ozs = array();
		$name_sort = $sort_array['lname'];
		$kill_sort = $sort_array['kills'];
		$starve_sort = $sort_array['starve_time'];
		
		if($sort_by == 'lname')
			$sort_string ="v_u.lname $name_sort, v_u.fname $name_sort, v_u.kills $kill_sort, v_u.starve_time $starve_sort ";
		else if($sort_by == 'kills')
			$sort_string ="v_u.kills $kill_sort, v_u.starve_time $starve_sort, v_u.lname $name_sort, v_u.fname $name_sort ";
		else if($sort_by == 'starve_time')
			$sort_string ="v_u.starve_time $starve_sort, v_u.kills $kill_sort, v_u.lname $name_sort, v_u.fname $name_sort ";
		else
			$sort_string ="v_u.kills $kill_sort, v_u.starve_time $starve_sort, v_u.lname $name_sort, v_u.fname $name_sort ";

		if($oz_flushed){
			$query = "SELECT k_u.fname AS kFname, k_u.lname AS kLname, v_u.fname AS vFname, v_u.lname AS vLname, v_u.starve_time AS vTime, v_u.kills AS vKills, v_u.slogan AS vSlogan, k.time AS kTime, v_u.gt_name AS vGTname, k_u.gt_name AS kGTname, v_u.id AS vID, k_u.id AS kID, v_u.avatar AS vAvatar
				FROM kills k
				JOIN users k_u ON k.killer = k_u.gt_name
				JOIN users v_u ON k.victim = v_u.gt_name 
				WHERE v_u.fname != 'Feed' AND v_u.lname != 'Feed' 
				ORDER BY $sort_string";
		} else {
			$query = "SELECT k_u.fname AS kFname, k_u.lname AS kLname, v_u.fname AS vFname, v_u.lname AS vLname, v_u.starve_time AS vTime, v_u.kills AS vKills, v_u.slogan AS vSlogan, k.time AS kTime, v_u.gt_name AS vGTname, k_u.gt_name AS kGTname, v_u.id AS vID, k_u.id AS kID, v_u.avatar AS vAvatar
				FROM kills AS k
				JOIN users AS k_u ON k.killer = k_u.gt_name
				JOIN users AS v_u ON k.victim = v_u.gt_name 
				WHERE v_u.oz = 0 AND v_u.fname != 'Feed' AND v_u.lname != 'Feed' 
				ORDER BY $sort_string";
		}

		$kb_res = $db->query($query) or die($db->error);
		if (!$oz_flushed)
		{
			$index = 0;
			$query = "SELECT gt_name FROM users WHERE oz=1";
			$oz_res = $db->query($query) or die($db->error);
			while ($one_oz = $oz_res->fetch_array(MYSQLI_NUM))
			{
				$ozs[$index] = $one_oz[0];
				$index++;
			}
		}
		
		while( $r = $kb_res->fetch_assoc()){
			echo	"<div class='zombie_killboard_item'><li>\n";
				
			if ($r["vAvatar"] != "")
			{
				echo ("\t<img src='../images/avatars/" . $r['vAvatar'] . "' width='50' />\n");
			}
			else
			{
				echo ("\t<img src='../images/avatars/tiny_zombie.png' width='50' />\n");
			}
					
			echo	"\t<a class='kill_name' href='../profile/index.php?id=" . $r["vID"] . "' ><span class='name'>".$r["vFname"]." ".$r["vLname"]."</span></a>\n";
					
						$time = date('D H:i', strtotime($r["kTime"]));
						$starve = date('D H:i', strtotime($r["vTime"]));
						echo "\t<h3><strong><span class='kills'>".$r["vKills"]."</span></strong> Kill";
						echo ($r["vKills"]==1) ? "":"s";
						echo "!</h3>\n";
						echo "<p style='margin:0; padding:0; padding-left:5px; line-height:12px; font-size:12px;'>";
						echo "&nbsp;&nbsp;&nbsp;<strong>Killed by:</strong> ";
						if (!$oz_flushed)
						{
							$killed_by_oz = false;
							
							foreach($ozs as $oz_id)
							{
								if ($r["kGTname"] == $oz_id)
								{
									$killed_by_oz = true;
								}
							}
							
							if ($killed_by_oz == true)
							{
								echo "The OZ";
							}
							else
							{
								echo "<a class='killer_name' href='../profile/index.php?id=" . $r["kID"] . "' >" . $r["kFname"] . " " . $r["kLname"] . "</a>";
							}
						}
						else
						{
							echo $r["kFname"] . " " . $r["kLname"]."</span>";
						}
						echo "<br>&nbsp;&nbsp;&nbsp;<strong>On</strong> $time<br>";
						echo "&nbsp;&nbsp;&nbsp;<strong>Starves:</strong> <span class='starve_time'>$starve<br></span>";
						echo "</p>";
						echo "<p>";
						if( $r["vGTname"]=='twrobel3' and $gt_name != 'twrobel3')
							echo "\t<p class='skinny_lines'>I'm why we can't have nice things</p>\n </div>\n";
						else{
							echo "\t<p class='skinny_lines'>".$r["vSlogan"]."</p>\n";
							echo "</div>\n";
						}
		}
	}
}

function print_killform($faction, $error){
	global $gt_name, $db;
	if($faction != "zombie")
		return "";
		
	
	$res = $db->query("SELECT gt_name,fname, lname, kills, starve_time FROM users WHERE (faction='ZOMBIE' AND gt_name <> '$gt_name') ORDER BY starve_time ASC") or die(mysql_error());
	$list = "";
	while( $r = $res->fetch_assoc()){
		$time = date('D H:i', strtotime($r['starve_time']));
		$s = ($r['kills']==1)? '' : 's';
		$list .= "\t\t\t\t<option value='".$r['gt_name']."'>".$r['fname']." ".$r['lname']." - $time  - ".$r['kills']." kill$s</option>\n";	
	}
	$list2 = implode("\n", array_slice(explode("\n",$list),1));
	echo 	"<form id='report_kill_form' name='report_kill_form' action='report_kill.php' method='post' >\n".
			"\t<input type='hidden' name='map_x' value='0' />\n".
			"\t<input type='hidden' name='map_y' value='0' />\n".
			"\t<table>\n";
			if($error){
				echo 	"\t\t<tr>\n".
						"\t\t\t<td align='right' class='error'>Errors:</td>\n".
						"\t\t\t<td align='left' class='error'>$error</td>\n";
			}
			 echo "\t\t<tr>\n".
			"\t\t\t<td align='right'><label for='code'>Player Code:</label></td>\n".
			"\t\t\t<td><input name='code' type='text' value='' /></td>\n".
			"\t\t</tr>\n".
			"\t\t<tr>\n".
			"\t\t<td align='right'><label for='feed1'>Feed #1:</label></td>\n".
			"\t\t\t<td><select name='feed1'>\n$list<option>---</option></select></td>\n".
			"\t\t</tr>\n".
			"\t\t<tr>\n".
			"\t\t\t<td align='right'><label for='feed2'>Feed #2:</label></td>\n".
			"\t\t\t<td><select name='feed2'>\n$list2 <option>---</option></select></td>\n".
			"\t\t</tr>\n".
			"\t\t<tr>\n".
			"\t\t\t<td colspan='2' align='center'>Click the map to give the location of the tag</td>\n".
			"\t\t</tr>\n".
			"\t\t<tr>\n".
			"\t\t\t<td colspan='2' align='center'><input type='submit' value='OM NOM NOM' /></td>\n".
			"\t\t</tr>\n".
			"\t</table>\n".
			"\t<div id='gatech_map' onclick='point_it(event)' style=''>\n".
			"\t\t<div id='map_pin' style=''><img src='../images/crosshairs.png' >\n".
			"\t\t</div>\n".
			"\t</div>\n".
			"</form>\n";
}

function is_oz_flushed($game) {
    global $oz_flush_dates;
	return (( time() > strtotime($oz_flush_dates[$game])) ? true : false);
}

function quiz_open($game) {
    global $quiz_open_dates, $quiz_close_dates;
    return (( time() >= strtotime($quiz_open_dates[$game]) && time() < strtotime($quiz_close_dates[$game])) ? true : false);
}

function print_ach_checks($user) {
	global $gt_name, $ach_db;
	
    $human = "SELECT `adata`.`id` AS `id`, `adata`.`name` AS `name`, `adata`.`category` AS `category`, `agets`.`time` AS `time` FROM `ach_data` AS `adata` LEFT OUTER JOIN (SELECT * FROM `ach_gets` WHERE `user` = '$user') AS `agets` ON `adata`.`id` = `agets`.`ach_id` WHERE `adata`.`category` = 'human' ORDER BY `adata`.`category`, `adata`.`id`";
    
    $zombie = "SELECT `adata`.`id` AS `id`, `adata`.`name` AS `name`, `adata`.`category` AS `category`, `agets`.`time` AS `time` FROM `ach_data` AS `adata` LEFT OUTER JOIN (SELECT * FROM `ach_gets` WHERE `user` = '$user') AS `agets` ON `adata`.`id` = `agets`.`ach_id` WHERE `adata`.`category` = 'ZOMBIE' ORDER BY `adata`.`category`, `adata`.`id`";
    
    $general = "SELECT `adata`.`id` AS `id`, `adata`.`name` AS `name`, `adata`.`category` AS `category`, `agets`.`time` AS `time` FROM `ach_data` AS `adata` LEFT OUTER JOIN (SELECT * FROM `ach_gets` WHERE `user` = '$user') AS `agets` ON `adata`.`id` = `agets`.`ach_id` WHERE `adata`.`category` = 'GENERAL' ORDER BY `adata`.`category`, `adata`.`id`";
    
    $result = $ach_db->query($human);
	echo ("<div id='humanach' class='hide'><ul>");
	while ($row = $result->fetch_object())
	{
        echo ("<li><label for='$row->id'>$row->name </label><input type='checkbox' id='$row->id'");
		if (!is_null($row->time))
			echo (" checked='checked'");
		echo (" onclick='alter_ach($row->id, \"$user\")'></li>\n");
	}
	
	echo ("</ul></div><div id='zombieach' class='hide'><ul>");
	
	$result = $ach_db->query($zombie);
	while ($row = $result->fetch_object())
	{
		echo ("<li><label for='$row->id'>$row->name </label><input type='checkbox' id='$row->id'");
		if (!is_null($row->time))
			echo (" checked='checked'");
		echo (" onclick='alter_ach($row->id, \"$user\")'></li>\n");
	}
	
	echo ("</ul></div><div id='generalach' class='show'><ul>");
	
	$result = $ach_db->query($general);
	
	while ($row = $result->fetch_object())
	{
		echo ("<li><label for='$row->id'>$row->name </label><input type='checkbox' id='$row->id'");
		if (!is_null($row->time))
			echo (" checked='checked'");
		echo (" onclick='alter_ach($row->id, \"$user\")'></li>\n");
	}
	echo ("</ul></div>");    
}

function isOZ($name)
{
	global $db;

    $query = "SELECT `oz` FROM `users` WHERE gt_name = '$name'";

	$result = $db->query($query);

	$r = $result->fetch_assoc();

	if ($r['oz'] == 1)
	{
		return true;
	}
	else
	{
		return false;
	}
}
?>