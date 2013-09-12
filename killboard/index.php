<?php 
	require("../scripts/lib.php"); 
	$faction = verify($gt_name);
	
	$quizzed = taken_quiz($gt_name);
	if ($quizzed == False)
	{
		header("Location: http://hvz.gatech.edu/faction/inactive.php");
	}
	
	$sort_by='';
	if(isset($_POST['HSort']) ){
		$sort_by = $_POST['HSort'];
		if($sort_by == 'lname'){
			$_SESSION['Hsort']['lname'] = (($_SESSION['Hsort']['lname'] == 'ASC' ) ? 'DESC' : 'ASC');
			$_SESSION['Hsort']['fname'] = ($_SESSION['Hsort']['fname'] == 'ASC' ) ? 'DESC' : 'ASC';
		}
		unset($_POST['HSort']);
	}
	if(isset($_POST['ZSort']) ){
		$sort_by = $_POST['ZSort'];
		if($sort_by == 'lname'){
			$_SESSION['Zsort']['lname'] = ($_SESSION['Zsort']['lname'] == 'ASC' ) ? 'DESC' : 'ASC';
			$_SESSION['Zsort']['fname'] = ($_SESSION['Zsort']['fname'] == 'ASC' ) ? 'DESC' : 'ASC';
		}
		else if($sort_by == 'kills')
			$_SESSION['Zsort']['kills'] = ($_SESSION['Zsort']['kills'] == 'ASC' ) ? 'DESC' : 'ASC';
		else if($sort_by == 'starve_time')
			$_SESSION['Zsort']['starve_time'] = ($_SESSION['Zsort']['starve_time'] == 'ASC' ) ? 'DESC' : 'ASC';
		unset($_POST['ZSort']);
	}

						  
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Humans vs Zombies - Georgia Tech</title>
<link type="text/css" rel="stylesheet" href="/css/base.css">
<script src="../scripts/list.min.js"></script>

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
	
	  <div id="killboard">
	 	<h2>Killboard</h2>
		
		<form name='sort_form' action="index.php" method="post">
		<div id="human_list">
			<div style="float:right"> 
				Sort by: 
					<!--<input type="hidden" name="HSort" value="" />
					<input type="button" value="Name"  onclick="document.sort_form.HSort.value='lname'; document.sort_form.submit()" />-->
					<input type="button" value="Name" class="sort" data-sort="name" />
			</div><br>
			<?php
				$res = $db->query("SELECT * FROM users WHERE (faction='HUMAN' AND rules_quiz = 1 AND fname != 'Feed' AND lname != 'Feed') OR (oz = 1)");
				$r = $res->num_rows;
			?>
			<h2>Humans (<?php echo $r ?>)</h2>
				<ul class="list">
					<?php print_killboard("HUMAN", $_SESSION['Hsort'], "lname"); ?>
				</ul>

		</div>
		<script type="text/javascript">
					var options = {valueNames:['name'], page: 1000};
					var humanSort = new List('human_list',options);
				</script>
		 <div id="zombie_list">
		 	<div style="float:right">
				Sort by: 
					<!--<input type="hidden" name="ZSort" value="" />
					<input type="button" value="Name"  onclick="document.sort_form.ZSort.value='lname'; document.sort_form.submit()" />
					<input type="button" value="Kills" onclick="document.sort_form.ZSort.value='kills'; document.sort_form.submit()" /> 
					<input type="button" value="Hungriest" onclick="document.sort_form.ZSort.value='starve_time'; document.sort_form.submit()"/>-->
					<input type="button" value="Name" class="sort" data-sort="name" />
					<input type="button" value="Kills" class="sort" data-sort="kills" />
					<input type="button" value="Hungriest" class="sort" data-sort="starve_time" />
			</div><br>

			<?php
				$res = $db->query("SELECT * FROM users WHERE faction='ZOMBIE'AND fname != 'Feed' AND lname != 'Feed' AND oz = 0");
				$oz = $db->query("SELECT * FROM users WHERE oz = 1");
				if ($oz->num_rows != 0) {
					$r = $res->num_rows + 1;
				} else {
					$r = $res->num_rows;
				}
			?>
			<h2>Zombies (<?php echo $r ?>)</h2>
				<ul class="list">
					<?php print_killboard("ZOMBIE", $_SESSION['Zsort'], $sort_by); ?>
				</ul>
				
		</div>
		<script type="text/javascript">
					var options = {valueNames:['name','kills','starve_time'], page: 1000};
					var zombieSort = new List('zombie_list', options);
				</script>
		</form>
     <div class="footer"><p></p></div>
      
   </div>

</div>
</body>
</html>