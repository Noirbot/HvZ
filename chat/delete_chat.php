<?php
require("../scripts/lib.php");

$faction = verify($gt_name);
if($faction == "admin"){
	if (isset($_GET['cid']) && is_numeric($_GET['cid']))
		$cid = $_GET['cid'];
	else
		header("Location: http://www.youtube.com/watch?v=oHg5SJYRHA0");

	if ($cid)
        $db->query("DELETE FROM twits WHERE `id`='$cid'");
}
$_SESSION['hide']=0;
?>