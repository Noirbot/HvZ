<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Humans vs Zombies - Georgia Tech</title>
<!-- link type="text/css" rel="stylesheet" href="custom.css" -->
<link type="text/css" rel="stylesheet" href="/css/base.css">
<?php require("../scripts/lib.php"); ?>

<script type="text/javascript">

	function signup_validator(){
		
		var f = document.forms["signup"];
		var offenses = "";
		
		var fname = f["fname"].value;
		if (fname == null || fname == "")
			offenses += "First Name required.\n";
		var lname= f["lname"].value;
		if (lname == null || lname == "")
			offenses += "Last Name required.\n";

		if(offenses){
			alert(offenses);		
			return false;
		}
		return true;
	}
	
</script>

</head>
<body>
<div id="header">
    <div class="hiddenItem">
        <a href="#start" accesskey="2">Skip to content.</a>
        <a href="#navigation" accesskey="3">Skip to navigation.</a>
    </div>
    <div id="logoWrapper">
        <h1 id="logo">
            <a href="http://www.gatech.edu" accesskey="1"> </a>
        </h1>
		 <div id="siteLogo">
		 	<a href='/'>Humans vs Zombies</a>
		 </div>
	 </div>
    <div id="utilityBar"></div>
    <div id="breadcrumb"><form action='../logout.php'><input type='submit' value='Logout' style='float:right; margin-right:250px;'/></form></div>
</div>

<div id="meat">
   
   <div id="support"><a name="navigation"></a>
        <?php printSideBar(); ?>
    </div>

   <div id="content">
		<h2>Sign Up</h2>
	
		<?php	
			if(!first_time($gt_name)){

				echo "<p>You've already done this.  Go Away.</p>  <p>(If you got here by accident, send me an email with details.  Otherwise, stop trying to break my site.)</p>";
			}
			else{
		?>



		<form name="signup" action="signup_processing.php" onsubmit="return signup_validator()"  method="post">
		<table id='signup_table'>
			<tr>
				<td class='left' width="50%"><label for="fname">First Name</label></td>
				<td><input name="fname" id="fname" type="text" value="" /></td>
			</tr>
			<tr>
				<td class='left'><label for="lname">Last Name</label></td>
				<td><input name="lname" id="lname" type="text" value="" /></td>
			</tr>
			<tr>
				<td class='left'><label for="slogan" class="optional">Slogan</label></td>
				<td><input name="slogan" id="slogan" type="text" value="" /></td>
			</tr>
			<tr>
				<td class='left'><label for="gender" class="optional">Gender</label></td>
				<td><select name='gender' id="gender">
						<option value='PREFER_NOT_TO_ANSWER'>---</option>
						<option value='MALE'>Male</option>
						<option value='FEMALE'>Female</option>
					</select>
				</td>
			</tr>
			<tr>
				<td class='left'><label for="major" class="optional">Major</label></td>
				<td><input name="major" id="major" type="text" value="" /></td>
			</tr>
			<tr>
				<td class='left'><label for="year" class="optional">Year</label></td>
				<td>
					<select name='year' id="year">
						<option value='PREFER_NOT_TO_ANSWER'>---</option>
						<option value='1'>1 year</option>
						<option value='2'>2 years</option>
						<option value='3'>3 years</option>
						<option value='4'>4 years</option>
						<option value='5+'>5+ years</option>																		
					</select>
				</td>
			</tr>
		</table>
				
		<p>Items in red are required for validation purposes.  Items in black are optional and used for demographic purposes only. All tag data will be anonymized (No names, no e-mails, etc) and be made public.  If you choose to provide demographic information, you consent to allow us to anonymize it (No names, no e-mails, etc) and make it public as well.</p>
		
		<p><input type="submit" value="Submit" /></p>

		</form>
		

		
		<?php } ?>
      
   </div>

</div>
</body>
</html>

