<!-- Profil-->
<div id="left_container_profil">
    <div id="left_content_profil">
        <div id="left-profil-pic">
    <?php if($user->profil_pic == null){echo '<img src="./image/info.jpg">';}else{echo '<img src="'."http://".$_SERVER["SERVER_NAME"] ."/getMePartners/".$user->profil_pic.'" >';}
     ?>
        </div>
        <div class="left-profil-text">
            <label>Nom:</br><?php  echo $user->username;?></label>
        </div>
        <div class="left-profil-text">
            <label>Date d'anniversaire:</br><?php if ($user->birthdate != "0000-00-00 00:00:00"){ echo $user->birthdate; }else{ echo("Pas renseigné");} ?></label>
        </div>
        <div class="left-profil-text">
            <label>Adresse:</br><?php if ($user->addr != null){ echo $user->addr; }else{ echo("Pas renseigné");} ?> </label>
        </div>
        <div id="btn-settings-container">
            <center><button id="btn-settings" class="btn btn-default"><i class="fa fa-cogs" aria-hidden="true"></i>&nbsp;Paramètre du compte</button></center>
        </div>
    </div>
</div>
    <script type="text/javascript">
        $(document).ready(function(){
            $("#btn-settings").click(function(){
                <?php echo("var serv = '" . $_SERVER["SERVER_NAME"] . $_SERVER["PHP_SELF"] . "';"); ?>
                var uri = "http://"+serv+"?setting=account_setting";
                var res = encodeURI(uri);
                window.location = uri;
            });
        });
    </script>
    <!-- Fin Profil-->