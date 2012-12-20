<?php
require("../scripts/lib.php");


$faction = verify($gt_name);
if($faction == "admin"){
	$mid = isset($_GET['mid']) ? $_GET['mid'] : "";
	if ($mid)
        $db->query("DELETE FROM missions WHERE `id`='$mid'");
}
?>