<?php
require("../scripts/lib.php");

$poster_faction = strtoupper(verify($gt_name));

$audience = isset($_GET['faction']) ? $db->escape_string($_GET['faction']) : 0;
$comment = isset($_GET['comment']) ? $db->escape_string($_GET['comment']) : 0;
$comment = urldecode($comment);
$comment = str_replace( array('<','>'), '', $comment);



if(($audience == "ALL" or $audience == $poster_faction or $poster_faction = "ADMIN") and ($audience and $comment)){
    $db->query("INSERT INTO `twits` (`user`, `comment`, `audience`) VALUES ('$gt_name', '$comment', '$audience')") or die(mysql_error());
}
?>