<?php
include_once('db.php');

class Achievement {
	/* Get all unlocked achievements for the player requesting this URL */
    /* PATH: /api/achievements/unlocked */
	public static
	function getUnlockedAchievements() {
		$DBH = InstanceHolder::getInstance('AchievementDB');
		$STH = $DBH->prepare("SELECT ach_data.name, ach_data.category, ach_data.avatar, ach_data.description, ach_gets.time ".
							 "FROM ach_data LEFT JOIN ach_gets ON ach_data.id=ach_gets.ach_id ".
							 "WHERE ach_gets.user=:gt_name ORDER BY ach_gets.time DESC");
		$STH->bindParam(':gt_name', InstanceHolder::getInstance('gt_name'));
		$STH->execute();

		if ( $STH->rowCount() > 0 ) {
			$chat = $STH->fetchAll(PDO::FETCH_ASSOC);
			echo json_encode($chat);
		}
	}
    
    /* Get all locked achievements for the player requesting this URL */
    /* PATH: /api/achievements/locked */
    public static
	function getLockedAchievements() {
		$DBH = InstanceHolder::getInstance('AchievementDB');
		$STH = $DBH->prepare("SELECT name, category, avatar, description ".
							 "FROM ach_data ".
							 "WHERE id NOT IN (SELECT ach_id AS id FROM ach_gets WHERE user=:gt_name) ORDER BY id");
		$STH->bindParam(':gt_name', InstanceHolder::getInstance('gt_name'));
		$STH->execute();

		if ( $STH->rowCount() > 0 ) {
			$chat = $STH->fetchAll(PDO::FETCH_ASSOC);
			echo json_encode($chat);
		}
	}
}