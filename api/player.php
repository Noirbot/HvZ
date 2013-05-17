<?php
include_once('db.php');
class Player {
	/* PATH: /api/player/{playername} */
	public static
	function get($gt_name) {
		$DBH = InstanceHolder::getInstance('DB');
		$STH = $DBH->prepare("SELECT id, gt_name, fname, lname, player_code, faction, slogan, avatar, kills, starve_time FROM users WHERE gt_name=?");
		$STH->bindParam(1, $gt_name);
		$STH->execute();
		
		if ( $STH->rowCount() > 0 ) {
			$player = $STH->fetch(PDO::FETCH_ASSOC);
			
			if ( $gt_name != InstanceHolder::getInstance("gt_name")) {
				unset($player['player_code']);
			}
			echo json_encode($player);
		} else {
			echo "No player by that id";
		}

	}

	public static
	function registerKill() {
             
        //Only let Zombies kill
        if (self::getFaction(InstanceHolder::getInstance("gt_name")) != 'ZOMBIE') {
            echo "Only Zombies can kill!";
            die;
        }
        
		if (isset($_GET['playerCode']) && isset($_GET['feed1']) &&
			isset($_GET['feed2']) && isset($_GET['lat']) && isset($_GET['lng'])) {

            
			//Begin a transaction
			$DBH = InstanceHolder::getInstance('DB');
			$DBH->beginTransaction();

			try {
				//Check if player code belongs to human and is valid
				$victim = self::validatePlayerCode($_GET['playerCode']);

				if ($victim !== False) {
					//Insert kill into kills table
					$STH = $DBH->prepare("INSERT INTO kills (killer, victim, lat, lng) VALUES (:killer,:victim,:lat,:lng)");
					$STH->bindParam(':killer', InstanceHolder::getInstance('gt_name'));
					$STH->bindParam(':victim', $victim);
					$STH->bindParam(':lat', $_GET['lat']);
					$STH->bindParam(':lng', $_GET['lng']);
					if (!$STH->execute()) {
						throw new Exception('Could not insert into kills table.');
					}

					//Calculate starve time
					if( date("H") < "07" )
						$starve = date("Y-m-d H:m:s", strtotime("+28 hours"));
					else
						$starve = date("Y-m-d H:m:s", strtotime("+48 hours"));

					//Convert human to zombie
					$STH = $DBH->prepare("UPDATE users SET faction='ZOMBIE', starve_time=:starve, kills=0 WHERE gt_name=:victim");
					$STH->bindParam(':victim', $victim);
					$STH->bindParam(':starve', $starve);
					if (!$STH->execute()) {
						throw new Exception('Could not convert human to zombie.');
					}

					//update the current player's kill and starve time
					$STH = $DBH->prepare("UPDATE users SET starve_time = DATE_ADD(NOW(), INTERVAL 2 DAY), kills=kills+1 WHERE gt_name=:killer");
					$STH->bindParam(':killer', InstanceHolder::getInstance('gt_name'));
					if (!$STH->execute()) {
						throw new Exception("Could not update current player's kill count and starve time");
					}

					//Check if both players we are feeding are actually zombies
					if (self::getFaction($_GET['feed1']) == 'ZOMBIE' && self::getFaction($_GET['feed2']) == 'ZOMBIE') {
						//feed 2 zombies
						$STH = $DBH->prepare("UPDATE users SET starve_time = DATE_ADD(NOW(), INTERVAL 2 DAY) WHERE gt_name=:feed1 OR gt_name=:feed2");
						$STH->bindParam(':feed1', $_GET['feed1']);
						$STH->bindParam(':feed2', $_GET['feed2']);
						if (!$STH->execute()) {
							throw new Exception("Could not feed those two zombies!");
						}						
					} else {
 						throw new Exception("You can't feed a non-zombie!");
					}

				} else {
					throw new Exception('Invalid player code, or not a human.');
				}

				//commit transaction
				$DBH->commit();
			} catch (Exception $e) {
				$DBH->rollback();
				die($e->getMessage());
			}		

		}

	}

	/* PATH: /api/player/{playername}/kills */
	public static
	function kills($killer) {
		$DBH = InstanceHolder::getInstance('DB');
		$STH = $DBH->prepare("SELECT victim, lat, lng, time FROM kills WHERE killer=?");
		$STH->bindParam(1, $killer);
		$STH->execute();
		
		if ( $STH->rowCount() > 0 ) {
			$kills = $STH->fetchAll(PDO::FETCH_ASSOC);
			echo json_encode($kills);
		} else {
			echo "No kills";
		}

	}


	/* PATH: /api/faction/{factionname} */
	public static
	function faction($faction) {
		$faction = strtoupper($faction);
   
		$DBH = InstanceHolder::getInstance('DB');
        
        if ($faction == 'HUMAN') {
            $STH = $DBH->prepare("SELECT gt_name, fname, lname, slogan, avatar, faction ".
                                 "FROM users WHERE faction=:faction AND rules_quiz = 1 AND fname != 'Feed' AND lname != 'Feed' ORDER BY lname ASC");
        } else if ($faction == 'ZOMBIE') {
            if (!isset($_GET['order'])) $_GET['order'] = 'starve_time';
            switch ($_GET['order']) {
                case 'lname':
                    $STH = $DBH->prepare("SELECT gt_name, fname, lname, slogan, avatar, starve_time, kills, faction ".
                                         "FROM users WHERE faction=:faction AND oz != 1 AND fname != 'Feed' AND lname != 'Feed' ORDER BY lname ASC");
                    break;
                case 'starve_time':
                default:
                    $STH = $DBH->prepare("SELECT gt_name, fname, lname, slogan, avatar, starve_time, kills, faction ".
                                         "FROM users WHERE faction=:faction AND oz != 1 AND fname != 'Feed' AND lname != 'Feed' ORDER BY starve_time ASC");
                    break;
            }
        }

		$STH->bindParam(':faction', $faction);

		$STH->execute();
		
		if ( $STH->rowCount() > 0 ) {
			$players = $STH->fetchAll(PDO::FETCH_ASSOC);
			echo json_encode($players);
		}
	}

	public static
	function getFaction($gt_name) {
		$DBH = InstanceHolder::getInstance('DB');
		$STH = $DBH->prepare('SELECT faction FROM users WHERE gt_name=:gt_name');
		$STH->bindParam(':gt_name', $gt_name);
		$STH->execute();
		
		if ( $STH->rowCount() > 0 ) {
			$row = $STH->fetch(PDO::FETCH_ASSOC);
			return $row['faction'];
		} else {
			echo "No players by that name";
		}
	}

	private static
	function validatePlayerCode($playerCode) {
		$DBH = InstanceHolder::getInstance('DB');
		$STH = $DBH->prepare("SELECT * FROM users WHERE player_code=? AND faction='HUMAN' LIMIT 1");
		$STH->bindParam(1, trim($playerCode));
		$STH->execute();
		
		if ( $STH->rowCount() > 0 ) {
			$row = $STH->fetch(PDO::FETCH_ASSOC);
			return $row['gt_name'];
		} else {
			return False;
		}
	}
}