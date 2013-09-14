<?php
include_once('db.php');
include_once('player.php');
class Chat {
	/* Get all chat messages starting AFTER the chat message with id = $id */
	/* PATH: /api/chat/{earliest_id} */
	/* PARAMS: [0] = id */
	public static
	function getChat($id) {
		$faction = Player::getFaction(InstanceHolder::getInstance("gt_name"));
		$DBH = InstanceHolder::getInstance('DB');
		$STH = $DBH->prepare("SELECT id, audience, user, , comment FROM twits WHERE id > :id AND (audience='ALL' OR audience=:faction) ORDER BY timestamp ASC");
		$STH->bindParam(':id', $id);
		$STH->bindParam(':faction', $faction);
		$STH->execute();

		if ( $STH->rowCount() > 0 ) {
			$chat = $STH->fetchAll(PDO::FETCH_ASSOC);
			echo json_encode($chat);
		}
	}

	/* Post a new chat messages as the player that is currently making this function call */
	public static
	function newChat() {
		$faction = Player::getFaction(InstanceHolder::getInstance("gt_name"));
        $message = $_GET['message'];
        $DBH = InstanceHolder::getInstance('DB');
		$STH = $DBH->prepare("INSERT INTO twits(user, comment, audience) VALUES(:user,:comment,:audience)");
        $STH->bindParam(':user', InstanceHolder::getInstance("gt_name"));
        $STH->bindParam(':comment', $message);
		$STH->bindParam(':audience', $faction);
		$STH->execute();
	}
}