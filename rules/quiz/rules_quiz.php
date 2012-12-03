<?php 
  require("../../scripts/lib.php");
  $faction = verify($_SESSION["gtname"]);
?>

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
		<h2>Rules Quiz</h2>
        

        
		<?php
			if (quiz_open($current_game) || $faction == "admin")
            {
                echo ("<p>The rules document has been updated and a few changes have been made. Please take the following test to show you have read the new rules and understand them.</p>");
            }
            else
            {
                echo ('<p>Rules quiz will be available on ' . $quiz_open_dates[$current_game] . '. Study up on the <a href="../">Rules</a></p>');
            }
            if( isset($_SESSION['rules_error']) and $_SESSION['rules_error'])
            {
      				echo "<h1><strong><em>One or more of your answers is incorrect, try again!</em></strong></h1>";
      			}


           if (quiz_open($current_game) || $faction == "admin")
           {
                echo ("<div id='rules_quiz'>");
                echo ('<form action="rules_quiz_grader.php" method="post">');
                print_quiz();
                echo('<input type="submit" value="Submit" style="margin:0px"/>');
                echo('</form></div>');
               echo('<p>The quiz does not represent a comprehensive condensed version of the rules.  All players are responsible for knowing and following all of the rules.</p>');
           }
       ?>
        
      <div class="footer"> </div>
      
   </div>

</div>
</body>
</html>

