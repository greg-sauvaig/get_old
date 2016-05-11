<?php
	$id = $_GET["event"];
	$event = $user->getEventById($id, $bdd);
	if($event){
		echo json_encode($event);
		return;
	}
	else{
		return;
	}
?>