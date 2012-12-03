<?php 
	require("../scripts/lib.php"); 

	$faction = verify($gt_name);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Humans vs Zombies - Georgia Tech</title>
<link type="text/css" rel="stylesheet" href="/css/base.css">


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
	<div id="content">
		<h2 style="margin-left:2.5%">Achievements</h2>
		<hr />
		<?php
			$query = "SELECT * FROM `ach_data` ORDER BY `category`, `id`";
			
			$result = mysqli_query($ach_db, $query);
			
			while ($row = $result->fetch_object())
			{
				echo("<div class='ach_entry center'>");
				if ($row->avatar != NULL)
				{
					echo ("<img class='ach_img' src='../images/avatars/$row->avatar' alt='$row->name' width='50' height='50'/>");
				}
				echo <<<ENTRY
					<h3 class="ach_title">$row->name</h3>
					<p class="ach_desc">$row->description</p>
				</div>
ENTRY;
			}
		?>
		<div class="footer"><p></p></div>
	</div>

</div>
</body>
</html>