<?php
include_once('db.php');
class Message {
	/* PATH: /api/messages/new */
	public static
	function newMessages() {
		$DBH = InstanceHolder::getInstance('DB');
		$STH = $DBH->prepare("SELECT messages.id, messages.user_from, messages.user_to, messages.timestamp, messages.message, ".
							 "CONCAT(users.fname, ' ', users.lname) AS full_name ".
							 "FROM messages LEFT JOIN users ON messages.user_from = users.gt_name ".
							 "WHERE messages.user_to=? AND messages.retrieved=0");

		$STH->bindParam(1, InstanceHolder::getInstance('gt_name'));
		$STH->execute();
		
		if ( $STH->rowCount() > 0 ) {
			$messages = $STH->fetchAll(PDO::FETCH_ASSOC);
			//Set all messages as retrieved
			foreach ($messages as $message) {
				$STH = $DBH->prepare("UPDATE messages SET retrieved=1 WHERE id=?");
				$STH->bindParam(1, $message['id'], PDO::PARAM_INT);
				$STH->execute();
			}
			echo json_encode($messages);
		} else {
			echo "No messages exist";
		}
	}

	/* PATH: /api/messages */
	public static
	function oldMessages() {
		$count = 2;
		$offset = 0;

		//Try to get a valid # of messages to retrieve
		if (isset($_GET['count'])) {
			$count = is_numeric($_GET['count']) ? (int) abs($_GET['count']) : 10;
		}

		//Try to get an offset into the messages
		if (isset($_GET['offset'])) {
			$offset = is_numeric($_GET['offset']) ? (int) abs($_GET['offset']) : 0;
		}

		$DBH = InstanceHolder::getInstance('DB');
		$STH = $DBH->prepare("SELECT messages.user_from, messages.user_to, messages.timestamp, messages.message, ".
							 "CONCAT(users.fname, ' ', users.lname) AS full_name ".
							 "FROM messages LEFT JOIN users ON messages.user_from = users.gt_name ".
							 "WHERE messages.user_to=? AND messages.retrieved=1 ORDER BY messages.timestamp DESC LIMIT ?,?");

		$STH->bindParam(1, InstanceHolder::getInstance('gt_name'));
		$STH->bindParam(2, $offset, PDO::PARAM_INT);
		$STH->bindParam(3, $count, PDO::PARAM_INT);
		$STH->execute();
		
		if ( $STH->rowCount() > 0 ) {
			$messages = $STH->fetchAll(PDO::FETCH_ASSOC);
			echo json_encode($messages);
		} else {
			echo "No messages exist";
		}

	}

	/* PATH: /api/messages/sent */
	public static
	function sentMessages() {
		$DBH = InstanceHolder::getInstance('DB');
		$STH = $DBH->prepare("SELECT messages.user_from, messages.user_to, messages.timestamp, messages.message, ".
							 "CONCAT(users.fname, ' ', users.lname) AS full_name ".
							 "FROM messages LEFT JOIN users ON messages.user_to = users.gt_name ".
							 "WHERE messages.user_from=?");

		$STH->bindParam(1, InstanceHolder::getInstance('gt_name'));
		$STH->execute();
		
		if ( $STH->rowCount() > 0 ) {
			$messages = $STH->fetchAll(PDO::FETCH_ASSOC);
			echo json_encode($messages);
		} else {
			echo "No messages exist";
		}
	}

	public static
	function newMessage() {
		if (isset($_GET['to']) && isset($_GET['message'])) {
			$DBH = InstanceHolder::getInstance('DB');
			$STH = $DBH->prepare("INSERT INTO messages(user_from, user_to, message) VALUES(?,?,?)");

			$STH->bindParam(1, InstanceHolder::getInstance('gt_name'));
			$STH->bindParam(2, $_GET['to']);
			$STH->bindParam(3, $_GET['message']);

			if ( !$STH->execute() ) {
				echo "Error sending message.";
			}
		} else {
			echo "Please supply a recipient and message body.";
		}
	}


}