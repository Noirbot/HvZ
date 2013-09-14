<?php require("../../scripts/lib.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Humans vs Zombies - Georgia Tech</title>
<!-- link type="text/css" rel="stylesheet" href="custom.css" -->
<link type="text/css" rel="stylesheet" href="/css/base.css">


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
	<div id="breadcrumb"><form action='../../logout.php'><input type='submit' value='Logout' style='float:right; margin-right:250px;'/></form></div>
</div>

<div id="meat">

   <div id="support"><a name="navigation"></a>
		<?php printSideBar(); ?>
	</div>

   <div id="content">
		<h2>Official Waiver</h2>
		<?php
			$error = "";
			if (isset($_SESSION["gtiderror"]) && !$_SESSION["gtiderror"])
			{
				$error = "<h2 class=\"wrong\">Invalid GTID Number. Should be 902XXXXXX.</h2>";
			}
			echo <<<WAIVER
			<p class=”waiver”>NOTICE TO ALL PERSONS PARTICIPATING IN ATHLETIC, RECREATIONAL, AND ADVENTURE PROGRAMS, WORKSHOPS, AND OTHER ACTIVITIES INVOLVING RISK OF BODILY OR PERSONAL INJURY AND/OR PROPERTY DAMAGE</p>
            <p class=”waiver”>Many programs, activities and workshops involve substantial risks of injury, property damage and other dangers associated with participation in such activities. Dangers particular to such activities include, but are not limited to: Hypothermia, broken bones, strains, sprains, bruises, drowning, concussion, heart attack, and heat exhaustion.</p>
            <p class=”waiver”>Each participant in the following activity: <strong>Humans versus Zombies</strong>, should realize that there are inherent risks, hazards, and dangers involved, including the training, preparation for, and travel to and from such activities.  It is the responsibility of each participant to engage only in those activities and programs for which he/she has the prerequisite skills, qualifications, preparation and training. </p>
            <p class=”waiver”>The Institute does not warrant or guarantee in any respect the competency or mental or physical condition of any trip leader, vehicle driver, instructor, or individual participant in any athletic, recreational, adventure program, or workshop. </p>
            <p class=”waiver”>ACKNOWLEDGEMENT AND ASSUMPTION OF RISK</p>
            <p class=”waiver”> I have read the above notice carefully and acknowledged receipt of a copy thereof.  In consideration of the benefits received, I hereby assume all risks of damages or injury, including death, that I may sustain while participating in or as a result of, or in any way growing out of any aforementioned activity or program, or in travel to and from such activity. </p>
            <p class=”waiver”>Further I hereby certify that I am covered by an accident and health insurance policy that will be in effect any time that I am participating in Institute related activities or programs. </p>
            <p class=”waiver”>RELEASE AND WAIVER OF LIABILITY AND COVENANT NOT TO SUE (READ CAREFULLY BEFORE SIGNING)</p>
            <p class=”waiver”>The undersigned hereby acknowledges that participation in risk oriented programs and activities involves an inherent risk of physical injury and assumes all risks.  The undersigned hereby agrees that for the sole consideration of the Georgia Institute of Technology allowing the undersigned to participate in these programs and activities, the undersigned does hereby release and forever discharge the Georgia Institute of Technology and the Board of Regents of the University System of Georgia, its members individually, and its officers, agents, and employees from any and from all claims, demands, rights, and causes of action of whatever kind of nature, arising from and by reason of any and all known and unknown, foreseen and unforeseen bodily and personal injuries, damage to property, and the consequences thereof, resulting from any participation in any way connected with such programs and activities. </p>
            <p class=”waiver”>I further covenant and agree that for the consideration stated above I will not sue the Institution, Board of Regents of the University System of Georgia, its members individually, its officers, agents, or employees for any claim for damages arising or growing out of my <strong>voluntary</strong> participation in above said activities. I understand that the acceptance of this releases and covenant not to sue the Institution or the Board of Regents of the University System of Georgia shall not constitute a waiver in whole or part, of sovereign or official immunity by said Board, its members, officers, agents, and employees. By submitting this, I agree to the terms and conditions of this document and absolve the Institute, Humans versus Zombies, and all its affiliates from any harm that may come to my person or self, and I certify that I have read the above carefully before giving my electronic signature.</p>
            <form action="waiver_submit.php" method="post">
            	$error
                <label for="waivername">Your Name: </label><input type="text" name="name" id="waivername"/><label for="gtid"> GTID: </label><input type="text" name="gtid" id="gtid" size="9" maxlength="9"/><input type="hidden" value="$gt_name" name="gtname"/>
                <input type="submit">
            </form>
WAIVER;
	   ?>

	  <div class="footer"> </div>

   </div>

</div>
</body>
</html>

