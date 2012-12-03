<?php
	require("../scripts/lib.php"); 

	$faction = verify($gt_name);
	
	if($faction != 'admin'){
		if($local)
			header( "Location: http://localhost/faction/$faction.php" );
		else
			header( "Location: http://hvz.gatech.edu/faction/$faction.php" );
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
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Humans vs Zombies - Georgia Tech</title>
<link type="text/css" rel="stylesheet" href="../css/base.css">

<script type="text/javascript">
	function toggle_checkbox(checkbox_name, id){
		var request = new XMLHttpRequest();
		request.open("GET", "toggle_checkbox.php?pid="+id+"&checkbox="+checkbox_name, false);
		request.send(null);
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
	    <div id="deathbar">
	    	<?php print_deathbar(); ?>
	    </div>
	    <div id="content" class="">
	    	<h2>Players Table</h2>
	        <div id='players'>
		        <form action='table.php' method='post'>
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
		</div>
	</div>
</body>
</html>