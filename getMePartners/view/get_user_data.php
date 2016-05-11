<?php

$id = $_GET['user'];
$json = $user->get_user_data($bdd, $id);
echo(json_encode($json));

?>