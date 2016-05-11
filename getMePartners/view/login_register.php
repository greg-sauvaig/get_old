
<div id="maile">
<?php

if($a != "no"){
    echo('<script type="text/javascript">$(document).ready(function(){$("#maile").slideDown(4000).slideUp(4000);});</script>');
    echo($a);
}
?>
</div>
<div id="retrieve">
	<div id="retrieve-content">
		<div id="retrieve-container">
			<div id="retrieve-wrapper">
				<div id="connect" class="form" align="center">
					<button id="retrieve-btn" style="top:0px;float:right">X</button>
					<h3>Mot de passe oublié ?</h3>
					<div class="hr"></div>
					<form action="index.php" role="form" method="post">
						<input type="text" name="forgotten" maxlength="255"  placeholder="Entrez votre émail" required>
						<input type="submit" name="retrieve" value="Envoyer" class="btn-submit" >
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<div  id="login-register-container">
	<div  id="login-container">
	    <div  id="login-content">
	        <form method="post"  action="index.php">
	            <div >
	                <label for="email">Adresse mail:</label>
	                <input type="email"  id="email" name="email" required>
	            </div>
	            <div >
	                <label for="pwd">Mot de passe:</label>
	                <input type="password" id="password" name="pass" required>
	            </div>
	            <input class="btn-submit" type="submit" name="login" value="Connexion">
	        </form>
	        <h1>Vous possedez un compte? connectez-vous!
	        <div id="forgotmdp"><h6 style="margin:0px;">Mot de passe oublié ? cliquez ici !</h6></div>
	    </div>
	</div>
    <div  id="register-container" >
    	<div id="register-content">
	        <form method="post" role="form" action="index.php">
	            <div class="form-group">
	                <label for="username">Nom utilisateur:</label>
	                <input type="text" id="username" name="username" required>
	            </div>
	            <div class="form-group">
	                <label for="mail">Adresse mail:</label>
	                <input type="email"  id="mail" name="mail" required>
	            </div>
	            <div class="form-group">
	                <label for="password">Mot de passe:</label>
	                <input type="password"  id="password" name="pass" required>
	            </div>
	            <div class="form-group">
	                <label for="password2">Répétez le mot de passe:</label>
	                <input type="password"  id="password2" name="pass2" required>
	            </div>
	            <input class="btn-submit" type="submit" name="register" value="Inscription">
	        </form>
	        <h1>Register Now ! It's Free !</h1>
        </div>
    </div>                
</div>
<style type="text/css">
body{
  	background:url("./image/runners.jpg");
  	background-size:100% 100%;
  	background-repeat: no-repeat;
}	

</style>
<script type="text/javascript">
	$("#retrieve").click(function(){
        $("#retrieve").hide();
        $("#retrieve-container").animate({'top': '-500px'}, 1);
    });
    $("#forgotmdp").click(function(){
        $("#retrieve").show();
        $("#retrieve-container").animate({'top': '0px'}, 500);
    });
    $("#retrieve-container").click(function(e){
        e.stopPropagation();
    });
    $("#retrieve-btn").click(function(){
        $("#retrieve").hide();
        $("#retrieve-container").animate({'top': '-500px'}, 1);
    });
</script>