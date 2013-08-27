<?php
require("../scripts/lib.php");

$faction = verify($gt_name);

if (isset($_GET['id'])) {
    if (!is_numeric($_GET["id"]))
        header("Location: http://www.youtube.com/watch?v=oHg5SJYRHA0");
    else
        $id = $_GET["id"];
}
else
    $id = $_SESSION['id'];

$quizzed = taken_quiz($gt_name);
if ($quizzed == False)
{
    header("Location: http://hvz.gatech.edu/faction/inactive.php");
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Humans vs Zombies - Georgia Tech</title>
    <link type="text/css" rel="stylesheet" href="/css/base.css">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js" type="text/javascript"></script>
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
        
        function bump(value){
            var e1 = document.getElementById('time');
            var b = new Date();
            if(value > 0){
                var a = Date.parse(e1.value) + value*24*60*60*1000;
                b = new Date(a);
            }
            else{
                var s = new Date().getTime();
                b = new Date(s);
                
            }
            
            var year = b.getFullYear();
            var month = b.getMonth()+1;
            if(month < 10)
                month = "0" + month;
            var date = b.getDate();
            if(date < 10)
                date = "0" + date;
            var hour = b.getHours();
            if(hour <10)
                hour = "0" + hour;
            var minutes= "00";
            var seconds = "00";
            e1.value= year + "-" + month + "-" + date + " " + hour + ":" + minutes + ":" + seconds;
        }
        
        request = new XMLHttpRequest();
        <?php
        if ($faction == "admin")
            echo <<<ADMIN
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
        
        function unzombify(id)
        {
            alert("Raising Dead!");
            
            var url = "../admin/revive.php?id=" + id;
            request.open("GET", url, true);
            request.send(null);
            
            location.reload(true);
        }
ADMIN;
        ?>
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
                $res = $db->query("SELECT * FROM `users` WHERE id=$id") or die("Invalid User ID: $id.");
                $r = $res->fetch_object();
                if (strcmp($r->gt_name, $_SESSION["gtname"]) == 0)
                    $is_owner = TRUE;
                else
                    $is_owner = FALSE;
                ?>
    
                <em><h3><?php echo $r->fname." ".$r->lname; ?></h3></em>
    
                <h4>&nbsp;-&nbsp;<?php echo $r->faction; ?></h4>
    
                <div id="avatar">
                    <?php
                    if ($r->avatar != "")
                    {
                        echo ("<img src='../images/avatars/" . $r->avatar . "' width='200' />");
                    }
                    else
                    {
                        echo ("<img src='../images/avatars/" . strtolower($r->faction) . '.png\' width="200" />');
                    }
                    ?>
                </div>
                <div id="userdata">
                    <?php
                        if ($is_owner || $faction == "admin") {
                            echo <<<EDIT
                                <form method="post" action="_index.php">
                                <input type='hidden' name="id" value='$r->id'>
                                <table>
                                <tr>
                                    <td><label for="sloganSel">Slogan</label></td>
                                    <td class="edit"><textarea id="sloganSel" name="slogan" rows="2" cols="60">$r->slogan</textarea></td>
                                </tr>
                                <tr>
                                    <td><label for="avatarSel">Avatar</label></td>
                                    <td class="edit"><select id="avatarSel" name="avatar">
EDIT;
                        if ($r->avatar == "")
                            echo ("<option value='' selected='selected'>Default</option>");
                        else
                            echo ("<option value=''>Default</option>");
    
                        $query = "SELECT `adata`.`id` AS `id`, `adata`.`name` AS `name`, `adata`.`avatar` as avatar FROM `ach_data` AS `adata` RIGHT OUTER JOIN (SELECT * FROM `ach_gets` WHERE `user` = '$r->gt_name') AS `agets` ON `adata`.`id` = `agets`.`ach_id` ORDER BY `adata`.`category`, `adata`.`id`";
    
                        $result = $ach_db->query($query);
    
                        while ($row = $result->fetch_object())
                        {
                            if ($r->avatar == "")
                                echo ("<option value='$row->avatar'>$row->name</option>");
                            else
                            {
                                if ($r->avatar == $row->avatar)
                                    echo ("<option value='$row->avatar' selected='selected'>$row->name</option>");
                                else
                                    echo ("<option value='$row->avatar'>$row->name</option>");
                            }
                        }
    
                        echo <<<EDIT
                                </select>
                            </td>
                        </tr>
EDIT;
                        if ($faction == "admin")
                        {
                            echo <<<EDIT
                            <tr>
                                <td>
                                    <label for="factionSel">Faction</label>
                                </td>
                                <td class="edit">
                                    <select id="factionSel" name="faction">
                                        <option value='---'>---</option>
                                        <option value='HUMAN'>Human</option>
                                        <option value='ZOMBIE'>Zombie</option>
                                        <option value='ADMIN'>Admin</option>
                                        <option value='INACTIVE'>Inactive</option>
                                        <option value='BANNED'>Banned</option>
                                        <option value='DEAD'>Dead</option>
                                     </select>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="signedSel">Signed Up</label>
                                </td>
                                <td class="edit">
                                    <input type="checkbox" name="signed_up" id="signedSel"                                   
EDIT;
                            echo ($r->signed_up) ? "checked='checked'":"";
                            
                            echo <<<EDIT
                                />
                                </td>
                            </tr>  
                            <tr>
                                <td><label for="rulesSel">Rules Quiz</label></td>
                                <td class="edit"><input id="rulesSel" name='rules_quiz' type='checkbox' 
EDIT;
                            echo ($r->rules_quiz) ? "checked='checked'":"";
                            
                            echo <<<EDIT
                                    />
                                </td>
                            </tr>
EDIT;
                            if ($r->faction == "ZOMBIE") {
                                echo <<<EDIT
                                <tr>
                                    <td>Starve Time</td>
                                    <td class="edit"><input id='time' name='starve_time' type='text' value='$r->starve_time' /> <input type='button' value='now' onclick='bump(0)' /> <input type='button' value='+24 hrs' onclick="bump(1)"/> <input type='button' value='+48 hrs' onclick="bump(2)"/> <input type='button' value='Finale!' onclick="bump(7)"/></td>
                                </tr>
EDIT;
                            }
                        }
                        echo <<<EDIT
                            <tr>
                                <td><input type="submit" value="Save Changes"/></td>
                            </tr>
                            </table>
                            </form>
                            <form action="../admin/revive.php" method="get">
                                <input type="hidden" name="id" value="$r->id"/>
                                <input type="submit" value="Resurrect"/>
                            </form>
EDIT;
                    } else {
                        echo <<<DATA
                        <h4>Slogan</h4>
				        <p id="slogan">$r->slogan</p>
DATA;
                        if ($r->faction == "ZOMBIE" || $r->faction == "DEAD")
                        {
                            
                            echo <<<DEAD
                            <h4>Kills</h4>
                            <p>$r->kills</p>
DEAD;
                        }
                    }
                    
                    echo "<hr>";
                    
                    if (!$is_owner && $faction != "admin") {
                        echo <<<ACH
                        <div id="ach_list">
                            <h4>Achievements</h4>
                            <dl>
ACH;
                        $query = "SELECT `adata`.`id` AS `id`, `adata`.`name` AS `name`, `adata`.`category` AS `category`, `adata`.`description` AS `desc`, `agets`.`time` AS `time` FROM `ach_data` AS `adata` RIGHT OUTER JOIN (SELECT * FROM `ach_gets` WHERE `user` = '$r->gt_name') AS `agets` ON `adata`.`id` = `agets`.`ach_id` ORDER BY `adata`.`category`, `adata`.`id`";
                        
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
                        echo <<<ACH
                            </dl>
                        </div>
ACH;
                    }
                    
                    if ($faction == "admin")
                    {
                        echo <<<ACH
                        <h2>Manage Achievements</h2>
                        <td>
                        <input type="button" value="Human" id="human" class="selectors" onclick="loadAch('humanach')"/>
                        <input type="button" value="Zombie" id="zombie" class="selectors" onclick="loadAch('zombieach')"/>
                        <input type="button" value="General" id="general" class="selectors" onclick="loadAch('generalach')"/>
                        <div id="ach_control">
ACH;
                        print_ach_checks($r->gt_name);
                        echo ("</div>");
                    }
                    
                    if ($r->faction == "HUMAN" && $is_owner) {
                        echo 	"\t\t<ul>\n".
			                    "\t\t\t<li><strong>Missions Completed:</strong>";
			            echo (" " . $r->early_mission . " Pre-Thursday mission");
						if ($r->early_mission != 1)
					    {
					        echo ("s");
					    }
						echo (" and " . $r->late_mission . " Post-Thursday mission");
					    if ($r->late_mission != 1)
					    {
						   	echo ("s");
					    }
				        echo    "</li>\n".
			                    "\t\t</ul>";
                        $name = $r->fname . " " . $r->lname;
                        $long = (strlen($name)>15) ? "class='long_name'" : "";
                        
                        echo <<<CARD
                        <h3>Player Card!</h3><br /><br />
                        <div id='player_card' style="margin-left:20px;">
                            <div id="tally">Zombie Tally</div>
                            <img src="../images/hvz.png" width="55%" /><br />
                            <h1 $long >$r->fname $r->lname</h1>
        
                            <ul><font face="monospace" size="4">ID:</font>
CARD;

                            foreach(str_split($r->player_code) as $char){
                                echo "<li>$char</li>\n";
                            }
                        echo <<<CARD
                            </ul>
                        </div>
                        <p><a href="print_card.php">Print it!</a> Cut it out! Surrender it upon being tagged!</p>
CARD;
                    }
                    
                    if ($r->faction == "INACTIVE" && $is_owner)
                    {
                        echo '<p>Please take the <a href="../rules/quiz/rules_quiz.php">Rules Quiz</a> to play in the next game</p>';            
                    }
                    
                    if ($r->faction == "ZOMBIE" && $is_owner)
                    {
                        echo <<<KILL
                        <h2>Report a Kill
                            <input type="button" value="Hide"  onclick="toggle(this, document.getElementById('report_kill'))">
                        </h2>
                        <div id="report_kill" class="show">
KILL;
                        print_killform($faction,FALSE);
        
                        echo ("</div>");
                    } 
                    ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>