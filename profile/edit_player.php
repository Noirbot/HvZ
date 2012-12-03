<?php require("../scripts/lib.php"); 
	$faction = verify($gt_name);
	
	if(!isset($_GET['id']) or $faction != 'admin')
		header("Location: http://hvz.gatech.edu/profile");
	$id = $_GET['id'];
	
	if( isset($_POST['id']) ){
		$faction = $_POST['faction'];
		$slogan = mysql_escape_string($_POST['slogan']);
		$signed_up = isset($_POST['signed_up'])?1:0;
		$rules_quiz = isset($_POST['rules_quiz'])?1:0;
		$starve_time = $_POST['starve_time'];

		if( $faction == '---' )
			$query = "UPDATE users SET slogan='$slogan', signed_up='$signed_up', rules_quiz='$rules_quiz', starve_time='$starve_time' WHERE id='$id'";
		else
			$query = "UPDATE users SET faction='$faction', slogan='$slogan', signed_up='$signed_up', rules_quiz='$rules_quiz', starve_time='$starve_time' WHERE id='$id'";
		mysql_query($query) or die(mysql_error());
	}
	
	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Humans vs Zombies - Georgia Tech</title>
<link type="text/css" rel="stylesheet" href="/css/base.css">
<script language="JavaScript">
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
				$res = mysql_query("SELECT * FROM `users` WHERE `id`='$id'") or die("User Query Fail");
				$r = mysql_fetch_assoc($res);
			
				if( mysql_num_rows($res) < 1 ) die("<h2>Invalid Player</h2>");
			
			?>

		<i><h3><?php echo $r["fname"]." ".$r['lname']; ?></h3></i><h4>&nbsp;-&nbsp;
				<?php
					echo $r["faction"];
				?>
				</h4>
			<div id="avatar">
				<img src="../images/avatars/<?php echo strtolower($r["faction"]); ?>.png"  width='200px' />
			</div>
            <form action='edit_player.php?id=<?php echo $id; ?>' method='post'>
            	<input type='hidden' name='id' value='<?php echo $id; ?>'  />
	            <table>
    	        	<tr>
        	        	<td>Faction</td>
            	        <td><select name='faction'>
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
                        <td>Slogan</td>
                        <td><textarea name='slogan'><?php echo $r['slogan']; ?></textarea></td>
                     </tr>
                     <tr>
                     	<td>Signed up</td>
                        <td><input name='signed_up' type='checkbox' <?php echo ($r['signed_up']) ? "checked='checked'":""; ?>/></td>
                     </tr>  
                     <tr>
                     	<td>Rules Quiz</td>
                        <td><input name='rules_quiz' type='checkbox' <?php echo ($r['rules_quiz']) ? "checked='checked'":""; ?>/></td>
                     </tr>
                     <tr>
                     	<td>Starve Time</td>
                        <td><input id='time' name='starve_time' type='text' value='<?php echo $r["starve_time"];?>' /> <input type='button' value='now' onclick='bump(0)' /> <input type='button' value='+24 hrs' onclick="bump(1)"/> <input type='button' value='+48 hrs' onclick="bump(2)"/> <input type='button' value='Finale!' onclick="bump(7)"/></td>
                    </tr>
                </table>
                <input type='submit' value='Edit' />
            </form>
			<?php
			echo <<<ACH
			<h2>Manage Achievements</h2>
			<td>
			<input type="button" value="Human" id="human" class="selectors" onclick="loadAch('humanach')"/>
			<input type="button" value="Zombie" id="zombie" class="selectors" onclick="loadAch('zombieach')"/>
			<input type="button" value="General" id="general" class="selectors" onclick="loadAch('generalach')"/>
			<div id="ach_control">
ACH;
			print_ach_checks($r["gt_name"]);
			echo ("</div>");
			?>
		</div>
     <div class="footer"><p></p></div>
      
   </div>

</div>
</body>
</html>