<?php 
	require("../scripts/lib.php"); 

	$faction = verify($gt_name);
	
	if($faction != 'admin'){
		header( "Location: http://hvz.gatech.edu/faction/$faction.php" );
	}

	if( isset($_POST['add_mission']) ){
		$name = (isset($_POST['name']) and $_POST['name']) ? mysql_real_escape_string($_POST['name']) : 'error';
		$start_datetime = ($_POST['start_datetime'] == 'YYYY-MM-DD 23:59:59') ? 'error' : mysql_real_escape_string($_POST['start_datetime']);
		$end_datetime = ($_POST['end_datetime'] == 'YYYY-MM-DD 23:59:59') ? 'error' : mysql_real_escape_string($_POST['end_datetime']);
		$release_datetime = ($_POST['release_datetime'] == 'YYYY-MM-DD 23:59:59') ? 'error' : mysql_real_escape_string($_POST['release_datetime']);
		$location = (isset($_POST['location']) and $_POST['location']) ? mysql_real_escape_string($_POST['location']) : 'error';
		$faction = mysql_real_escape_string($_POST['faction']);
		$description = (isset($_POST['description']) and $_POST['description']) ? mysql_real_escape_string($_POST['description']) : 'error';
	
		if( $name == 'error' or $start_datetime == 'error' or $end_datetime == 'error' or $release_datetime == 'error' or $location == 'error' or $faction == 'error' or $description == 'error')
			$error = "Oh noes! You filled something out wrong!";
		else{
			mysql_query("INSERT INTO missions (name, start_datetime, end_datetime, release_datetime, location, faction, description) VALUES ('$name', '$start_datetime', '$end_datetime', '$release_datetime', '$location', '$faction', '$description')") or die(mysql_error());
			//echo "INSERT INTO missions (name, start_datetime, end_datetime, release_datetime, location, faction, description) VALUES ('$name', '$start_datetime', '$end_datetime', '$release_datetime', '$location', '$faction', '$description')";
		}
		($error) ? die($error) : $a=1;
	}

	if( isset($_POST['factions']) ){
		$inactive = isset($_POST['inactive']) ? "faction='INACTIVE'" : "faction='BANANA'";	
		$dead = isset($_POST['dead']) ? "faction='DEAD'" : "faction='BANANA'";	;	
		$banned = isset($_POST['banned'])? "faction='BANNED'" : "faction='BANANA'";	;	
		$admin = isset($_POST['admin']) ? "faction='ADMIN'" : "faction='BANANA'";	;	
		
		$condition = " or $inactive or $dead or $banned or $admin ";
		$unhide_table = 1;
	}
	else{ $condition = "or 0=1"; }
	

	if( isset($_POST['npc_words']) ){
		$words = isset($_POST['npc_words']) ? mysql_real_escape_string($_POST['npc_words']) : 0;
		
		if($words)
			mysql_query("INSERT INTO twits (user, comment, audience) VALUES ('rmcfear3','$words','HUMAN')") or die(mysql_error());
	}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Humans vs Zombies - Georgia Tech</title>
<link type="text/css" rel="stylesheet" href="/css/base.css">

<script type="text/javascript">
	function toggle(e1, e2){
		e1.value = (e1.value == "Show") ? "Hide" : "Show";
		e2.className = (e2.className == "body hide") ? "body show" : "body hide";
	}
	function tog(name){
		e = document.getElementById(name);
		e.className = (e.className == "hide") ? "show" : "hide";
	}
	function toggle_checkbox(checkbox_name, id){
		var request = new XMLHttpRequest();
		request.open("GET", "toggle_checkbox.php?pid="+id+"&checkbox="+checkbox_name, false);
		request.send(null);
	}
	function remove_mission(id){
		var request = new XMLHttpRequest();
		request.open("GET", "delete_mission.php?mid="+id, false);
		request.send(null);
		location.reload(true)
	}
</script>

</head>
<body>
<div id="header">
    <div class="hiddenItem">
        <a href="#start" accesskey="2">Skip to content.</a>
        <a href="#navigation" accesskey="3">Skip to navigation.</a>
    </div>
    <div id="logoWrapper" class="clearfix">
        <h1 id="logo">
            <a href="http://www.gatech.edu" accesskey="1"> </a>
        </h1>
    <div id="siteLogo">
    Humans vs Zombies</div>
    </div>
    <div id="utilityBar"></div>
    <div id="breadcrumb"><form action='../logout.php'><input type='submit' value='Logout' style='float:right; margin-right:250px;'/></form></div>
</div>
<div id="meat">
   
    <div id="support"><a name="navigation"></a>
        <?php printSideBar(); ?>
    </div>

   <div id="content" class="">


	
    	<h2 onclick="tog('players')"><a>Players Table</a></h2>
        <div id='players' class='hide'>
        <form action='admin.php' method='post'>
        	<input type='hidden' name='factions' value='1' />
        	Include: Inactive <input type='checkbox' name='inactive' /> &nbsp;&nbsp;
            		 Dead <input type='checkbox' name='dead' checked='checked' />&nbsp;&nbsp;
                     Banned <input type='checkbox' name='banned'/>&nbsp;&nbsp;
                     Admin <input type='checkbox' name='admin'/>&nbsp;&nbsp;
            <input type="submit" name="query" value="Refresh" />
        </form>
        <form action='admin.php' method='post'>
        <?php $res = mysql_query("SELECT * FROM users WHERE faction='HUMAN' or faction='ZOMBIE' $condition ORDER BY `faction` DESC, `early_mission` DESC, `late_mission` DESC, lname ASC, fname ASC"); ?>
        <table border='1' style="padding:0; margin:0;">
			<tr>
            	<td><b>Faction</b></td><td><b>GT Name</b></td><td><b>Name</b></td><td><b>Code</b></td><td><b>Missions</b></td>
                <td><b>Signed Up</b></td><td><b>Rules Quiz</b></td>
            </tr>
             
			 <?php
			 while($r = mysql_fetch_array($res)){
				 $id=$r['id'];
				 $emission_check = ($r['early_mission']) ? "checked='checked'" : '';
				 $lmission_check = ($r['late_mission']) ? "checked='checked'" : '';
				 $signed_check= ($r['signed_up']) ? "checked='checked'" : '';
				 $rules_check = ($r['rules_quiz']) ? "checked='checked'" : '';
				echo "<tr>";
					echo "<td>".$r['faction']."</td>";
					echo "<td><a href='../profile/edit_player.php?id=$id'>".$r['gt_name']."</a></td>";
					echo "<td>".$r['fname']." ".$r['lname']."</td>";
					echo "<td>".$r['player_code']."</td>";
					echo "<td><input type='checkbox' name='$id|mission' value='early' $emission_check onclick='toggle_checkbox(\"early_mission\",$id)'/>&nbsp;&nbsp; <input type='checkbox' name='$id|mission' value='late' $lmission_check onclick='toggle_checkbox(\"late_mission\",$id)'/> </td>";
					echo "<td><input type='checkbox' name='$id|signed_up' value='signed_up' $signed_check onclick='toggle_checkbox(\"signed_up\",$id)'/></td>";
					echo "<td><input type='checkbox' name='$id|rules_quiz' value='rules_quiz' $rules_check onclick='toggle_checkbox(\"rules_quiz\",$id)'/></td>";
				echo "</tr>";
			 }
			 
			 ?>
        </table>
        
	</form>
        
    </div>

	<h2 onclick="tog('add_mission')"><a>Missions</a></h2>
		<div id='add_mission' class='hide'>
        <div class='mission'>
		<form action='admin.php' method='post'>
		<table>
			<tr>
			<td>Name: </td><td><input type='text' value='' name='name' size='23'/></td>
			</tr><tr>
			<td>Start Time:</td><td> <input type='text' value='YYYY-MM-DD 23:59:59' name='start_datetime' size='23' /></td>
			</tr><tr>
			<td>End Time:</td><td> <input type='text' value='YYYY-MM-DD 23:59:59' name='end_datetime' size='23' onfocus="//this.value=''"/></td>
			</tr><tr>
			<td>Release Time:</td><td> <input type='text' value='YYYY-MM-DD 23:59:59' name='release_datetime' size='23' onfocus="//this.value=''"/></td>
			</tr><tr>
			<td>Location:</td><td> <input type='text' name='location' size='23'></td>
			</tr><tr>
			<td>Faction:</td><td> <select name='faction'>
					<option value='ADMIN'>---</option>
					<option value='HUMAN'>Human</option>
					<option value='ZOMBIE'>Zombie</option>
					<option value='ALL'>All</option>
				</select></td>
			</tr><tr>
			<td>Description:</td><td> <textarea name='description' cols='69' rows='10' ></textarea></td>
            </tr>
		</table>
			<input type='submit' name='add_mission' value='Add Mission!'/>
		</form>
        </div>       
        <?php
			$res = mysql_query("SELECT * FROM `missions` WHERE 1 ORDER BY `start_datetime` DESC") or die("Query Fail");
			while($r = mysql_fetch_array($res)){
				
				$start = date('D H:i', strtotime($r['start_datetime']));
				$end_time =   date('D H:i', strtotime($r['end_datetime']));
				
				echo "<div class='mission'>".
				"<form action='edit_mission.php' method='post'>".
				"<input type='hidden' name='id' value='".$r['id']."' />".
				"<table>".
					"<tr>".
					"<td>Name: </td><td><input type='text' value='".$r['name']."' name='name' size='23'/><input type='button' value='X' style='float:right; color:red; border:none;' onclick='remove_mission(".$r['id'].")'>".
					"</td>".
					"</tr><tr>".
					"<td>Start Time:</td><td> <input type='text' value='".$r['start_datetime']."' name='start_datetime' size='23' /></td>".
					"</tr><tr>".
					"<td>End Time:</td><td> <input type='text' value='".$r['end_datetime']."' name='end_datetime' size='23' /></td>".
					"</tr><tr>".
					"<td>Release Time:</td><td> <input type='text' value='".$r['release_datetime']."' name='release_datetime' size='23' /></td>".
					"</tr><tr>".
					"<td>Location:</td><td> <input type='text' name='location' value='".$r['location']."' size='23'></td>".
					"</tr><tr>".
					"<td>Faction (all caps):</td><td><input type='text' name='faction' value='".$r['faction']."' /></td>".
					"</tr><tr>".
					"<td>Description:</td><td> <textarea name='description' cols='69' rows='10' >".$r['description']."</textarea></td>".
	            "</tr>".
			"</table>
			<input type='submit' value='Edit'>
			</form>
			</div>";
			}
		?>
        
	</div>

	<h2 onclick="tog('email')"><a>Email Players</a></h2>
		<div id='email' class='hide'>
        <div class='mission'>
		<form action='../scripts/email.php' method='post'>
		<table>
			<tr>
			<td>To: </td><td><select name='faction'>
  					<option value='---'>---</option>
					<option value='HUMAN'>Human</option>
					<option value='ZOMBIE'>Zombie</option>
					<option value='BOTH'>Both</option>
                    <option value='EVERYONE'>Everyone Ever</option>
				</select></td>
			</tr><tr>
			<td>Subject:</td><td> <input type='text' value='' name='subject' size='23' /></td>
			</tr><tr>
			<td>Message:</td><td> <textarea name='message' cols='69' rows='10' ></textarea></td>
            </tr>
		</table>
			<input type='submit' name='email' value='E-Mail!'/>
		</form>
        </div>
	</div>

	<h2 onclick="tog('npc')"><a>Talk Like an NPC</a></h2>
	<div id='npc' class='hide'>
    	<div class='mission'> <!--style="border:3px solid #c5a757; width:280px; padding:10px; padding-top:0px;">-->
    	<form action='admin.php' method='post'> <input type='hidden' value='human_NPC' />
    	<h3>McFearson Says:</h3><br />
        <textarea name='npc_words' cols='50' rows='3'>Moo!</textarea><br />
		<input type='submit' value="Say it!" />
        </form>
        </div>
    </div>


	    <p>
        	<ul>
            	<li><a href="zombie.php">Zombie</a> Faction</li>
                <li><a href="human.php">Human</a> Faction</li>
                <li><a href="chat.php">Chat</a></li>
            </ul>
        </p>


      
   </div>

</div>
</body>
</html>