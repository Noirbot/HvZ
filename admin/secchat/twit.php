<?php
require("../../scripts/lib.php");

//if (strcasecmp($_SERVER['HTTP_REFERER'], "http://hvz.gatech.edu/chat/index.php") != 0 && strcasecmp($_SERVER['HTTP_REFERER'], "http://hvz.gatech.edu/chat/") != 0)
	//die();

$poster_faction = $_SESSION["faction"];

$audience = isset($_GET['faction']) ? mysql_real_escape_string($_GET['faction']) : 0;
$comment = isset($_GET['comment']) ? substr(mysql_real_escape_string($_GET['comment']),0,140) : 0;
$comment = urldecode($comment);
$comment = str_replace( array('<','>'), '', $comment);



if(($audience == "ALL" or $audience == $poster_faction or $poster_faction == "ADMIN") and ($audience and $comment) and ($gt_name != 'jmorgan3')){
	mysql_query("INSERT INTO `twits` (`user`, `comment`, `audience`) VALUES ('$gt_name', '$comment', '$audience')") or die(mysql_error());
}
?>