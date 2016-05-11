<?php

class Event{
	public $id;
	public $name;
	public $nbr_runners;
	public $event_time;
	public $statut;
	public $lonStart;
	public $latStart;
	public $lonEnd;
	public $latEnd;
	public $lead_user;

	public $lead_user_name;
	public $lead_user_pic;

	public function __construct($name, $bdd){
		return $this->getEventByName($name, $bdd);
	}


	public function getEventByName($name, $bdd){
		try{
			//Recuperation de l'evenement en fonction de son nom (champ unique en bdd)
			$query = "CALL getEventByName('$name')";
			$data = $bdd->prepare($query);
			$data->execute();
			if ($data->rowCount() === 1){
				$data = $data->fetch(PDO::FETCH_ASSOC);
				foreach ($data as $key => $value) { //Definition de chaque attribut de l'event depuis la bdd
					$this->$key = $value;
				}
				return $this;
			}else{
				return false;
			}
		}catch (Exception $e){
			echo "Error : ", $e->getMessage(), "\n";
			return false;
		}
	}



	public function getEventById($id, $bdd){
		try{
			//Recuperation de l'evenement en fonction de son nom (champ unique en bdd)
			$query = "SELECT * FROM `event` WHERE `id` = $id;";
			$data = $bdd->prepare($query);
			$data->execute();
			if ($data->rowCount() === 1){
				$data = $data->fetch(PDO::FETCH_ASSOC);
				return $data;
			}else{
				return false;
			}
		}catch (Exception $e){
			echo "Error : ", $e->getMessage(), "\n";
			return false;
		}
	}
/* chat function */
	public function event_have_chat($id, $bdd){
		try{
			$query = "SELECT * FROM `event_has_chat` WHERE `id_event` = $id;";
			$data = $bdd->prepare($query);
			$data->execute();
			if ($data->rowCount() === 1){
				return true;
			}else{
				return false;
			}
		}catch (Exception $e){
			echo "Error : ", $e->getMessage(), "\n";
			return false;
		}
	}

	public function link_msg_to_event_chat($id_event, $id_msg, $bdd){
		try{
			$query = "INSERT INTO `event_has_chat` (`fk_id_msg`, `fk_id_event`) VALUES($id_msg, $id_event)";
			$data = $bdd->prepare($query);
			$data->execute();
			if ($data->rowCount === 1){
				return true;
			}else{
				return false;
			}
		}catch (Exception $e){
			echo "Error : ", $e->getMessage(), "\n";
			return false;
		}
	}

	public function insert_msg($msg, $bdd){
		try{
			$time = time();
			$query = "INSERT INTO `msg` (`text`, `msg_insertts`) VALUES($msg, $time)";
			$data = $bdd->prepare($query);
			$data->execute();
			if ($data->lastInsertId() != null){
				return $data->lastInsertId();
			}else{
				return false;
			}
		}catch (Exception $e){
			echo "Error : ", $e->getMessage(), "\n";
			return false;
		}
	}

	public function link_msg_to_user($id_user, $id_msg, $bdd){
		try{
			$query = "INSERT INTO `user_msg` (`user_id`, `msg_id`) VALUES($id_user, $id_msg)";
			$data = $bdd->prepare($query);
			$data->execute();
			if ($data->lastInsertId() != null){
				return $data->lastInsertId();
			}else{
				return false;
			}
		}catch (Exception $e){
			echo "Error : ", $e->getMessage(), "\n";
			return false;
		}
	}

}

?>
