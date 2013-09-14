<?php
	require("../scripts/lib.php");
	$faction = verify($gt_name);
	
	if($faction == 'admin'){
		$error=0;
		if( isset($_POST['id']) ){
			$id = (isset($_POST['id']) and $_POST['id']) ? $db->escape_string($_POST['id']) : 'error';
			$name = (isset($_POST['name']) and $_POST['name']) ? $db->escape_string($_POST['name']) : 'error';
			$start_datetime = ($_POST['start_datetime'] == 'YYYY-MM-DD 23:59:59') ? 'error' : $db->escape_string($_POST['start_datetime']);
			$end_datetime = ($_POST['end_datetime'] == 'YYYY-MM-DD 23:59:59') ? 'error' : $db->escape_string($_POST['end_datetime']);
			$release_datetime = ($_POST['release_datetime'] == 'YYYY-MM-DD 23:59:59') ? 'error' : $db->escape_string($_POST['release_datetime']);
			$location = (isset($_POST['location']) and $_POST['location']) ? $db->escape_string($_POST['location']) : 'error';
			$faction = $db->escape_string($_POST['faction']);
			$description = (isset($_POST['description']) and $_POST['description']) ? $db->escape_string($_POST['description']) : 'error';
		
			if( $id=='error' or $name == 'error' or $start_datetime == 'error' or $end_datetime == 'error' or $release_datetime == 'error' or $location == 'error' or $faction == 'error' or $description == 'error')
				$error = "Oh noes! You filled something out wrong!";
			else{
                $db->query("UPDATE missions SET name='$name', start_datetime='$start_datetime', end_datetime='$end_datetime', release_datetime='$release_datetime', location='$location', faction='$faction', description='$description' WHERE id='$id'") or die(mysql_error());
				header("Location: http://hvz.gatech.edu/admin/index.php");
			}
			($error) ? die($error) : $a=1;
		}
		header("Location: http://hvz.gatech.edu/admin/past_missions.php");
	}
?>