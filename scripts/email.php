<?php 
	require("lib.php"); 
	//$faction = verify($gt_name);
	/*if($faction != 'admin'){
		if($local)
			header( "Location: http://localhost/faction/$faction.php" );
		else
			header( "Location: http://hvz.gatech.edu/faction/$faction.php" );
	}*/
	
	
	(isset($_POST['faction'])) ? $audience = $_POST['faction'] : die("No audience provided");
	if($audience == '---') die("No audience selected");
	(isset($_POST['subject'])) ? $subject = $_POST['subject'] : die("No subject provided");
	(isset($_POST['message'])) ? $message = $_POST['message'] : die("No message provided");	
	
	$to = "hvzgatech@gmail.com";
	$from = "From: hvzgatech@gmail.com\r\n";
	$reply_to = "Reply-To: hvzgatech@gmail.com\r\n";
	$subject = "[HvZ][$audience] $subject";
	$message = $message;
	
	$bcc = "Bcc: ";
	//Create BCC string to send to correct faction
	if($audience == "EVERYONE")
		$cond = "WHERE `signed_up`=1";
	elseif($audience == "BOTH")
		$cond = "WHERE faction='HUMAN' or faction='ZOMBIE' AND `signed_up`=1";
	else
		$cond = "WHERE faction='$audience' AND `signed_up`=1";
		
	$res = mysql_query("SELECT gt_name FROM users $cond");
	while($r = mysql_fetch_array($res))
		$bcc .= $r['gt_name']."@mail.gatech.edu, ";
	$bcc .= "vince.pedicino@inta.gatech.edu, jmartin34@mail.gatech.edu";
	
	$headers = $from . $reply_to . $bcc;	
	
	
	
	mail($to, $subject, $message, $headers);
	
	header("Location: http://hvz.gatech.edu/admin/");
	?>
	
    
