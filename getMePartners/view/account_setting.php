<div id="error"></div>
<div id="profil-container" class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
	<div class="col-lg-1 col-md-1 col-xs-1 col-sm-1"></div>
	<div class="col-lg-5 col-md-5 col-xs-5 col-sm-5" id="left-setting-container">
		<h4 class="col-lg-12 col-md-12 col-xs-12 col-sm-12" >Mon Compte</h4>
		<div class="">Vous pouvez modifier vos parametres ici !</div>
<?php
	$at = get_object_vars ( $user ); // i know (^_^)...! 
	$a = 0;
			//var_dump($at);
	echo "<form action='' method='post' id='f0'>";
	foreach ($at as $key => $value) {
		if ($a >= 1 && $a < 4 && $a != 2 ){
			echo ("<label>".str_replace('_', '', $key)." : ");
					//	echo ("<br>");
			echo ("<input name='$a' type='text' placeholder='".$value."' value='".$value."' style='width:100%;padding:5px;height:40px;' required>"."</label>");
		}
		if ($a == 2 ){
			echo ("<label>".str_replace('_', '', $key)." : ");
					//	echo ("<br>");
			echo ("<input name='$a' type='password' placeholder='".$value."' value='".$value."' style='width:100%;padding:5px;height:40px;' required>"."</label>");
			echo ("<label> Confirmez le nouveau ".str_replace('_', '', $key)." : ");
					//	echo ("<br>");
			echo ("<input name='pass2' type='password' placeholder='".$value."' value='".$value."' style='width:100%;padding:5px;height:40px;' required>"."</label>");
		}
		if ($a == 4){
			echo ("<label>".str_replace('_', '', $key)." : ");
			        //echo ("</br>");
			echo ("<input id='date' data-format='YYYY-MM-DD' data-template='YYYY MM DD' name='".($a)."'  type='text' placeholder='".substr($value,0,-9)."' value='".substr($value,0,-9)."' style='width:100%;padding:5px;height:40px;' required>"."</label></br>");
		}
		$a++;
	}
	echo ("<label>adresse : ");
	echo ("<input id='adresse' name='5' type='text' placeholder='".$user->addr."' value='".$user->addr."' style='width:100%;padding:5px;height:40px;' required>"."</label></br>");
	echo "<input class='btn' type='submit' name='send-maj-profil' value='Mettre à jour'>";
	echo "</form>";
		// handling form validation 
	if(isset($_POST["send-maj-profil"], $_POST["1"], $_POST["2"], $_POST["3"], $_POST["4"], $_POST["pass2"], $_POST["5"])){
		$username = $_POST["1"];
		$password = $_POST["2"];
		$password2 = $_POST["pass2"];
		$email = $_POST["3"];
		$birthdate = $_POST["4"];
		$addr = $_POST["5"];
			//$pic = $_POST["6"];
		if($user->maj_profil($username, $password, $password2, $email, $birthdate, $addr, $bdd)){
			echo('<script type="text/javascript">$(document).ready(function(){$("#error").html("");$("#error").html("<center style=\'font-size:20px;padding:30px;\'>votre profil a bien été mit a jours</center>");});</script>');
			echo('<script type="text/javascript">$(document).ready(function(){$("#error").slideDown(4000).delay(1000).slideUp(4000);});</script>');
		}
		else{
			echo('<script type="text/javascript">$(document).ready(function(){$("#error").html("");$("#error").html("<center \'font-size:20px;padding:30px;\'>erreur</center>");});</script>');
			echo('<script type="text/javascript">$(document).ready(function(){$("#error").slideDown(4000).delay(1000).slideUp(4000);});</script>');
		}
	}
	
?>	    
	<fieldset >Changez votre photo de profil ici:		
		<form method="post" enctype="multipart/form-data" action="index.php/?setting=account_setting" id="f1">
			<p>
				<input id="imageField" type="file" name="fichier" size="30">
				<input type="submit" name="upload" value="Uploader">
			</p>
		</form>
	</fieldset>
	<script language="javascript" type="text/javascript">
	$(function () {
		$("#adresse").geocomplete({
        });
		$('#date').combodate();
		    $('#imageField').on('change', function (e) {
	    	var files = $(this)[0].files;
	    	if (files.length > 0) {
	            var file = files[0],
	            $image_preview = $('#picture');
	            $image_preview.attr('src', window.URL.createObjectURL(file));
	        }
	    });
	     $('input:text').on('change', function (e) {
	    	var value = $(this).val();
	    	var id = $(this).attr('name');
	    	if(id != 'pass2'){
	    		$('#'+id).html(value);
	    	}
	    });
	});
	</script>
	</div>
	<div class="col-lg-5 col-md-5 col-xs-5 col-sm-5" id="right-setting-container">
		<h4 class="col-lg-12 col-md-12 col-xs-12 col-sm-12">Aperçu avant validation</h4>
			<div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
	<?php if($user->profil_pic == null){echo '<img id="picture" src="./image/info.jpg" style="height:100px;width:100px;">';}else{echo '<img id="picture" src="'."http://".$_SERVER["SERVER_NAME"] ."/getMePartners/".$user->profil_pic.'" style="height:100px;">';}
	 ?>
	        </div>
	    <div class="row">
	        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
	            <h6>Nom d'utilisateur:</h6><h5 id='1' class='center-text'><?php  echo $user->username;?></h5>
	        </div>
	    </div>  
	    <div class="row">
	        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
	            <h6>Date d'anniversaire:</h6><h5 id="4" class='center-text'><?php if ($user->birthdate != "0000-00-00 00:00:00"){ echo $user->birthdate; }else{ echo("pas renseigné");} ?></h5>
	        </div>
	    </div>
	    <div class="row">
	        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
	            <h6>Adresse:</h6><h5 id="5" class='center-text'><?php if ($user->addr != null){ echo $user->addr; }else{ echo("pas renseigné");} ?> </h5>
	        </div>
	    </div>
	    <div class="row">
	        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
	            <h6>Email:</h6><h5 id='3' class='center-text'><?php if ($user->mail != null){ echo $user->mail; }else{ echo("pas renseigné");} ?> </h5>
	        </div>
	    </div>
	    <div class="row">
	        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
	            <h6>Mot de passe:</h6><h5 id='2' class='center-text'><?php if ($user->password != null){ echo $user->password; }else{ echo("pas renseigné");} ?> </h5>
	        </div>
	    </div>
	</div>
	<div class="col-lg-1 col-md-1 col-xs-1 col-sm-1"></div>
</div>