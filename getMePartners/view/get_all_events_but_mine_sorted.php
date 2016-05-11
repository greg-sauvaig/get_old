<?php

$data = $_GET['data'];
$order = $_GET['order'];
switch ($data) {
	case 'nbUser_order':
	$data = 'nbr_runners';
	break;
	case 'date_order':
	$data = 'event_time';
	break;
	case 'author_order':
	$data = 'lead_user';
	break;
	case 'location_order':
	$data = 'addr_start';
	break;
}
switch ($order) {
	case 'up':
	$order = "ASC";
	break;
	case 'down':
	$order = "DESC"; 
	break;
}

$events = EventList::getAllEventsButMinesSorted($user->id, $_GET['lat'], $_GET['lon'], $_GET['radius'], $data, $order, $bdd);
$json = array();
foreach ($events as $key => $value) {
	array_push($json, json_encode($value)); 
}  
echo(json_encode($json));

?>