<?php
    require("../scripts/lib.php");
	$faction = verify($gt_name);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Humans vs Zombies - Georgia Tech</title>
<link type="text/css" rel="stylesheet" href="/css/base.css">
<script language="JavaScript">
	function point_it(event)
	{
		pos_x = event.offsetX?(event.offsetX):event.pageX-document.getElementById("gatech_map").offsetLeft;
		pos_y = event.offsetY?(event.offsetY):event.pageY-document.getElementById("gatech_map").offsetTop;
		
		document.getElementById("map_pin").style.left = (pos_x-24)+"px" ;
		document.getElementById("map_pin").style.top = (pos_y-24)+"px" ;
		document.getElementById("map_pin").style.visibility = "visible";
		document.report_kill_form.map_x.value = pos_x;
		document.report_kill_form.map_y.value = pos_y;
		
	}
	function toggle(e1, e2){
		e1.value = (e1.value == "Show") ? "Hide" : "Show";
		e2.className = (e2.className == "hide") ? "show" : "hide";
	}
	
	request = new XMLHttpRequest();
	
	function alter_ach(ach_id, gtname)
	{
		if (document.getElementById(ach_id).checked)
		{
			var url = "../scripts/achmod.php?aid="+ach_id+"&name="+gtname+"&mode=add";
			request.open("GET", url, true);
			request.send(null);
		}
		else
		{
			var url = "../scripts/achmod.php?aid="+ach_id+"&name="+gtname+"&mode=rem";
			request.open("GET", url, true);
			request.send(null);
		}
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
            <div id="profile">
                <?php
                    $res = $db->query("SELECT * FROM `users` WHERE `gt_name`='$gt_name'") or die("User Query Fail");
                    $r = $res->fetch_assoc();
                    $other_gt_name = $r['gt_name'];
                ?>

                <em><h3><?php echo $r["fname"]." ".$r['lname']; ?></h3></em>

                <h4>&nbsp;-&nbsp;<?php echo $r["faction"]; ?></h4>

                <div id="avatar">
                    <?php
                    	if ($r["avatar"] != "")
                    	{
                    		echo ("<img src='../images/avatars/" . $r['avatar'] . "' width='200' />");
                    	}
                    	else
                    	{
                    		echo ("<img src='../images/avatars/" . strtolower($r["faction"]) . '.png\' width="200" />');
                    	}
                    ?>
                </div>

                <p>
                    <form action="edit_profile.php" method="post">
	                    <h4 class="set_label">Set Slogan</h4>
                        <textarea name="slogan"  cols="60" rows="1"><?php if($r['slogan']) echo $r['slogan'];?></textarea>
                        </br>
                        <h4 class="set_label">Set Avatar</h4>
                        <select id="avatarSel" name="avatar">
							<?php
							if ($r["avatar"] == "")
								echo ("<option value='' selected='selected'>Default</option>");
							else
								echo ("<option value=''>Default</option>");
							
							$query = "SELECT `adata`.`id` AS `id`, `adata`.`name` AS `name`, `adata`.`avatar` as avatar FROM `ach_data` AS `adata` RIGHT OUTER JOIN (SELECT * FROM `ach_gets` WHERE `user` = '$gt_name') AS `agets` ON `adata`.`id` = `agets`.`ach_id` ORDER BY `adata`.`category`, `adata`.`id`";
							
							$result = $ach_db->query($query);
														
							while ($row = $result->fetch_object())
							{
								if ($r["avatar"] == "")
									echo ("<option value='$row->avatar'>$row->name</option>");
								else
								{
									if ($r["avatar"] == $row->avatar)
										echo ("<option value='$row->avatar' selected='selected'>$row->name</option>");
									else
										echo ("<option value='$row->avatar'>$row->name</option>");
								}
							}
							?>
						</select>
						<br/>
                        <input type="submit" value="Edit" />
                    </form>
                </p>
                
                

                <hr style="float:left; width:45%"/><br />

                <?php
                    /* HUMAN STUFFF */
                    if($r['faction'] == 'HUMAN'){
	                    echo 	"\t\t<ul>\n".
			                    "\t\t\t<li><strong>Missions Completed:</strong>";
				        if ($r['early_mission'] == 1 or $r['late_mission'] == 1)
				       	{
					       	if ($r['early_mission'] == 1)
					       	{
						       	echo (" Pre-Thursday");
					       	}
					       	if ($r['early_mission'] == 1 and $r['late_mission'] == 1)
					       	{
						       	echo (" and");
					       	}
					       	if ($r['late_mission'] == 1)
					       	{
						       	echo (" Post-Thursday");
					       	}
				       	}
				       	else
				       	{
					       	echo (" None");
				       	}
				        echo    "</li>\n".
			                    "\t\t</ul>";
                        $name = $r['fname']." ".$r['lname'];
                        $long = (strlen($name)>15) ? "class='long_name'" : "";
                ?>
                <h3>Player Card!</h3><br /><br />
                <div id='player_card' style="margin-left:20px;">
                    <div id="tally">Zombie Tally</div>
                    <img src="../images/hvz.png" width="55%" /><br />
                    <h1 <?php echo $long ?>><?php echo $r["fname"]." ".$r['lname']; ?></h1>

                    <ul><font face="monospace" size="4">ID:</font>
                        <?php
                            foreach(str_split($r["player_code"]) as $char){
                                echo "<li>$char</li>\n";
                            }
                        ?>
                    </ul>
                </div>
                <p><a href="print_card.php">Print it!</a> Cut it out! Surrender it upon being tagged!</p>

                <?php }
                else if($r["faction"] == "ZOMBIE"){
                    $id=$r['id'];
                    $kills_res = $db->query("SELECT * FROM kills WHERE killer='$id'");
                    $killer_res = $db->query("SELECT * FROM kills JOIN users ON (kills.killer = users.gt_name) WHERE victim='$other_gt_name'");
                    $kills = $kills_res->num_rows;
                    $k = $killer_res->fetch_assoc();
                    $killer = $k['fname']." ".$k['lname'];

                    $day = date("l", strtotime($k['time']));
                    $starve = date("D H:i",strtotime($r['starve_time']));
                    echo 	"\t\t<ul>\n".
                            "\t\t\t<li><b>Kills:</b> $kills</li>\n".
                            "\t\t\t<li><b>Starve Time:</b> $starve\n";

                    if ($faction == "zombie" or $faction == "admin" or $faction == "dead")
                            echo "\t\t\t<li><b>Killed by:</b> $killer <b>on</b> $day\n";
                    echo "\t\t</ul>\n";

                }
                else if($r["faction"]=="INACTIVE"){
                ?>
                <p>Please take the <a href="../rules/quiz/rules_quiz.php">Rules Quiz</a> to play in the next game</p>
                <?php } ?>


                <?
                if ($faction == 'zombie'){
                ?>
                <p class="clear:both;">&nbsp;</p>
                <p class="clear:both;">&nbsp;</p>
                <hr />
                <h2>Report a Kill
                     <input type="button" value="Hide"  onclick="toggle(this, document.getElementById('report_kill'))">
                </h2>
                <div id="report_kill" class="show">

                    <?php
                    if(isset($_SESSION['error'])){
                        $error = $_SESSION['error'];
                        unset($_SESSION['error']);
                    }
                    else
                        $error = 0;
                    print_killform($faction,$error); ?>

                </div>
                <? } ?>
            </div>
           <?php
           if ($faction == "admin")
           {
                echo <<<ACH
                <h2>Manage Achievements</h2>
                <input type="button" value="Human" id="human" class="selectors" onclick="loadAch('humanach')"/>
                <input type="button" value="Zombie" id="zombie" class="selectors" onclick="loadAch('zombieach')"/>
                <input type="button" value="General" id="general" class="selectors" onclick="loadAch('generalach')"/>
                <div id="ach_control">
ACH;
               print_ach_checks($gt_name);
               echo ("</div>");

           }
            ?>


        <div class="footer"></div>

       </div>

    </div>
</body>
</html>