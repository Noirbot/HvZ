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
        function cp_faction_change(point, faction){
            var request = new XMLHttpRequest();
            request.open("GET", "toggle_cp.php?owner="+faction+"&point="+point, false);
            request.send(null);
            var point_id = "p" + point;
            document.getElementById(point_id).innerHTML = "Point " + point + " - Status: " + faction;
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
        <h2 class="center" id="p1">Point 1 - Status: <?php echo(point_controller(1)); ?></h2><br/>
        <input type="button" value="Zombie Control" onclick="cp_faction_change(1, 'ZOMBIE')">
        <input type="button" value="Neutral" onclick="cp_faction_change(1, 'NEUTRAL')">
        <input type="button" value="Human Control" onclick="cp_faction_change(1, 'HUMAN')">
    </div>
</div>
</body>
</html>