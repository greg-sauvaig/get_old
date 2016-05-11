<!-- right container -->
<div  id="my-event-container">
    <div ><h3 class="center-text">Mes événements:</h3></div>
<?php


function getAddr($lat,$lng){
	$url = 'http://maps.googleapis.com/maps/api/geocode/json?latlng='.trim($lat).','.trim($lng).'&sensor=false';
	$json = @file_get_contents($url);
	$data=json_decode($json);
	$status = $data->status;
	if($status=="OK")
		return $data->results[0]->formatted_address;
	else
		return false;
}

$p = $user->myEvents;
$p_size = count($p);
$messagesParPage = 4; 
$nombreDePages = ceil ($p_size/$messagesParPage);
$a = 0;
$c = 0;

// js pour switch entre les pages
for ($i=0; $i < $nombreDePages ; ++$i) { 
	echo(" <script type='text/javascript'>$(document).ready(function(){");echo" $('#btn".$i."').click(function(){";
	for ($e=0; $e < $nombreDePages; $e++) { 
		echo("$('#page".$e."').hide();");
		echo("$('#btn".$e."').css('background','#bbb');");
		echo("$('#btn".$e."').css('border-bottom','1px solid #999');");
		echo("$('#btn".$e."').css('z-index','1');");
	}
	echo("$('#page".$i."').show();");
	echo("$('#btn".$i."').css('background','#ddd');");
	echo("$('#btn".$i."').css('border-bottom','0px');");
	echo("$('#btn".$i."').css('z-index','1000');");
	echo("});});</script>");
}
echo('<script type="text/javascript">$(document).ready(function(){$("#btn0").click();});</script>'); 

// bouton pour les pages
echo('<div id="btn-page-container">');
for ($i=0; $i < $nombreDePages ; ++$i) {
	echo("<div class='btn-page' id='btn$i' ><center>".($i+1)."</center></div>");
}
echo('</div>');
// bouton pour les tris
echo('<div id="order_for_page">Trier par:<button id="status_order"><div class="chevron-up"></div>&nbspStatus</button><button id="author_order"><div class="chevron-up"></div>&nbspAuteur</button><button id="date_order"><div class="chevron-up"></div>&nbspDate</button><button id="location_order"><div class="chevron-up"></div>&nbspLieu</button></div>');
?>

<script type="text/javascript">
	$(document).ready(function(){

		function write_event(b,c,lead_user_pic,event_satus,nbr_runners,lead_user_name,date,event_addr,event_id){
			var str = '<div class="event-container" >'+'<div class="event-content">'+'<div class="event-author-pic">';
			if(lead_user_pic){
				str += '<img src="./'+lead_user_pic+'" style="height:100px;width:100px;">';
			}else{
				str += '<img src="./image/info.jpg" style="height:100px;width:100px;">'; 
			}
			str += '</div><div class="event-text"><label>Statut : </label><h5>';
			if(event_satus == 0)
				str += "Non commencé : <img src='./image/waiting.jpg' style='height:10px;width:10px;'></h5>";
			else if(event_satus == 1)
				str += "En cours : <img src='./image/on.jpg' style='height:10px;width:10px;'></h5>";
			else if(event_satus == 10)
				str += "Course finie : <img src='./image/end.jpg' style='height:10px;width:10px;'></h5>";
			else if(event_satus == 11)
				str += "Course annulée : <img src='./image/cancel.jpg' style='height:10px;width:10px;'></h5>";
			else
				str += "pas de status definit</h5>";
			if(nbr_runners < 10 && nbr_runners >= 1){
				str += '<center><button class="join-event btn" data-event="'+event_id+'" ><i class="fa fa-trash" aria-hidden="true"></i>&nbsp Quitter</button></center>';
			}else{ 
				str += '<div>La course est pleine</div>';
				str += '<center><button class="join-event btn" data-event="'+event_id+'" ><i class="fa fa-trash" aria-hidden="true"></i>&nbsp Quitter</button></center>';
			}
			str += '</div><div class="event-text"><label>Auteur : </label><h5>';
			if(lead_user_name){
				str += '<center>'+lead_user_name+'</center>';
			}else{
				str += "Pas de nom définit";
			}
			str += '</h5></div><div class="event-text"><label>Date de l\'evenement : </label><h5>';
			if(date != 0){
				str += date;
			}else{ 
				str += "Pas de date définit";
			}
			str += '</h5></div><div class="event-text"><label>Lieu de l\'evenement : </label><h5>';
			if(event_addr){ 
				str += event_addr;
			}else{
				str += "Pas d'adresse definit";
			}
			str += '</h5></div><div class="chat-btn"><button class="chat-button btn"><i class="fa fa-comment-o" aria-hidden="true"></i>&nbspchattez!</button></div><div class="event-pic"><img src="http://www.developpez.net/forums/attachments/p166896d1421856637/java/general-java/java-mobiles/android/integrer-personnaliser-carte-type-google-maps/googlemap.gif/" style="">';
			str += '<a class="event-info" href="#" title="info"><img src="./image/zoom.png" style="height:50px;margin:25px;" data-event="'+event_id+'" data-toggle="modal" data-target="#myModal"></a></div></div></div>';
			return str;
		}

		$("#status_order").click(function(){
			data = $(this).attr('id');
			classe = $(this).children('div').attr('class');
			order = classe.substr(8);
			if (order == "up"){
				$(this).children('div').attr("class","chevron-down");
			}
			else if (order == "down"){
				$(this).children('div').attr("class","chevron-up");
			}
			var pagesnbr = <?php echo "$nombreDePages";?>;
			for(var page = 0; page < pagesnbr; page++){
				$("#page"+page).html("");
				$("#page"+page).html("<center><img src='http://"+<?php echo "'".$_SERVER["REMOTE_ADDR"]."'";?>+"/getMePartners/image/loader.gif'</center>");
			}
			$.getJSON({
				url : <?php echo "'/getMePartners/index.php?send='"; ?>+'&data='+data+'&order='+order,
				success : function(data){
					var p_size = data.length;
					var b = 0;
					var c = 0;
					for(var page = 0; page < pagesnbr; page++){
						$("#page"+page).html("");
					}
				    for (var name in data) {
				   		var DATA = JSON.parse(data[name]);
						date = new Date(DATA["event_time"]*1000);
						var date = date.toLocaleDateString();
						var event_id = DATA["id_event"];
						var user = DATA["lead_user"];
						var event_satus = DATA["statut"];
						var nbr_runners = DATA["nbr_runners"];
						var lead_user_name = DATA['username'];
					    var lead_user_pic = DATA['profil_pic'];
					    var lat = DATA["latStart"];
					    var lon = DATA["lonStart"];
						var event_addr = DATA["addr_Start"];
						var messagesParPage = 4; 
						var nombreDePages = Math.ceil(p_size/messagesParPage);
						str = write_event(b,c,lead_user_pic,event_satus,nbr_runners,lead_user_name,date,event_addr,event_id);
						$("#page"+c).append(str);
						b++;
						if(b % 4 == 0){
							c++;
						}
					}
				},
				error:function(){
					for(var page = 0; page < pagesnbr; page++){
						$("#page"+page).html("");
					}
				},
				complete:function(){

				}
			});
		});

		$("#author_order").click(function(){
			data = $(this).attr('id');
			classe = $(this).children('div').attr('class');
			order = classe.substr(8);
			if (order == "up"){
				$(this).children('div').attr("class","chevron-down");
			}
			else if (order == "down"){
				$(this).children('div').attr("class","chevron-up");
			}
			var pagesnbr = <?php echo "$nombreDePages";?>;
			for(var page = 0; page < pagesnbr; page++){
				$("#page"+page).html("");
				$("#page"+page).html("<center><img src='http://"+<?php echo "'".$_SERVER["REMOTE_ADDR"]."'";?>+"/getMePartners/image/loader.gif'</center>");
			}
			$.getJSON({
				url : <?php echo "'/getMePartners/index.php?send='"; ?>+'&data='+data+'&order='+order,
				success : function(data){
					var p_size = data.length;
					var b = 0;
					var c = 0;
					for(var page = 0; page < pagesnbr; page++){
						$("#page"+page).html("");
					}
				    for (var name in data) {
				   		var DATA = JSON.parse(data[name]);
						date = new Date(DATA["event_time"]*1000);
						var date = date.toLocaleDateString();
						var event_id = DATA["id_event"];
						var user = DATA["lead_user"];
						var event_satus = DATA["statut"];
						var nbr_runners = DATA["nbr_runners"];
						var lead_user_name = DATA['username'];
					    var lead_user_pic = DATA['profil_pic'];
					    var lat = DATA["latStart"];
					    var lon = DATA["lonStart"];
						var event_addr = DATA["addr_Start"];
						var messagesParPage = 4; 
						var nombreDePages = Math.ceil(p_size/messagesParPage);
						str = write_event(b,c,lead_user_pic,event_satus,nbr_runners,lead_user_name,date,event_addr,event_id);
						$("#page"+c).append(str);
						b++;
						if(b % 4 == 0){
							c++;
						}
					}
				},
				error:function(){
					for(var page = 0; page < pagesnbr; page++){
						$("#page"+page).html("");
					}
				},
				complete:function(){

				}
			});
		});

		$("#date_order").click(function(){
			data = $(this).attr('id');
			classe = $(this).children('div').attr('class');
			order = classe.substr(8);
			if (order == "up"){
				$(this).children('div').attr("class","chevron-down");
			}
			else if (order == "down"){
				$(this).children('div').attr("class","chevron-up");
			}
			var pagesnbr = <?php echo "$nombreDePages";?>;
			for(var page = 0; page < pagesnbr; page++){
				$("#page"+page).html("");
				$("#page"+page).html("<center><img src='http://"+<?php echo "'".$_SERVER["REMOTE_ADDR"]."'";?>+"/getMePartners/image/loader.gif'</center>");
			}
			$.getJSON({
				url : <?php echo "'/getMePartners/index.php?send='"; ?>+'&data='+data+'&order='+order,
				success : function(data){
					var p_size = data.length;
					var b = 0;
					var c = 0;
					for(var page = 0; page < pagesnbr; page++){
						$("#page"+page).html("");
					}
				    for (var name in data) {
				   		var DATA = JSON.parse(data[name]);
						date = new Date(DATA["event_time"]*1000);
						var date = date.toLocaleDateString();
						var event_id = DATA["id_event"];
						var user = DATA["lead_user"];
						var event_satus = DATA["statut"];
						var nbr_runners = DATA["nbr_runners"];
						var lead_user_name = DATA['username'];
					    var lead_user_pic = DATA['profil_pic'];
					    var lat = DATA["latStart"];
					    var lon = DATA["lonStart"];
						var event_addr = DATA["addr_Start"];
						var messagesParPage = 4; 
						var nombreDePages = Math.ceil(p_size/messagesParPage);
						str = write_event(b,c,lead_user_pic,event_satus,nbr_runners,lead_user_name,date,event_addr,event_id);
						$("#page"+c).append(str);
						b++;
						if(b % 4 == 0){
							c++;
						}
					}
				},
				error:function(){
					for(var page = 0; page < pagesnbr; page++){
						$("#page"+page).html("");
					}
				},
				complete:function(){

				}
			});
		});

		$("#location_order").click(function(){
			data = $(this).attr('id');
			classe = $(this).children('div').attr('class');
			console.log($(this).children('div').attr('class'));
			order = classe.substr(8);
			if (order == "up"){
				$(this).children('div').attr("class","chevron-down");
			}
			else if (order == "down"){
				$(this).children('div').attr("class","chevron-up");
			}
			var pagesnbr = <?php echo "$nombreDePages";?>;
			for(var page = 0; page < pagesnbr; page++){
				$("#page"+page).html("");
				$("#page"+page).html("<center><img src='http://"+<?php echo "'".$_SERVER["REMOTE_ADDR"]."'";?>+"/getMePartners/image/loader.gif'</center>");
			}
			$.getJSON({
				url : <?php echo "'/getMePartners/index.php?send='"; ?>+'&data='+data+'&order='+order,
				success : function(data){
					var p_size = data.length;
					var b = 0;
					var c = 0;
					for(var page = 0; page < pagesnbr; page++){
						$("#page"+page).html("");
					}
				    for (var name in data) {
				   		var DATA = JSON.parse(data[name]);
						date = new Date(DATA["event_time"]*1000);
						var date = date.toLocaleDateString();
						var event_id = DATA["id_event"];
						var user = DATA["lead_user"];
						var event_satus = DATA["statut"];
						var nbr_runners = DATA["nbr_runners"];
						var lead_user_name = DATA['username'];
					    var lead_user_pic = DATA['profil_pic'];
					    var lat = DATA["latStart"];
					    var lon = DATA["lonStart"];
						var event_addr = DATA["addr_start"];
						var messagesParPage = 4; 
						var nombreDePages = Math.ceil(p_size/messagesParPage);
						str = write_event(b,c,lead_user_pic,event_satus,nbr_runners,lead_user_name,date,event_addr,event_id);
						$("#page"+c).append(str);
						b++;
						if(b % 4 == 0){
							c++;
						}
					}
				},
				error:function(){
					for(var page = 0; page < pagesnbr; page++){
						$("#page"+page).html("");
					}
				},
				complete:function(){

				}
			});
		});

		$(document).on('click', '.join-event', function () {
			$(this).parent().parent().parent().parent().addClass('removed-item');
			$(this).parent().parent().parent().parent().fadeOut();
			id = $(this).attr("data-event");
			$.getJSON({
				url : '/getMePartners/index.php?delete='+id,
				success : function(data){
						var items = [];
  						$.each( data, function( key, val ) {
    							items.push( val );
  						});
  						setTimeout(function(){console.log(items)}, 1000);	
				},
				error:function(data){
						var items = [];
  						$.each( data, function( key, val ) {
    							items.push( val );
  						});
  						setTimeout(function(){console.log(items)}, 1000);	
				},
				complete:function(){

				}
			});
		});
		var iditi = "";

		$(document).on('click', '.chat-button', function () {
			$("#chat_container").show();
			iditi = $(this).parent().parent().find(".join-event").attr("data-event");
			$.getJSON({
				url : '/getMePartners/index.php?chat=&e='+iditi,
				success : function(data){
					$("#all_chat_message").html("");
					for(message in data){
						var DATA = JSON.parse(data[message]);
    					$("#all_chat_message").append("<div class='chat_msg'><div class='chat-name'>"+DATA['username']+"</div><div class='chat-time'>"+moment(new Date(DATA["time"]*1000)).locale('fr').format("YYYY-MM-DD hh:mm:ss")+"</div><div class='chat-text'>"+DATA['text']+"</div></div>");
					}
					$("#all_chat_message").scrollTop($("#all_chat_message").prop('scrollHeight')); 
				},
				error:function(data){
				},
				complete:function(){
				}
			});
			setInterval(function(){ getCurentChat(iditi); console.log("requesting new messages");}, 3000);
		});

		$(document).on('click', '#chat-close-button', function () {
			$("#chat_container").hide();
		});

		$("#chat_container").draggable();

		$("#chatt").submit(function(e){
			e.preventDefault();
			msg = $("#text_chat_input").val();
			$("#all_chat_message").append("<div class='chat_msg'><div class='chat-name'>"+'<?php  echo $user->username;?>'+"</div><div class='chat-time'>"+moment(new Date()).locale('fr').format("YYYY-MM-DD hh:mm:ss")+"</div><div class='chat-text'>"+msg+"</div></div>");
			$("#all_chat_message").scrollTop($("#all_chat_message").prop('scrollHeight'));
			$.getJSON({
				url : '/getMePartners/index.php?chat=&e='+iditi+"&m="+msg,
				success : function(data){
				},
				error:function(data){
				},
				complete:function(){
				}
			});
		});

		function getCurentChat(iditi){
			$.getJSON({
				url : '/getMePartners/index.php?chat=&e='+iditi,
				success : function(data){
					$("#all_chat_message").html("");
					for(message in data){
						var DATA = JSON.parse(data[message]);
    					$("#all_chat_message").append("<div class='chat_msg'><div class='chat-name'>"+DATA['username']+"</div><div class='chat-time'>"+moment(new Date(DATA["time"]*1000)).locale('fr').format("YYYY-MM-DD hh:mm:ss")+"</div><div class='chat-text'>"+DATA['text']+"</div></div>");
					}
					$("#all_chat_message").scrollTop($("#all_chat_message").prop('scrollHeight')); 
				},
				error:function(data){
				},
				complete:function(){
				}
			});
		}

	});
</script>
<?php
// pages et contenu
echo("<div class='page' id='page$c' >");

for ($b = 0; $b < $p_size ;$b++) {
	$event = $user->myEvents[$b];
	$author = $user->getUserById($event->lead_user, $bdd);
	if($b % $messagesParPage == 0 && $b != 0){
		$c++;
		
		echo("</div>");
		echo("<div class='page' id='page$c' style='display:none;'>");
	} 	
?>
			 		<!-- events list-->
	<div class="event-container" >
		<div class="event-content">
			<div class="event-author-pic">
				<?php 	if($author['profil_pic'] != null){ 
							echo '<img src="'."http://".$_SERVER["SERVER_NAME"].'/getMePartners/'.$author['profil_pic'].'" style="height:100px;width:100px;">'; 
						}
						else{
							echo '<img src="./image/info.jpg" style="height:100px;width:100px;">';
						}
				?>
			</div>		
			<div class="event-text">
				<label>Statut : </label><h5>
				<?php
				switch ($event->statut) {
						case 0:
							echo "Non commencé : <img src='./image/waiting.jpg' style='height:10px;width:10px;'></h5>";
							break;
						case 1:
							echo "En cours : <img src='./image/on.jpg' style='height:10px;width:10px;'></h5>";
							break;
						case 10:
							echo "Course fini : <img src='./image/end.jpg' style='height:10px;width:10px;'></h5>";
							break;
						case 11:
							echo "Course annulée : <img src='./image/cancel.jpg' style='height:10px;width:10px;'></h5>";
							break;
						default:
							echo "Pas de status definit</h5>";
							break;
				}
				if($event->nbr_runners < 10 && $event->nbr_runners >= 1){echo '<center><button class="join-event btn" data-event="'.$event->id.'"><i class="fa fa-trash" aria-hidden="true"></i>&nbspQuitter</button></center>';}else{ echo '<div>La course est pleine</div>';echo '<center><button class="join-event btn" data-event="'.$event->id.'"><i class="fa fa-trash" aria-hidden="true"></i>&nbspQuitter</button></center>';}
				?>
				
			</div>
			<div class="event-text">
				<label>Auteur : </label><h5>
					<?php
						if($author['username'] != null){echo '<center>'.$author['username'].'</center>';}else{echo "Pas de nom définit";}
					?>
				</h5>
			</div>
			<div class="event-text">
				<label>Date de l'événement : </label><h5 class="date">
					<?php
						if($event->event_time != 0){echo strftime("%A %d %B %Y",$event->event_time);}else{ echo "Pas de date définit";}
					?>
				</h5>
			</div>
			<div class="event-text">
				<label>Lieu de l'événement : </label><h5>
					<?php
						if($addr = $event->addr_start){ echo $addr;}else{echo "Pas d'adresse definit";}
					?>
				</h5>
			</div> 
			<div class="chat-btn" >
				<button class="chat-button btn"><i class="fa fa-comment-o" aria-hidden="true"></i>&nbspchattez!</button>
			</div>
			<div class="event-pic">
				<img src="http://www.developpez.net/forums/attachments/p166896d1421856637/java/general-java/java-mobiles/android/integrer-personnaliser-carte-type-google-maps/googlemap.gif/" style="">
				<?php echo '<a class="event-info" href="#" title="info"><img src="./image/zoom.png" style="height:50px;margin:25px;" data-event="'.$event->id.'" data-toggle="modal" data-target="#myModal"></a>'; ?>
			</div>
		</div>		
	</div>
	<!-- fin events list-->
<?php

}

?> 

</div>
<script type="text/javascript" src="js/functions_modal.js"></script>

<!-- modal for event detail -->

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Detail du trajet de la course</h4>
      </div>
      <div class="modal-body">
        	<center><p id="modal-title"></p></center>
    		<div id="map" style="width:500px;height:350px;"></div>
    		<div id="panel"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<!-- END Modal -->

<!-- chat -->
<div id="chat_container">
	<div><i class="fa fa-times" aria-hidden="true" id="chat-close-button" style="float:right;color:red;margin-top:5px;"></i></div>
	<div id="chat_content">
		<div id="all_chat_message">
		</div>
		<form id="chatt">
			<input type="textarea" name="chat-message" id="text_chat_input" required></input>
			<input type="submit" name="sub-chat" id="btn_chat_input" class="btn"></input>
		</form>
	</div>
</div>
<!-- END chat -->