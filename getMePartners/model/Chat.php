<?php

abstract class Chat{

	/* chat function */
	public static function post_msg(){
		$bdd = Db::dbConnect();
		$event_id = $_GET["e"];
		$chat_msg = $_GET["m"];
		$user = new User($_COOKIE['getMePartners'], $bdd);
		$user_id = $user->id;
		if(true){
			$message_id = Chat::insert_msg($chat_msg, $bdd);
			if($message_id){
				$link = Chat::link_msg_to_user($user_id, $message_id, $bdd);
				if($link){
					if(Chat::link_msg_to_event_chat($event_id, $message_id, $bdd)){
						echo(json_encode(array("ok" => "Vous pouvez envoyer vos messages.")));
						return;
					} 
					else{
						var_dump(Chat::link_msg_to_event_chat($event_id, $message_id, $bdd));
						echo(json_encode(array("error" => "erreur lors de la procedure de lien du chat.")));
						return;
					}  
				}
				else{
					echo(json_encode(array("error" => "erreur lors de la procedure de lien du message.")));
					return;
				}	
			}
			else{
				echo(json_encode(array("error" => "erreur lors de la procedure d'enregistrement du message.")));
				return;
			}
		}
	}

	public static function get_chat_msg(){
		$bdd = Db::dbConnect();
		$event_id = $_GET["e"];
		try{
			$query = "SELECT `text`, `msg_insertts` as `time`, `username` FROM `msg` 
						join `event_has_chat` on `msg`.`id` = `event_has_chat`.`fk_id_msg` 
						join `user_msg` on `msg`.`id` = `user_msg`.`msg_id` 
						join `user` on `user_msg`.`user_id` = `user`.`id` 
						WHERE `event_has_chat`.`fk_id_event` = $event_id ORDER BY `msg_insertts` ASC;";
			$data = $bdd->prepare($query);
			$data->execute();
			$res = $data->fetchAll(PDO::FETCH_ASSOC);
			if ($res){
				$ret = array();
				foreach ($res as $key => $value) {
					array_push($ret, json_encode($value));
				}
				echo(json_encode($ret));
				return;
			}else{
				echo(json_encode(array("error" => "Pas de message dans le tchat, soyez le premier à en poster un !")));
				return;
			}
		}catch (Exception $e){
			echo(json_encode(array("error" => "erreur contactez un administrateur.")));
			return;
		}
	}

	public static function event_have_chat($id, $bdd){
		try{
			$query = "SELECT * FROM `event_has_chat` WHERE `fk_id_event` = $id;";
			$data = $bdd->prepare($query);
			$data->execute();
			if ($data->rowCount() === 1){
				return true;
			}else{
				return false;
			}
		}catch (Exception $e){
			return false;
		}
	}

	public static function link_msg_to_event_chat($id_event, $id_msg, $bdd){
		try{
			$query = "INSERT INTO `event_has_chat` (`fk_id_msg`, `fk_id_event`) VALUES($id_msg, $id_event)";
			$data = $bdd->prepare($query);
			$data->execute();
			if ($data->rowCount() == 1){
				return true;
			}else{
				return false;
			}
		}catch (Exception $e){
			echo "Error : ", $e->getMessage(), "\n";
			return false;
		}
	}

	public static function insert_msg($msg, $bdd){
		try{
			$time = time();
			$query = "INSERT INTO `msg` (`text`, `msg_insertts`) VALUES('$msg', $time)";
			$data = $bdd->prepare($query);
			$data->execute();
			if ($bdd->lastInsertId() != null){
				return $bdd->lastInsertId();
			}else{
				return false;
			}
		}catch (Exception $e){
			return false;
		}
	}

	public static function link_msg_to_user($id_user, $id_msg, $bdd){
		try{
			$query = "INSERT INTO `user_msg` (`user_id`, `msg_id`) VALUES($id_user, $id_msg)";
			$data = $bdd->prepare($query);
			$data->execute();
			if ($bdd->lastInsertId()){
				return $bdd->lastInsertId();
			}else{
				return false;
			}
		}catch (Exception $e){
			return false;
		}
	}
}

?>