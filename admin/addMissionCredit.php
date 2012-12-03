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
			<div class='upload'>
				<?php
					if (isset($_GET["error"]))
						echo "<p class='wrong'><strong>" . urldecode($_GET["error"]) . "</strong></p>";
				?>
				<h2>Upload GTID File</h2>
				<form action='addCreditEngine.php' method='post' enctype="multipart/form-data">
					<label for="file">File:</label>
					<input type="file" name="file" id="file" />
					<br />
					<br/>
					<input type="radio" name="time" value="early"> Early Mission
					<input type="radio" name="time" value="late"> Late Mission
					<br/>
					<br/>
					<input type='submit' name='add_mission' value='Process'/>
				</form>
		    </div>
		</div>
	</div>
</body>