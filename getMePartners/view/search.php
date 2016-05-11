<div class="col-lg-9 col-md-9 col-xs-9 col-sm-9" style="max-height:80vh;">
    <form action="" method="post" id="mapform">    
        <div class="col-lg-4 col-md-4 col-xs-4 col-sm-4">
            <label for="searchFrom">Search Events from ?</label>
            <input type="text" id="searchFrom" name="searchFrom">
        </div>
        <div class="col-lg-4 col-md-4 col-xs-4 col-sm-4">
            <label for="searchRadius">Select Search Radius</label>
            <input <?php if (isset($_POST['searchRadius'])){echo 'data-radius="'.$_POST['searchRadius'].'"';}?> type="range" id="searchRadius" name="searchRadius" max="50" min="1"></input>
        </div>
        <input <?php if (isset($_POST['lat_Search'])){echo 'data-lat="'.$_POST['lat_Search'].'"';}?> type="hidden" data-search="lat" name="lat_Search" id="lat_Search" required>
        <input <?php if (isset($_POST['lng_Search'])){echo 'data-lon="'.$_POST['lng_Search'].'"';}?> type="hidden" data-search="lng" name="lng_Search" id="lng_Search" required>
        <div class="col-lg-4 col-md-4 col-xs-4 col-sm-4">
            <input type="submit" value="search" name="search"></input>
        </div>
    </form>
</div>
<script type="text/javascript" src="./js/search.js"></script>

<?php
if (isset($eventList)){
$p = $eventList;
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
        echo("$('#btn".$e."').css('border-bottom','1px solid black');");
        echo("$('#btn".$e."').css('z-index','1');");
    }
    echo("$('#page".$i."').show();");
    echo("$('#btn".$i."').css('background','grey');");
    echo("$('#btn".$i."').css('border-bottom','0px');");
    echo("$('#btn".$i."').css('z-index','1000');");
    echo("});});</script>");
}
echo('<script type="text/javascript">$(document).ready(function(){$("#btn0").click();});</script>'); 
echo('<div id="listContainer">');
// bouton pour les pages
echo('<div id="btn-page-container">');
for ($i=0; $i < $nombreDePages ; ++$i) {
    echo("<div class='btn-page' id='btn$i' ><center>".($i+1)."</center></div>");
}
echo('</div>');
// bouton pour les tris
echo('<div id="order_for_page_search">Trier par:
            <div class="first_order_search"><button id="author_order"><div class="chevron-up"></div>&nbspauteur</button></div>
            <button id="date_order"><div class="chevron-up"></div>&nbspdate</button>
            <button id="location_order"><div class="chevron-up"></div>&nbsplieu</button>
            <button id="nbUser_order"><div class="chevron-up"></div>&nbspParticipants</button>
     </div>');
?>
<script type="text/javascript">
    $(document).ready(function(){

        function write_event(b,c, lead_user_pic, event_satus, nbr_runners, max_runners, lead_user_name, date, event_addr, event_id){
            var str = '<div class="event-container" >'+'<div class="event-content">'+'<div class="event-author-pic">';
            if(lead_user_pic){
                str += '<img src="http://localhost/getMePartners/'+lead_user_pic+'" style="height:100px;width:100px;">';
            }else{
                str += '<img src="./image/info.jpg" style="height:100px;width:100px;">'; 
            }
            str += '</div><div class="event-text"><label>Statut : </label><h5>';
            if(event_satus == 0)
                str += "non commencé : <img src='./image/waiting.jpg' style='height:10px;width:10px;'></h5>";
            if(nbr_runners < max_runners)
                str += '<center><button class="voir-event btn" data-event="'+event_id+'">Voir</button></center>';
            str += '</div><div class="event-text"><label>Auteur : </label>';
            if(lead_user_name)
                str += '<h5><center>'+lead_user_name+'</center></h5>';
            str += '</div><div class="event-text"><label>Date de l\'evenement : </label><h5>';
            if(date != 0)
                str += date;
                str += '</h5></div><div class="event-text"><label>Lieu de l\'evenement : </label><h5>';
            if(event_addr)
                str += event_addr;
                str+= '</h5></div><div class="event-text"><label>Nb Participants</label><h5>'+nbr_runners+' / '+max_runners;
            if (nbr_runners == max_runners){                        
                str+= " (Complet)";
            }
            str+='</h5></div>';
            str+='<div class="event-pic"><img src="http://www.developpez.net/forums/attachments/p166896d1421856637/java/general-java/java-mobiles/android/integrer-personnaliser-carte-type-google-maps/googlemap.gif/" style=""><a class="event-info" href="#" title="info"><img src="./image/zoom.png" style="height:50px;margin:25px;" data-event="'+event_id+'" data-toggle="modal" data-target="#myModal"></a></div></div></div>';
            return str;
        }

        $("#nbUser_order").click(function(){
            data = $(this).attr('id');
            classe = $(this).children('div').attr('class');
            order = classe.substr(8);
            lattitude = $('#lat_Search').attr("data-lat");
            longitude = $('#lng_Search').attr("data-lon");
            radius = $('#searchRadius').attr("data-radius");
            if (order == "up"){
                $(this).children('div').attr("class","chevron-down");
            }
            else if (order == "down"){
                $(this).children('div').attr("class","chevron-up");
            }
            var pagesnbr = <?php echo "$nombreDePages";?>;
            for(var page = 0; page < pagesnbr; page++){
                $("#page"+page).html("");
                $("#page"+page).html("<center><img src='http://"+<?php echo "'".$_SERVER["SERVER_NAME"]."'";?>+"/getMePartners/image/loader.gif'</center>");
            }
            $.getJSON({
                url : '/getMePartners/index.php?search=&data='+data+'&order='+order+'&lat='+lattitude+'&lon='+longitude+'&radius='+radius,
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
                        var max_runners = DATA['max_runners'];
                        var lead_user_name = DATA['username'];
                        var lead_user_pic = DATA['profil_pic'];
                        var lat = DATA["latStart"];
                        var lon = DATA["lonStart"];
                        var event_addr = DATA["addr_start"];
                        var messagesParPage = 4; 
                        var nombreDePages = Math.ceil(p_size/messagesParPage);
                        str = write_event(b,c,lead_user_pic,event_satus,nbr_runners, max_runners,lead_user_name,date,event_addr,event_id);
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
            lattitude = $('#lat_Search').attr("data-lat");
            longitude = $('#lng_Search').attr("data-lon");
            radius = $('#searchRadius').attr("data-radius");
            if (order == "up"){
                $(this).children('div').attr("class","chevron-down");
            }
            else if (order == "down"){
                $(this).children('div').attr("class","chevron-up");
            }
            var pagesnbr = <?php echo "$nombreDePages";?>;
            for(var page = 0; page < pagesnbr; page++){
                $("#page"+page).html("");
                $("#page"+page).html("<center><img src='http://"+<?php echo "'".$_SERVER["SERVER_NAME"]."'";?>+"/getMePartners/image/loader.gif'</center>");
            }
            $.getJSON({
                url : '/getMePartners/index.php?search=&data='+data+'&order='+order+'&lat='+lattitude+'&lon='+longitude+'&radius='+radius,
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
                        var max_runners = DATA['max_runners'];
                        var lead_user_name = DATA['username'];
                        var lead_user_pic = DATA['profil_pic'];
                        var lat = DATA["latStart"];
                        var lon = DATA["lonStart"];
                        var event_addr = DATA["addr_start"];
                        var messagesParPage = 4; 
                        var nombreDePages = Math.ceil(p_size/messagesParPage);
                        str = write_event(b,c,lead_user_pic,event_satus,nbr_runners, max_runners,lead_user_name,date,event_addr,event_id);
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
            lattitude = $('#lat_Search').attr("data-lat");
            longitude = $('#lng_Search').attr("data-lon");
            radius = $('#searchRadius').attr("data-radius");
            if (order == "up"){
                $(this).children('div').attr("class","chevron-down");
            }
            else if (order == "down"){
                $(this).children('div').attr("class","chevron-up");
            }
            var pagesnbr = <?php echo "$nombreDePages";?>;
            for(var page = 0; page < pagesnbr; page++){
                $("#page"+page).html("");
                $("#page"+page).html("<center><img src='http://"+<?php echo "'".$_SERVER["SERVER_NAME"]."'";?>+"/getMePartners/image/loader.gif'</center>");
            }
            $.getJSON({
                url : '/getMePartners/index.php?search=&data='+data+'&order='+order+'&lat='+lattitude+'&lon='+longitude+'&radius='+radius,
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
                        var max_runners = DATA['max_runners'];
                        var lead_user_name = DATA['username'];
                        var lead_user_pic = DATA['profil_pic'];
                        var lat = DATA["latStart"];
                        var lon = DATA["lonStart"];
                        var event_addr = DATA["addr_start"];
                        var messagesParPage = 4; 
                        var nombreDePages = Math.ceil(p_size/messagesParPage);
                        str = write_event(b,c,lead_user_pic,event_satus,nbr_runners, max_runners,lead_user_name,date,event_addr,event_id);
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
            order = classe.substr(8);
            lattitude = $('#lat_Search').attr("data-lat");
            longitude = $('#lng_Search').attr("data-lon");
            radius = $('#searchRadius').attr("data-radius");
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
                url : '/getMePartners/index.php?search=&data='+data+'&order='+order+'&lat='+lattitude+'&lon='+longitude+'&radius='+radius,
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
                        var max_runners = DATA['max_runners'];
                        var lead_user_name = DATA['username'];
                        var lead_user_pic = DATA['profil_pic'];
                        var lat = DATA["latStart"];
                        var lon = DATA["lonStart"];
                        var event_addr = DATA["addr_start"];
                        var messagesParPage = 4; 
                        var nombreDePages = Math.ceil(p_size/messagesParPage);
                        str = write_event(b,c,lead_user_pic,event_satus,nbr_runners, max_runners,lead_user_name,date,event_addr,event_id);
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

        $(document).on('click', '.voir-event', function () {
           var id = $(this).attr('data-event');
           window.location = 'index.php?voir='+id;
        });

    });
</script>
<?php
// pages et contenu

if(gettype($p) === "array"){
    echo("<div class='search-page' id='page$c'>");

    for ($b = 0; $b < $p_size ;$b++) {
                $event = $eventList[$b];
                $author = array('lead_user' => $event['lead_user'], 'profil_pic' => $event['profil_pic'], 'username' => $event['username']);
        if($b % $messagesParPage == 0 && $b != 0){
            $c++;
            
            echo("</div>");
            echo("<div class='page' id='page$c' style='display:none;'>");
        }   
?>
                    <!-- events list-->
    <div class="event-container" id="search-container">
        <div class="event-content" id="search-event-content">
            <div class="event-author-pic">
                <?php
                    if($author['profil_pic'] != null)
                    { 
                        echo '<img src="'."http://".$_SERVER["SERVER_NAME"].'/getMePartners/'.$author['profil_pic'].'" style="height:100px;width:100px;">'; 
                    }   
                    else
                    {
                        echo '<img src="./image/info.jpg" style="height:100px;width:100px;">';
                    }
                ?>
            </div>
            <center>      
            <div class="event-text">
                <label>Statut : </label><h5>
                <?php
                switch ($event['statut']) {
                        case 0:
                            echo "non commencé : <img src='./image/waiting.jpg' style='height:10px;width:10px;'></h5>";
                            break;
                        case 1:
                            echo "en cours : <img src='./image/on.jpg' style='height:10px;width:10px;'></h5>";
                            break;
                        case 10:
                            echo "course fini : <img src='./image/end.jpg' style='height:10px;width:10px;'></h5>";
                            break;
                        case 11:
                            echo "course annulé : <img src='./image/cancel.jpg' style='height:10px;width:10px;'></h5>";
                            break;
                        default:
                            echo "pas de statut definis</h5>";
                            break;
                }
                if($event['nbr_runners'] < $event['max_runners']){
                    echo '<center><button class="voir-event btn" data-event="'.$event['event_id'].'">Voir</button></center>';
                }
                ?>
                
            </div>
            <div class="event-text">
                <label>Auteur : </label><h5>
                    <?php
                        if($author['username'] != null){echo '<center>'.$author['username'].'</center>';}else{echo "pas de nom définit";}
                    ?>
                </h5>
            </div>
            <div class="event-text">
                <label>Date de l'evenement : </label><h5>
                    <?php
                        if($event['event_time'] != 0){echo strftime("%A %d %B %Y",$event['event_time']);}else{ echo "pas de date définit";}
                    ?>
                </h5>
            </div>
            <div class="event-text">
                <label>Lieu de l'evenement : </label>
                <h5>
                    <?php
                        if($addr = $event['addr_start']){ echo $addr;}else{echo "pas d'adresse definit";}
                    ?>
                </h5>
            </div>
            <div class="event-text">
                <label>Nb Participants</label><h5>
                    <?php
                        echo $event['nbr_runners'], " / ", $event['max_runners'];
                        if ($event['nbr_runners'] == $event['max_runners']){                        
                            echo " (Complet)";
                        }
                    ?>
                </h5>
            </div>
            </center>
            <div class="event-pic">
                <img src="http://www.developpez.net/forums/attachments/p166896d1421856637/java/general-java/java-mobiles/android/integrer-personnaliser-carte-type-google-maps/googlemap.gif/" style="">
                <?php echo '<a class="event-info" href="#" title="info"><img src="./image/zoom.png" style="height:50px;margin:25px;" data-event="'.$event['event_id'].'" data-toggle="modal" data-target="#myModal"></a>'; ?>
            </div>
        </div>      
    </div>
    <!-- fin events list-->
<?php

        }
    }// if($eventList)
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