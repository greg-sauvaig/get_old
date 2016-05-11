<?php

abstract class EventList{

	private static function isInArea($lat1, $lon1, $lat2, $lon2, $radius)
	{
		if ($lat1 && $lat2 && $radius)
		{
			$R = 6371000; // Rayon de la Terre en mètre
			$rad1 = $lat1 * M_PI / 180;
			$rad2 = $lat2 * M_PI / 180;
	
			$deltaRadLat = ($lat2 - $lat1) * M_PI / 180;
			$deltaRadLon = ($lon2 - $lon1) * M_PI / 180;

			$a = sin($deltaRadLat / 2) * sin($deltaRadLat / 2) + cos($rad1) * cos($rad2) * sin($deltaRadLon / 2) * sin($deltaRadLon / 2);
			$c = 2 * atan2(sqrt($a), sqrt(1 - $a));

			$d = $R * $c;
			$d /= 1000;
			if( $d <= $radius){
				return true;
			}
			return false;
		}else{
			return false;			
		}
	}

	public static function getAllEventsButMines($id, $bdd){
		try{
			$i = 0;
			$query = "CALL getAllEventsButMines($id)";
			$prepared = $bdd->prepare($query);
			$prepared->execute();
			$eventList = array();
			if ($prepared->rowCount() != NULL){
				$data = $prepared->fetchAll(PDO::FETCH_ASSOC);
				foreach ($data as $key => $value) 
				{
					if(isset($_POST['lat_Search']) && isset($_POST['lng_Search']) && isset($_POST['searchRadius']))
					{
						if (self::isInArea($_POST['lat_Search'], $_POST['lng_Search'],$value["latStart"], $value['lonStart'], $_POST['searchRadius'])){
							$eventList[$i] = $value;
							$i++;
						}

					}
					
				}
				return $eventList;
			}else{
				return false;
			}
		}catch (Exception $e){
			echo "Errpr :", $e->getMessage(), "\n";
		}

		return false;
	}

	public static function getAllEventsButMinesSorted($id, $lat, $lon, $radius, $data, $order, $bdd){
		try{
			$i = 0;
			$query = "SELECT  `event`.`id` AS  `id_event` ,  `name` ,  `nbr_runners` ,  `event_time` ,  `statut` ,  `lonStart` ,  `latStart` ,  `lonEnd` ,  `latEnd` ,  `lead_user` ,  `username` ,  `profil_pic` ,  `addr_start` ,  `addr_end` , `event`.`max_runners`
			FROM  `event` 
			INNER JOIN  `user_event` ON  `user_event`.`event_id` =  `event`.`id` 
			INNER JOIN  `user` ON  `event`.`lead_user` =  `user`.`id` 
			WHERE  `user_event`.`user_id` != $id
			ORDER BY $data $order;";
			$prepared = $bdd->prepare($query);
			$prepared->execute();
			$eventList = array();
			if ($prepared->rowCount() != NULL){
				$data = $prepared->fetchAll(PDO::FETCH_ASSOC);
				foreach ($data as $key => $value) 
				{
					if ($lat && $lon && $radius)
					{
						if (self::isInArea($lat, $lon, $value["latStart"], $value['lonStart'], $radius)){
							$eventList[$i] = $value;
							$i++;
						}

					}
					
				}
				return $eventList;
			}else{
				return false;
			}
		}catch (Exception $e){
			echo "Errpr :", $e->getMessage(), "\n";
		}

		return false;
	}
}

?>