<?php
	require("../scripts/lib.php"); 

	$faction = verify($gt_name);
	
	if($faction != 'admin'){
		if($local)
			header( "Location: http://localhost/faction/$faction.php" );
		else
			header( "Location: http://hvz.gatech.edu/faction/$faction.php" );
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Humans vs Zombies - Georgia Tech</title>
<link type="text/css" rel="stylesheet" href="../css/base.css">
<script type="text/javascript">
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
		    	Humans vs Zombies
			</div>
	    </div>
	    <div id="utilityBar"></div>
	    <div id="breadcrumb">
	    	<form action='../logout.php'><input type='submit' value='Logout' style='float:right; margin-right:250px;'/></form>
	    </div>
	</div>
	<div id="meat">
	    <div id="support"><a name="navigation"></a>
	        <?php printSideBar(); ?>
	    </div>
	    <div id="deathbar">
	    	<?php print_deathbar(); ?>
	    </div>
	    <div id="content" class="">
			<div class='mission'>
				<h2>Missions</h2>
				<?php
				$res = $db->query("SELECT * FROM `missions` WHERE 1 ORDER BY `start_datetime` DESC") or die("Query Fail");
				while($r = $res->fetch_assoc()){
					
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
		</div>
	</div>
</body>