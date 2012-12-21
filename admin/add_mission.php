<?php
	require("../scripts/lib.php"); 

	$faction = verify($gt_name);
	
	if($faction != 'admin'){
		header( "Location: http://hvz.gatech.edu/faction/$faction.php" );
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Humans vs Zombies - Georgia Tech</title>
<link type="text/css" rel="stylesheet" href="../css/base.css">
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
				<h2>Add Mission</h2>
				<form action='add_mission_engine.php' method='post'>
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
							<td>Faction:</td><td> 
								<select name='faction'>
									<option value='ADMIN'>---</option>
									<option value='HUMAN'>Human</option>
									<option value='ZOMBIE'>Zombie</option>
									<option value='ALL'>All</option>
								</select>
							</td>
						</tr><tr>
							<td>Description:</td><td> <textarea name='description' cols='69' rows='10' ></textarea></td>
			            </tr>
					</table>
					<input type='submit' name='add_mission' value='Add Mission!'/>
				</form>
		    </div>
		</div>
	</div>
</body>