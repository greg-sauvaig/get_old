<div id="maile">
<?php

if($a != "no"){
    echo('<script type="text/javascript">$(document).ready(function(){$("#maile").slideDown(4000).slideUp(4000);});</script>');
    echo($a);
}
?>
</div>
<div class="col-lg-1 col-md-1 col-xs-1 col-sm-1"></div>
<div class="col-lg-9 col-md-9 col-xs-9 col-sm-9" >
    <form action="" method="post" id="mapform">
        <center><div class="row"  >
            <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6">
                <label for="run_start">Lieu de depart</label>
                <input type="text" id="start" name="addrStart" style="width:300px;"  id="origin">
            </div>
            <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6">
                <label for="run_date">Date de depart</label>
                <input id="date" data-form="DD-MM-YYYY" data-template="DD MM YYYY" name="run_date" value="01-01-2016" required >
            </div>
            </div>
        </center>
        <center><div class="row" >
            <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6">
                <label for="run_">Lieu d'arrivée</label>
                <input type="text" name="addrEnd" id="end" style="width:300px;" id="destination">
            </div>
            <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6">
                <label for="run_end">Heure de départ de la course</label>
                <input id="time" data-format="HH:mm" data-template="HH : mm" name="run_time" type="text" required>
            </div>
            </div>
            <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6">
                <label for="maxRunners">Nb max de Participants</label>
                <input type="number" name="maxRunners" min="2" max="50" required>                
            </div>
            <input type="text" placeholder="Titre de l'Event" name="event_name" required>
            <input type="hidden" data-start="lat" name="lat_Start" id="lat_Start" required>
            <input type="hidden" data-start="lng" name="lng_Start" id="lng_Start" required>
            <input type="hidden" data-end="lat" name="lat_End" id="lat_End" required>
            <input type="hidden" data-end="lng" name="lng_End" id="lng_End" required>
            <input type='hidden' name='runDistance' id='runDistance' value="" required>
            <input type="submit" name="create_event" class="btn btn-default">
        </center>
    </form>
    <button id="recall" class="btn btn-default" onclick="calculate();">Recalculer le trajet</button>

    <div id="panel"></div>
    <div id="map"></div>
</div>
<script type="text/javascript" src="js/functions.js"></script>