<?php
	session_start();
	$local=false;
    $current_game = "spring2013";
    $db_users = array(
    	"achievements" => "achieve",
        "spring2012" => "Spr12",
        "fall2012" => "Fall12",
        "spring2013" => "Spr13"
    );
    $db_pass = array(
    	"achievements" => "legopibb",
        "spring2012" => "pandapool",
        "fall2012" => "legopibb",
        "spring2013" => "sextowel"
    );

    if($local){
        $gt_name='jmartin34';
        $db = mysql_connect('127.0.0.1','root') or die("DBC Local Fail");
    }

    $gt_name = $_ENV["REMOTE_USER"];
    $_SESSION["gtname"] = $gt_name;

    $db = mysql_connect("web-db1.gatech.edu:3306",$db_users[$current_game],$db_pass[$current_game]) or die("DBC Remote Fail");


    $ach_db = new mysqli("web-db1.gatech.edu",$db_users["achievements"],$db_pass["achievements"],"achievements",3306);
    if (mysqli_connect_errno()) {
        die('Connect Error (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());
    }


    mysql_select_db($current_game);
	
	$oz_flush_dates = array(
		"spring2012" => "27 February 2012 23:00",
		"fall2012" => "17 September 2012 23:00",
		"spring2013" => "4 March 2013 23:00"
		);
	$oz_flushed = false;

    $quiz_open_dates = array(
        "spring2012" => "28 February 2012",
        "fall2012" => "10 September 2012",
        "spring2013" => "25 February 2013"
    );

    $quiz_close_dates = array(
        "spring2012" => "28 February 2012 17:00",
        "fall2012" => "17 September 2012 17:00",
        "spring2013" => "4 March 2013 17:00"
    );

	if(!isset($_SESSION['sorted'])){
		$_SESSION['Hsort']=array('lname'=>'ASC','fname'=>'ASC');
		$_SESSION['Zsort']=array('lname'=>'ASC','fname'=>'ASC','kills'=>'DESC', 'starve_time'=>'ASC');
		$_SESSION['sorted']=1;
	}


function verify($gt_name){
	global $local;
	//mysql_select_db("hvz",$db);
	//$gt_name="bananapants";
	$res = mysql_query("SELECT * FROM `users` where `gt_name`='$gt_name'") or die("Verify Query Fail");
	
	if(mysql_num_rows($res) == 0)
		$ins = mysql_query("INSERT INTO `users` (gt_name) VALUES ('$gt_name')") or die("Addition Query Fail");
	
	$r = mysql_fetch_assoc($res);

	if($r["signed_up"]){
		if($r['faction'])
		{
			$_SESSION["faction"] = $r["faction"];
			return strtolower($r['faction']);
		}
	}
	
	if($local){
		header( "Location: http://localhost/profile/signup.php" );
		die();}
	else{
		header( "Location: http://hvz.gatech.edu/profile/signup.php" );
		die();}
}



function first_time($gt_name){
	global $db;

	$res= mysql_query("SELECT signed_up FROM `users` where `gt_name`='$gt_name'", $db) or die("Query Fail");
	$r = mysql_fetch_array($res);
	return $r['signed_up']=='0' ? True : False;
}

function taken_quiz($gt_name) {
	global $db;
	
	$res= mysql_query("SELECT rules_quiz FROM `users` where `gt_name`='$gt_name'", $db) or die("Query Fail");
	$r = mysql_fetch_array($res);
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
	
	if ($count != "")
		$res = mysql_query("SELECT * FROM (SELECT twits.id AS tid, users.id AS uid, fname, lname, oz, timestamp, faction, comment FROM `twits` JOIN (`users`) ON (twits.user=users.gt_name) WHERE audience='$chat_faction' ORDER BY twits.id DESC $count) AS `chats` ORDER BY tid");
	else
		$res = mysql_query("SELECT twits.id AS tid, users.id AS uid, fname, lname, oz, timestamp, faction, comment FROM `twits` JOIN (`users`) ON (twits.user=users.gt_name) WHERE audience='$chat_faction' ORDER BY twits.id");
	
	while( $r = mysql_fetch_array($res)){
		$id=$r['tid'];
		$uid=$r['uid'];
		$time = date('D H:i', strtotime($r['timestamp']));
		$fname=$r['fname'];
		$lname=$r['lname'];
		$comment=$r['comment'];
		$faction = ($r['oz'] == 1) ? "HUMAN" : $r['faction'];
        $faction_line = strtolower($faction) . "_line";

		echo "<tr class='chat_line $faction_line'>\n";

        if($show_del)
            echo "<td><input type='button' value='X' style='float:left; color:red; border:none; font-size:10px; line-height:10px;' onclick='remove_chat($id, \"$chat_faction\")'></td>";

        echo "<td><a href='../profile/view_profile.php?id=$uid'>$fname $lname</a></td><td>$time</td><td><div class='comments'>$comment</div></td>";

        echo "</tr>\n";
	}
}

function print_chat($user_faction,$chat_faction, $count){

    $res = mysql_query("SELECT twits.id, fname, lname, oz, timestamp, faction, comment FROM `twits` JOIN (`users`) ON (twits.user=users.gt_name) WHERE audience='$chat_faction' ORDER BY twits.id DESC $count");

    while( $r = mysql_fetch_array($res)){
        $id=$r['id'];
        $time = date('D H:i', strtotime($r['timestamp']));
        $fname=$r['fname'];
        $lname=$r['lname'];
        $comment=$r['comment'];
        $fact = ($r['oz'] == 1) ? "human" : strtolower($r['faction']);
        echo "<div class='chat_item'>\n".
            "\t<img src='../images/avatars/tiny_$fact.png' width='50px' />\n".
            "\t<h1>$fname $lname</h1> <h2>$time</h2>\n";
        if($user_faction=="admin")
            echo "<input type='button' value='X' style='float:right; color:red; border:none;' onclick='remove_chat($id)'>";

        echo "\t<p>$comment</p>\n".
            "</div>\n";
    }
}

function print_missions($faction){
	
	$res = mysql_query("SELECT * FROM `missions` WHERE (`faction`='$faction' OR `faction`='ALL' )AND `release_datetime` < NOW() ORDER BY `start_datetime` DESC") or die("Query Fail");
	while($r = mysql_fetch_array($res)){
		
		$start = date('D H:i', strtotime($r['start_datetime']));
		$end =   date('D H:i', strtotime($r['end_datetime']));
		
		//echo $start." ".$end;
		echo	"\n<div id='mission".$r['id']."' class='mission'>\n".
					"\t<h3>".$r['name']."</h3>\n".
					"\t\t<p class='mission_particulars'>\n".
						"\t\t\t<strong>Start:</strong> $start <br>\n".
						"\t\t\t<strong>End</strong>: $end <br>\n".
						"\t\t\t<strong>Location:</strong> ".$r['location']."<br>\n".
					"\t\t</p>\n".
					"\t\t<p class='mission_description'>".$r['description']."</p>\n".
			 	"\t</div>\n";
		
	}

	
}

function print_quiz(){
	$rules_res = mysql_query("SELECT * FROM `rules_quiz_questions`");
	$i=0;
	while( $qr = mysql_fetch_array($rules_res) ){
		$ans_res   = mysql_query("SELECT * FROM `rules_quiz_answers` WHERE `question_id` ='".$qr['id']."'");
		echo "\n<p class='question";
		if (isset($_SESSION["wrong"]) && in_array($qr['id'], $_SESSION["wrong"]))
			echo " wrong";
		echo "'>".++$i.".  ".$qr["question"]."</p>";

			while( $ar = mysql_fetch_array($ans_res) ){
				echo "\n\t\t<input type='radio' name='q".$qr["id"]."' value='".$ar['id']."'>  ".$ar['answer']."</input><br />";

			}

	}
}

function print_deathbar() {
	global $oz_flushed, $current_game, $gt_name;
	$oz_flushed = is_oz_flushed($current_game);
	
	if($oz_flushed){
		$query = "SELECT k_u.fname, k_u.lname, v_u.fname, v_u.lname, v_u.starve_time, v_u.kills, v_u.slogan, k.time, v_u.gt_name, k_u.gt_name
			FROM kills k
			JOIN users k_u ON k.killer = k_u.gt_name
			JOIN users v_u ON k.victim = v_u.gt_name 
			ORDER BY k.time DESC
			LIMIT 5";
	} else {
		$query = "SELECT k_u.fname, k_u.lname, v_u.fname, v_u.lname, v_u.starve_time, v_u.kills, v_u.slogan, k.time, v_u.gt_name, k_u.gt_name
			FROM kills AS k
			JOIN users AS k_u ON k.killer = k_u.gt_name
			JOIN users AS v_u ON k.victim = v_u.gt_name 
			WHERE v_u.oz = 0
			ORDER BY k.time DESC
			LIMIT 5";
	}
	
	$db_res = mysql_query($query) or die(mysql_error());
	
	if (!$oz_flushed)
	{
		$index = 0;
		$query = "SELECT gt_name FROM users WHERE oz=1";
		$oz_res = mysql_query($query) or die(mysql_error());
		while ($one_oz = mysql_fetch_array($oz_res))
		{
			$ozs[$index] = $one_oz[0];
			$index++;
		}
	}
	
	echo ('<h2 style="padding-left:5px;">Fresh Kills</h2>');
	
	while( $r = mysql_fetch_array($db_res) ){
		/* MySQL or PHP fails.  If doesn't allow you to rename the keys in the associative array.
		 *   actually, i realized i just need to say k_u.fname kfname, etc. but this is low on the prio list
		 *  So as a key:
		 * 0 = killer fname
		 * 1 = killer lname
		 * 2 = victim fname
		 * 3 = victim lname
		 * 4 = victim starve time
		 * 5 = victim kills
		 * 6 = victim slogan
		 * 7 = victim time of zombification
		 * 8 = victim gt_name
		 * 9 = killer gt_name
		 */
		echo	"<div class='deathbar_item'>\n".
				"\t<h1>".$r[2]." ".$r[3]."</h1>\n";
				
				$time = date('D H:i', strtotime($r[7]));
				echo "<p style='margin:0; padding:0; padding-left:5px; line-height:12px; font-size:12px;'>";
				echo "&nbsp;&nbsp;&nbsp;<strong>Killed by:</strong> ";
				if (!$oz_flushed)
				{
					$killed_by_oz = false;
						foreach($ozs as $oz_id)
					{
						if ($r[9] == $oz_id)
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
						echo $r[0] . " " . $r[1];
					}
				}
				else
				{
					echo $r[0] . " " . $r[1];
				}
				echo "<br>&nbsp;&nbsp;&nbsp;<strong>On</strong> $time<br>";
				echo "</p>";
				echo "</div>\n";
	}
}



function print_killboard($faction, $sort_array, $sort_by){
	global $oz_flushed, $current_game, $gt_name;
	$oz_flushed = true/*is_oz_flushed($current_game)*/;
	if($faction == "HUMAN"){
		$name_sort = $sort_array['lname'];
		if($oz_flushed)
			$query = "SELECT * FROM users WHERE (faction='HUMAN' AND oz=0 AND fname != 'Feed' AND lname != 'Feed') AND rules_quiz = 1 ORDER BY lname $name_sort, fname $name_sort, id ASC";
		else
			$query = "SELECT * FROM users WHERE (faction='HUMAN' OR oz=1) AND rules_quiz = 1 ORDER BY lname $name_sort, fname  $name_sort, id ASC";
		$kb_res = mysql_query($query) or die(mysql_error());
		while( $r = mysql_fetch_array($kb_res) ){
			echo	"<div class='killboard_item'>\n";
				
			if ($r["avatar"] != "")
			{
				echo ("\t<img src='../images/avatars/" . $r['avatar'] . "' width='50' />\n");
			}
			else
			{
				echo ("\t<img src='../images/avatars/tiny_human.png' width='50' />\n");
			}
				
			echo    "\t<a class='kill_name' href='../profile/view_profile.php?id=" . $r['id'] . "' >".$r['fname']." ".$r['lname']."</a>\n";
			if( $r['gt_name']=='twrobel3' and $gt_name != 'twrobel3')
				echo "\t<p class='skinny_lines'>I'm why we can't have nice things</p>\n </div>\n";
			else{
				echo "\t<p class='skinny_lines'>".$r['slogan']."</p>\n".
					  "</div>\n";
			}
		}	
	}
	else if($faction == "ZOMBIE"){
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
			//$sort_string ="v_u.lname $name_sort, v_u.fname $name_sort, v_u.kills $kill_sort, v_u.starve_time $starve_sort ";

		if($oz_flushed){
			$query = "SELECT k_u.fname, k_u.lname, v_u.fname, v_u.lname, v_u.starve_time, v_u.kills, v_u.slogan, k.time, v_u.gt_name, k_u.gt_name, v_u.id, k_u.id, v_u.avatar
				FROM kills k
				JOIN users k_u ON k.killer = k_u.gt_name
				JOIN users v_u ON k.victim = v_u.gt_name 
				WHERE v_u.fname != 'Feed' AND v_u.lname != 'Feed' 
				ORDER BY $sort_string";
		} else {
			$query = "SELECT k_u.fname, k_u.lname, v_u.fname, v_u.lname, v_u.starve_time, v_u.kills, v_u.slogan, k.time, v_u.gt_name, k_u.gt_name, v_u.id, k_u.id, v_u.avatar
				FROM kills AS k
				JOIN users AS k_u ON k.killer = k_u.gt_name
				JOIN users AS v_u ON k.victim = v_u.gt_name 
				WHERE v_u.oz = 0 AND v_u.fname != 'Feed' AND v_u.lname != 'Feed' 
				ORDER BY $sort_string";
		}
		
		

		$kb_res = mysql_query($query) or die(mysql_error());
		if (!$oz_flushed)
		{
			$index = 0;
			$query = "SELECT gt_name FROM users WHERE oz=1";
			$oz_res = mysql_query($query) or die(mysql_error());
			while ($one_oz = mysql_fetch_array($oz_res))
			{
				$ozs[$index] = $one_oz[0];
				$index++;
			}
		}
		
		while( $r = mysql_fetch_array($kb_res) ){
			/* MySQL or PHP fails.  If doesn't allow you to rename the keys in the associative array.
			 *   actually, i realized i just need to say k_u.fname kfname, etc. but this is low on the prio list
			 *  So as a key:
			 * 0 = killer fname
			 * 1 = killer lname
			 * 2 = victim fname
			 * 3 = victim lname
			 * 4 = victim starve time
			 * 5 = victim kills
			 * 6 = victim slogan
			 * 7 = victim time of zombification
			 * 8 = victim gt_name
			 * 9 = killer gt_name
			 * 10 = victim id
			 * 11 = killer id
			 * 12 = victim avatar
			 */
			echo	"<div class='zombie_killboard_item'>\n";
				
			if ($r[12] != "")
			{
				echo ("\t<img src='../images/avatars/" . $r['avatar'] . "' width='50' />\n");
			}
			else
			{
				echo ("\t<img src='../images/avatars/tiny_zombie.png' width='50' />\n");
			}
					
			echo	"\t<a class='kill_name' href='../profile/view_profile.php?id=" . $r[10] . "' >".$r[2]." ".$r[3]."</a>\n";
					
						$time = date('D H:i', strtotime($r[7]));
						$starve = date('D H:i', strtotime($r[4]));
						echo "\t<h3><strong>".$r[5]."</strong> Kill";
						echo ($r[5]==1) ? "":"s";
						echo "!</h3>\n";
						echo "<p style='margin:0; padding:0; padding-left:5px; line-height:12px; font-size:12px;'>";
						echo "&nbsp;&nbsp;&nbsp;<strong>Killed by:</strong> ";
						if (!$oz_flushed)
						{
							$killed_by_oz = false;
							
							foreach($ozs as $oz_id)
							{
								if ($r[9] == $oz_id)
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
								echo "<a class='killer_name' href='../profile/view_profile.php?id=" . $r[11] . "' >" . $r[0] . " " . $r[1] . "</a>";
							}
						}
						else
						{
							echo $r[0] . " " . $r[1];
						}
						echo "<br>&nbsp;&nbsp;&nbsp;<strong>On</strong> $time<br>";
						echo "&nbsp;&nbsp;&nbsp;<strong>Starves:</strong> $starve<br>";
						echo "</p>";
						echo "<p>";
						if( $r[8]=='twrobel3' and $gt_name != 'twrobel3')
							echo "\t<p class='skinny_lines'>I'm why we can't have nice things</p>\n </div>\n";
						else{
							echo "\t<p class='skinny_lines'>".$r[6]."</p>\n";
							echo "</div>\n";
						}
		}
	}
}

function print_killform($faction, $error){
	global $gt_name;
	if($faction != "zombie")
		return "";
		
	
	$res = mysql_query("SELECT gt_name,fname, lname, kills, starve_time FROM users WHERE (faction='ZOMBIE' AND gt_name <> '$gt_name') ORDER BY starve_time ASC") or die(mysql_error());
	$list = "";
	while( $r = mysql_fetch_array($res) ){
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
	return /*(( time() > strtotime($oz_flush_dates[$game])) ? true : false)*/true;
}

function quiz_open($game) {
    global $quiz_open_dates, $quiz_close_dates;
    return (( time() >= strtotime($quiz_open_dates[$game]) && time() < strtotime($quiz_close_dates[$game])) ? true : false);
}

function point_controller($point) {
    $res = mysql_query("SELECT owner FROM capture WHERE id=$point") or die(mysql_error());
    $r = mysql_fetch_array($res);
    return $r[0];
}

function print_ach_checks($user) {
	global $gt_name, $ach_db;
	
    $human = "SELECT `adata`.`id` AS `id`, `adata`.`name` AS `name`, `adata`.`category` AS `category`, `agets`.`time` AS `time` FROM `ach_data` AS `adata` LEFT OUTER JOIN (SELECT * FROM `ach_gets` WHERE `user` = '$user') AS `agets` ON `adata`.`id` = `agets`.`ach_id` WHERE `adata`.`category` = 'human' ORDER BY `adata`.`category`, `adata`.`id`";
    
    $zombie = "SELECT `adata`.`id` AS `id`, `adata`.`name` AS `name`, `adata`.`category` AS `category`, `agets`.`time` AS `time` FROM `ach_data` AS `adata` LEFT OUTER JOIN (SELECT * FROM `ach_gets` WHERE `user` = '$user') AS `agets` ON `adata`.`id` = `agets`.`ach_id` WHERE `adata`.`category` = 'ZOMBIE' ORDER BY `adata`.`category`, `adata`.`id`";
    
    $general = "SELECT `adata`.`id` AS `id`, `adata`.`name` AS `name`, `adata`.`category` AS `category`, `agets`.`time` AS `time` FROM `ach_data` AS `adata` LEFT OUTER JOIN (SELECT * FROM `ach_gets` WHERE `user` = '$user') AS `agets` ON `adata`.`id` = `agets`.`ach_id` WHERE `adata`.`category` = 'GENERAL' ORDER BY `adata`.`category`, `adata`.`id`";
    
    $result = mysqli_query($ach_db, $human);
	echo ("<div id='humanach' class='hide'><ul>");
	while ($row = $result->fetch_object())
	{
        echo ("<li><label for='$row->id'>$row->name </label><input type='checkbox' id='$row->id'");
		if (!is_null($row->time))
			echo (" checked='checked'");
		echo (" onclick='alter_ach($row->id, \"$user\")'></li>\n");
	}
	
	echo ("</ul></div><div id='zombieach' class='hide'><ul>");
	
	$result = mysqli_query($ach_db, $zombie);
	while ($row = $result->fetch_object())
	{
		echo ("<li><label for='$row->id'>$row->name </label><input type='checkbox' id='$row->id'");
		if (!is_null($row->time))
			echo (" checked='checked'");
		echo (" onclick='alter_ach($row->id, \"$user\")'></li>\n");
	}
	
	echo ("</ul></div><div id='generalach' class='show'><ul>");
	
	$result = mysqli_query($ach_db, $general);
	
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
	//if (is_oz_flushed($current_game))
	{
		//return false;
	}

	$query = "SELECT `oz` FROM `users` WHERE gt_name = '$name'";

	$result = mysql_query($query);

	$r = mysql_fetch_array($result);

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