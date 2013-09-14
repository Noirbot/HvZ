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
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js" type="text/javascript"></script>
<script type="text/javascript">
	function toggle_checkbox(checkbox_name, id){
		var request = new XMLHttpRequest();
		request.open("GET", "toggle_checkbox.php?pid="+id+"&checkbox="+checkbox_name, false);
		request.send(null);
	}

    function is_checked(faction) {
        document.getElementById('fname').innerHTML = faction;
        return document.getElementById(faction).checked;
    }

	function set_visible() {
		var table = document.getElementById("users");
		//var shown_rows = new Array();
        var cur_faction;
		
		// Handle faction visibility
		for (var i = 1, row; row = table.rows[i]; i++)
        {
            cur_faction = row.cells[4].innerHTML.toLowerCase();
            if (is_checked(cur_faction))
            {
                if (!(row.cells[0].innerHTML == '' || row.cells[1].innerHTML == ''))
				{
					row.className = "show";
					//shown_rows.push(i);
				}
            }
            else
            {
                row.className = "hide";
            }
        }
		// First name visibility
		var fname =  document.getElementById("fname").value;
        if (fname != "")
        {
            for (i = 1; row = table.rows[i]; i++)
            {
                if (row.className == "show")
                {
                    if (row.cells[0].innerHTML.toUpperCase().indexOf(fname.toUpperCase()) == -1)
                    {
                        row.className = "hide";
                    }
                }
            }
        }

        // Last Name visibility
        var lname =  document.getElementById("lname").value;
        if (lname != "")
        {
            for (i = 1; row = table.rows[i]; i++)
            {
                if (row.className == "show")
                {
                    if (row.cells[1].innerHTML.toUpperCase().indexOf(lname.toUpperCase()) == -1)
                    {
                        row.className = "hide";
                    }
                }
            }
        }
	}

	function add_mission(id, isLate)
	{
        var lookupID = isLate ? "lmission" : "emission";

        var $button = $("#" + id).find("." + lookupID);

        $button.css("background-color", "black")

        $.ajax({
            type: "POST",
            url: "credit/add_credit.php",
            data: {id: id, mode : 1, late : isLate},
            cache: false,
            success: function(data)
            {
                $button.val(parseInt($button.val()) + 1);
            }
        });

        setTimeout( function() {
            $button.css("background-color", "white");
        }, 250);
	}

	function rem_mission(id)
	{
		var request = new XMLHttpRequest();
		request.open("GET", "credit/add_credit.php?id="+id+"&mode=0", false);
		request.send(null);
	}
</script>

</head>
<body onload="set_visible()">
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
	    	<h2>Players Table - 
	    		<?php
	    			$query = "SELECT SUM(CASE `faction` WHEN 'DEAD' THEN 1 ELSE 0 END) AS `dead`, SUM(CASE `faction` WHEN 'BANNED' THEN 1 ELSE 0 END) AS `banned`, SUM(CASE `faction` WHEN 'ADMIN' THEN 1 ELSE 0 END) AS `admin`, SUM(CASE `faction` WHEN 'HUMAN' THEN 1 ELSE 0 END) AS `human`, SUM(CASE `faction` WHEN 'ZOMBIE' THEN 1 ELSE 0 END) AS `zombie`, SUM(CASE `faction` WHEN 'INACTIVE' THEN 1 ELSE 0 END) AS `inactive` FROM `users`";
	    			$result = $db->query($query);
	    			$r2 = $result->fetch_assoc();
	    			$total = $r2['dead'] + $r2['zombie'] + $r2['human'];
	    			echo($total);
	    		?>
	    	Players</h2>
	        <div id='players'>
		        <form action='search_table.php' method='post'>
		        	Search In: Inactive (With Name) <input type='checkbox' id='inactive' onclick="set_visible()"/> &nbsp;&nbsp;
		            		 Dead (<?php echo ($r2['dead']); ?>)<input type='checkbox' id='dead' onclick="set_visible()"/>&nbsp;&nbsp;
		                     Banned (<?php echo ($r2['banned']); ?>)<input type='checkbox' id='banned' onclick="set_visible()"/>&nbsp;&nbsp;
		                     Admin (<?php echo ($r2['admin']); ?>)<input type='checkbox' id='admin' onclick="set_visible()"/>&nbsp;&nbsp;
                             Human (<?php echo ($r2['human']); ?>)<input type='checkbox' id='human' onclick="set_visible()"/>&nbsp;&nbsp;
                             Zombie (<?php echo ($r2['zombie']); ?>)<input type='checkbox' id='zombie' onclick="set_visible()"/>&nbsp;&nbsp;
                             <input type="checkbox" id='npc' class="hide"/>
                             <input type="checkbox" id='did_not_do_missions' class="hide"/>
							<br />
							First Name <input type='text' name='fname' id='fname' onkeyup="set_visible()"/>
                            Last Name <input type='text' name='lname' id='lname' onkeyup="set_visible()"/>
		        </form>
                <?php $res = $db->query("SELECT * FROM users WHERE (fname != '' OR lname != '') AND fname != 'Feed' AND lname != 'Feed' ORDER BY `faction` DESC, `early_mission` DESC, `late_mission` DESC, lname ASC, fname ASC"); ?>
                <table border='1' style="padding:0; margin:0;" id="users">
                    <tr>
                        <td><strong>F Name</strong></td>
                        <td><strong>L Name</strong></td>
                        <td><strong>GT Name</strong></td>
                        <td><strong>GTID</strong></td>
                        <td><strong>Faction</strong></td>
                        <td><strong>Code</strong></td>
                        <td><strong>M1</strong></td>
                        <td><strong>M2</strong></td>
                        <td><strong>Has Info</strong></td>
                        <td><strong>Quiz</strong></td>
                    </tr>

                     <?php
                     while($r = $res->fetch_assoc()){
                         $id=$r['id'];
                         $emission = $r['early_mission'];
                         $lmission = $r['late_mission'];
                         $signed_check= ($r['signed_up']) ? "checked='checked'" : '';
                         $rules_check = ($r['rules_quiz']) ? "checked='checked'" : '';
                        echo "\t\t<tr class='hide' id='$id''>";
                            echo "<td>".$r['fname']."</td>";
                            echo "<td>".$r['lname']."</td>";
                            echo "<td><a href='../profile/index.php?id=$id'>".$r['gt_name']."</a></td>";
                            echo "<td>".$r['gtid']."</td>";
                            echo "<td>".$r['faction']."</td>";
                            echo "<td>".$r['player_code']."</td>";
                            echo "<td><input type='button' onclick='add_mission($id, false)' value='$emission' class='emission'/></td>";
                            echo "<td><input type='button' onclick='add_mission($id, true)' value='$lmission' class='lmission'/></td>";
                            echo "<td><input type='checkbox' name='$id|signed_up' value='signed_up' $signed_check onclick='toggle_checkbox(\"signed_up\",$id)'/></td>";
                            echo "<td><input type='checkbox' name='$id|rules_quiz' value='rules_quiz' $rules_check onclick='toggle_checkbox(\"rules_quiz\",$id)'/></td>";
                            echo "</tr>\n";
                     }
                     ?>
                </table>
			</div>
		</div>
	</div>
</body>
</html>