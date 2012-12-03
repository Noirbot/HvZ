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
			<div id='email'>
		        <div class='mission'>
                    <h2>Send Email</h2>
					<form action='../scripts/email.php' method='post'>
					<table>
						<tr>
						<td>To: </td><td><select name='faction'>
			  					<option value='---'>---</option>
								<option value='HUMAN'>Human</option>
								<option value='ZOMBIE'>Zombie</option>
								<option value='BOTH'>Both</option>
			                    <option value='EVERYONE'>Everyone Ever</option>
							</select></td>
						</tr><tr>
						<td>Subject:</td><td> <input type='text' value='' name='subject' size='23' /></td>
						</tr><tr>
						<td>Message:</td><td> <textarea name='message' cols='69' rows='10' ></textarea></td>
			            </tr>
					</table>
						<input type='submit' name='email' value='E-Mail!'/>
					</form>
		        </div>
		    </div>
		</div>
	</div>
</body>