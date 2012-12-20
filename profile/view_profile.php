<?php
	require("../scripts/lib.php");
	$faction = verify($gt_name);
	
	if (is_numeric($_GET['id']))
		$id = $_GET['id'];
	else
		header("Location: http://www.youtube.com/watch?v=oHg5SJYRHA0");
	
	$quizzed = taken_quiz($gt_name);
	if ($quizzed == False)
	{
		if($local)
			header("Location: http://localhost/faction/inactive.php");
		else
			header("Location: http://hvz.gatech.edu/faction/inactive.php");
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Humans vs Zombies - Georgia Tech</title>
<link type="text/css" rel="stylesheet" href="/css/base.css">
<script language="JavaScript">
	function toggle(id){
		document.getElementById(id).className = (document.getElementById(id).className == "hide") ? "show" : "hide";
	}
	
	function loadAch(faction)
	{
		document.getElementById("humanach").className = "hide";
		document.getElementById("zombieach").className = "hide";
		document.getElementById("generalach").className = "hide";
		document.getElementById(faction).className = "show";
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
		<div id="utilityBar"> </div>
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

			<div id="profile">
				<?php
					$res = $db->query("SELECT * FROM `users` WHERE id=$id") or die("User Query Fail");
					$r = $res->fetch_assoc();
					$other_gt_name = $r['gt_name'];
				?>

				<em><h3><?php echo $r["fname"]." ".$r['lname']; ?></h3></em>

				<h4>&nbsp;-&nbsp;
					<?php
						if ($r["oz"] == 0)
							echo $r["faction"];
						else
							echo ("HUMAN");
					?>
				</h4>

				<div id="avatar">
					<?php
						if ($r["avatar"] != "")
						{
							echo ("<img src='../images/avatars/" . $r['avatar'] . "' width='200' />");
						}
						else
						{
							if ($r["oz"] == 0)
								echo ("<img src='../images/avatars/" . strtolower($r["faction"]) . '.png\' width="200" />');
							else
								echo ("<img src='../images/avatars/human.png' width=\"200\" />");
						}
					?>
				</div>
				<br/>
				<h4>Slogan</h4>
				<p id="slogan"><?php echo($r["slogan"]); ?></p>
				<div id="ach_list">
					<h4>Achievements</h4>
					<dl>
						<?php
							$query = "SELECT `adata`.`id` AS `id`, `adata`.`name` AS `name`, `adata`.`category` AS `category`, `adata`.`description` AS `desc`, `agets`.`time` AS `time` FROM `ach_data` AS `adata` RIGHT OUTER JOIN (SELECT * FROM `ach_gets` WHERE `user` = '$other_gt_name') AS `agets` ON `adata`.`id` = `agets`.`ach_id` ORDER BY `adata`.`category`, `adata`.`id`";
							
							$result = $ach_db->query($query);
							
							$empty = TRUE;
							
							while ($row = $result->fetch_object())
							{
								$empty = FALSE;
								echo ("<dt onclick='toggle($row->id)'>$row->name</dt><dd id='$row->id' class='hide'>- $row->desc</dd>");
							}
							
							if ($empty)
							{
								echo("<p>Nothing has been achieved.</p>");
							}
						?>
					</dl>
				</div>
			</div>
			<div class="footer"></div>
		</div>
	</div>
</body>
</html>