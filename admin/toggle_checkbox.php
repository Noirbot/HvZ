<?
	require("../scripts/lib.php");
	$faction = verify($gt_name);
	if( $faction == 'admin' ){
		
		if(isset($_GET['pid']) and isset($_GET['checkbox']) ){
			$player = $_GET['pid'];
			$field = $_GET['checkbox'];
			
			mysql_query("UPDATE users SET `$field` = 1-`$field` WHERE `id`=$player");
		}
	}
?>