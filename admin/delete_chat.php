<?php
require("../scripts/lib.php");

$faction = verify($gt_name);
if($faction == "admin"){
	$cid = isset($_GET['cid']) ? $_GET['cid'] : "";
	if ($cid)
        $db->query("DELETE FROM twits WHERE `id`='$cid'");
}
$_SESSION['hide']=0;
?>