<?php
$thisEvent = $user->getEventById($_GET['voir'], $bdd);
$leader = $userList[0];
var_dump($userList);
?>
<div class="col-lg-1 col-md-1 col-xs-1 col-sm-1"></div>

<div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
    <div class="row" id="leader">
        <div class="col-lg-12 col-md-12 col-xs-12 col-xs-12 event-container" style="height:14vh;border:1px solid black;padding:1vh; min-width:970px !important;">
            <div class="col-lg-2 col-md-2 col-xs-2 col-xs-2">
            <?php
                if($leader['profil_pic'] != null){ 
                            echo '<img src="'."http://".$_SERVER["SERVER_NAME"].'/getMePartners/'.$leader['profil_pic'].'" style="height:100px;width:100px;">'; 
                        }
                        else{
                            echo '<img src="./image/info.jpg" style="height:100px;width:100px;">';
                        }
            ?>
            </div>
            <div class="col-lg-1 col-md-1 col-xs-1 col-xs-1">
                <h5 style="line-height:50px;">Créateur: <br><?php echo $leader['username']; ?></h5>
            </div>
            <div class="col-lg-2 col-md-2 col-xs-2 col-xs-2">
                <h5 style="line-height:50px;"><?php echo $thisEvent['max_runners']; ?> Participants Max</h5>
                <div class="row" id="total">
                    <center><h5><?php echo $thisEvent['nbr_runners'], " / ", $thisEvent['max_runners'], " participants"?></h5></center>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-xs-3 col-xs-3" >
                <center><h5 style="line-height:50px;"><?php echo 'début : ', date("d-m-Y à H:i", $thisEvent['event_time']);?></h5></center>
            </div>
            <div class="col-lg-2 col-md-2 col-xs-2 col-xs-2" >
                <center><h5 style="line-height:50px;"><?php echo "lieu : ", $thisEvent['addr_start'];?></h5></center>
            </div>
            <div class="col-lg-2 col-md-2 col-xs-2 col-xs-2">
                <h5 style="font-size:230%;line-height:50px;"><?php echo $thisEvent['runDistance'];?></h5>
            </div>
            <?php 
            $isInEvent = false;
            for($x = 0; $x < count($userList); $x++) {
                if ($userList[$x]['user_id'] == $user->id) {
                    $isInEvent = true;
                }
            }
            if (($thisEvent['nbr_runners'] < $thisEvent['max_runners']) && ($isInEvent == false)){ ?>
            <div class="col-lg-2 col-md-2 col-xs-2 col-xs-2">
                <form method="POST" action="" id="joinEvent">
                    <input type="submit" value="rejoindre" name="joinEvent" class="btn btn-default"></input>
                </form>
            </div>
            <?php } ?>
        </div>
        <?php
            for ($i=1; $i < count($userList); $i++) { 
                echo '<div class="col-lg-12 col-md-12 col-xs-12 col-xs-12 event-container" style="height:14vh;border:1px solid black;padding:1vh; min-width:970px !important;">';
                echo '<div class="col-lg-2 col-md-2 col-xs-2 col-xs-2">';
                if($userList[$i]['profil_pic'] != null){ 
                    echo '<img src="'."http://".$_SERVER["SERVER_NAME"].'/getMePartners/'.$userList[$i]['profil_pic'].'" style="height:100px;width:100px;">'; 
                }
                else{
                    echo '<img src="./image/info.jpg" style="height:100px;width:100px;">';
                }
        ?>
            </div>
            <div class="col-lg-1 col-md-1 col-xs-1 col-xs-1">
                <h5 style="line-height:50px;">Créateur: <br><?php echo $leader['username']; ?></h5>
            </div>
            <div class="col-lg-2 col-md-2 col-xs-2 col-xs-2">
                <h5 style="line-height:50px;"><?php echo $thisEvent['max_runners']; ?> Participants Max</h5>
                <div class="row" id="total">
                    <center><h5><?php echo $thisEvent['nbr_runners'], " / ", $thisEvent['max_runners'], " participants"?></h5></center>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-xs-3 col-xs-3" >
                <center><h5 style="line-height:50px;"><?php echo 'début : ', date("d-m-Y à H:i", $thisEvent['event_time']);?></h5></center>
            </div>
            <div class="col-lg-2 col-md-2 col-xs-2 col-xs-2" >
                <center><h5 style="line-height:50px;"><?php echo "lieu : ", $thisEvent['addr_start'];?></h5></center>
            </div>
            <div class="col-lg-2 col-md-2 col-xs-2 col-xs-2">
                <h5 style="font-size:230%;line-height:50px;"><?php echo $thisEvent['runDistance'];?></h5>
            </div>
        <?php
            }
        ?>
    </div>

    <!-- Modal -->
    <div id="content-event" class="" role="dialog" style="display:inline-block;width:100%;">
      <div class="modal-dialog modal-lg" style="width:100%;">
        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Detail du trajet de la course</h4>
          </div>
          <div class="modal-body">
                <center><p id="modal-title"></p></center>
                <div id="map" style="width:80%;height:350px;"></div>
                <div id="panel"></div>
          </div>
        </div>
      </div>
    </div>

</div>
<script type="text/javascript" >
    $(function(){

initialize = function(){
    data = $(this).children().data('event');
    var latStart = DATA["latStart"];
    var lonStart = DATA["lonStart"];
    var latLng = new google.maps.LatLng(latStart, lonStart); // Correspond au coordonnées de Lille
    var myOptions = {
    zoom      : 10, // Zoom par défaut
    center    : latLng, // Coordonnées de départ de la carte de type latLng 
    mapTypeId : google.maps.MapTypeId.TERRAIN, // Type de carte, différentes valeurs possible HYBRID, ROADMAP, SATELLITE, TERRAIN
    maxZoom   : 20
};

map      = new google.maps.Map(document.getElementById('map'), myOptions);
panel    = document.getElementById('panel');
// Création de l'icône
var myMarkerImage = new google.maps.MarkerImage("image/runner.png");


var marker = new google.maps.Marker({
    position : latLng,
    map      : map,
    title    : "Moi",
    icon     :  myMarkerImage// Chemin de l'image du marqueur pour surcharger celui par défaut
});

var watchID = navigator.geolocation.watchPosition(function(position) {
                    marker.setPosition({lat : position.coords.latitude, lng :position.coords.longitude});
                    console.log("lat" + position.coords.latitude+ ","+ "lng"  + position.coords.longitude);
            });

var contentMarker = [
'<div id="containerTabs">',
'<div id="tabs">',
'<ul>',
'<li><a href="#tab-1"><span>Lorem</span></a></li>',
'<li><a href="#tab-2"><span>Ipsum</span></a></li>',
'<li><a href="#tab-3"><span>Dolor</span></a></li>',
'</ul>',
'<div id="tab-1">',
'<h3>Lille</h3><p>Suspendisse quis magna dapibus orci porta varius sed sit amet purus. Ut eu justo dictum elit malesuada facilisis. Proin ipsum ligula, feugiat sed faucibus a, <a href="http://www.google.fr">google</a> sit amet mauris. In sit amet nisi mauris. Aliquam vestibulum quam et ligula pretium suscipit ullamcorper metus accumsan.</p>',
'</div>',
'<div id="tab-2">',
'<h3>Aliquam vestibulum</h3><p>Aliquam vestibulum quam et ligula pretium suscipit ullamcorper metus accumsan.</p>',
'</div>',
'<div id="tab-3">',
'<h3>Pretium suscipit</h3><ul><li>Lorem</li><li>Ipsum</li><li>Dolor</li><li>Amectus</li></ul>',
'</div>',
'</div>',
'</div>'
].join('');

var infoWindow = new google.maps.InfoWindow({
    content  : contentMarker,
    position : latLng
});

google.maps.event.addListener(marker, 'click', function() {
    infoWindow.open(map,marker);
});

  google.maps.event.addListener(infoWindow, 'domready', function(){ // infoWindow est biensûr notre info-bulle
    jQuery("#tabs").tabs();
});
  
  
  direction = new google.maps.DirectionsRenderer({
    map   : map,
    panel : panel // Dom element pour afficher les instructions d'itinéraire
});

};

calculate = function(origin_lat,origin_lng,destination_lat,destination_lng){
    origin = new google.maps.LatLng(origin_lat, origin_lng);
    destination = new google.maps.LatLng(destination_lat, destination_lng);
    if(origin && destination){
        var request = {
            origin      : origin,
            destination : destination,
            travelMode  : google.maps.DirectionsTravelMode.WALKING // Mode de conduite
        }
        var directionsService = new google.maps.DirectionsService(); // Service de calcul d'itinéraire
        directionsService.route(request, function(response, status){ // Envoie de la requête pour calculer le parcours
            if(status == google.maps.DirectionsStatus.OK){
                direction.setDirections(response); // Trace l'itinéraire sur la carte et les différentes étapes du parcours
            }
        });
    }
};


$(document).on('click', '.event-info', function () {
    data = $(this).children().data('event');
    $.getJSON({
        url : "/getMePartners/index.php?event="+data,
        success : function(data){
            DATA = data;
            var latStart = DATA["latStart"];
            var lonStart = DATA["lonStart"];
            var latEnd = DATA["latEnd"];
            var lonEnd = DATA["lonEnd"];
            var name = DATA["name"];
            $('#panel').html("");
            calculate(latStart,lonStart,latEnd,lonEnd);
            
            initialize();
            $("#modal-title").html("");
            $("#modal-title").html(name);
        },
        error:function(){
          
        },
        complete:function(){

        }
    });
});

function feed_info(id){
        $.getJSON({
            url : "/getMePartners/index.php?event="+id,
            success : function(data){
                DATA = data;
                var latStart = DATA["latStart"];
                var lonStart = DATA["lonStart"];
                var latEnd = DATA["latEnd"];
                var lonEnd = DATA["lonEnd"];
                var name = DATA["name"];
                $('#panel').html("");
                calculate(latStart,lonStart,latEnd,lonEnd);
                
                initialize();
                $("#modal-title").html("");
                $("#modal-title").html(name);
            },
            error:function(){
              
            },
            complete:function(){

            }
        });
    }
    feed_info(<?php echo $_GET["voir"];?>);

});
</script>