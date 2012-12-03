<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Humans vs Zombies - Georgia Tech</title>
<!-- link type="text/css" rel="stylesheet" href="custom.css" -->
<link type="text/css" rel="stylesheet" href="/css/base.css">
<?php require("../scripts/lib.php"); ?>

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
		<h2>GT Humans vs Zombies - Spring 2012</h2>
		<div style="padding-left:15px;">
			<?php
			if (quiz_open($current_game))
			{
				echo ("<p>You currently aren't signed up to play this round.</p>");
				echo ('<p>Remedy this by taking the <a href="/rules/quiz/rules_quiz.php">Rules Quiz</a>!</p>');
			}
			else
			{
				echo ("<p>The rules quiz for the Fall game will open on September 10th.</p>");
			}
			
			?>
		</div>
			 
      <div class="footer"><p></p></div>
      
   </div>

</div>
</body>
</html>

