<?php
include_once('db.php');
include_once('player.php');

class Mission {
	/* Get all missions of a certain status */
	/* PATH: /api/mission/{status} */
	/* PARAMS: [0] = status */
	public static
	function get($status) {
		$status = strtolower($status);
		$DBH = InstanceHolder::getInstance('DB');
		$STH;
		switch ($status) {
			case "active":
				$STH = $DBH->prepare("SELECT * FROM missions WHERE (faction=? OR faction='ALL') AND start_datetime < NOW() AND end_datetime > NOW()");
				break;
			case "issued":
				$STH = $DBH->prepare("SELECT * FROM missions WHERE (faction=? OR faction='ALL') AND start_datetime > NOW() AND release_datetime < NOW()");
				break;
			case "closed":
				$STH = $DBH->prepare("SELECT * FROM missions WHERE (faction=? OR faction='ALL') AND end_datetime < NOW()");
				break;
		}

		$STH->bindParam(1, Player::getFaction(InstanceHolder::getInstance('gt_name')));
		$STH->execute();
		
		if ( $STH->rowCount() > 0 ) {
			$missions = $STH->fetchAll(PDO::FETCH_ASSOC);
			echo json_encode($missions);
		} else {
			echo "No missions exist";
		}

	}
}