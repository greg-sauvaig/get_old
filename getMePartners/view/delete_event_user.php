<?php
$id = $_GET['delete'];
$json = $user->delete_event_user($bdd, $id);
echo(json_encode($json));
?>