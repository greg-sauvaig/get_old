<?php

	if(isset($_GET['e'], $_GET["m"])){
		Chat::post_msg();
		return;
	}
	else{
		Chat::get_chat_msg();
		return;
	}

?>