<?php

abstract class Logs
{
	public static $message;

	private static function checkLogs($mail, $pswd, $bdd){
		try {
			$query = "CALL checkLogs('$mail', '$pswd')";
			$prepared = $bdd->prepare($query);
			$prepared->execute();
			if($prepared->rowCount() == 1){
				return True;
			}
		} catch (Exception $e) {
			echo "Error : ", $e->getMessage, "\n";
			return False;
		}
		return False;
	}

	public static function login($mail, $pswd, $bdd)
	{	
		if ($mail && $pswd && $mail != null && $pswd != null)
		{	
			if (self::checkLogs($mail, $pswd, $bdd))
			{
				Session::setSession($mail, $pswd, $bdd);
				self::$message = "Bienvenu sur Get Me Partners !";

			}else{
				self::$message = "Identifiants invalides."; //message d'erreur.
			}
		}else{
			self::$message = "Un ou plusieurs champs sont vides !"; //message d'erreur.
		}
	}
	

	public static function register($username, $mail, $pass, $pass2, $bdd)
	{
		if ($username && $mail && $pass && $pass2 && $username != null && $mail != null && $pass != null && $pass2 != null)
		{
			if ($pass === $pass2)
			{
				if (strlen($pass) >= 6)
				{
					try{
						$query = "CALL register('$username', '$pass', '$mail')";
						$prepared = $bdd->prepare($query);
						$prepared->execute();
						if ($prepared->rowCount() === 1)
						{
							self::smtpMailer($pass,$mail);
							//mail($mail, 'Inscription GET ME PARTNERS !', )	
							return true;
						}else{
							self::$message = "Un compte utilise déjà cette adresse mail"; //message d'erreur.
							return false;
						}
					}catch (Exception $e){
						echo "Error : ", $e->getMessage, "\n";
						return False;
					}
				}else{
					self::$message = "Le mot de passe doit faire 8 charactères minimum"; //message d'erreur.
					return false;
				} 
			}else{
				self::$message = "Les mots de passes ne correspondent pas."; //message d'erreur.
				return false;
			}
		}else{
			self::$message = "Un ou plusieurs champs sont vides !"; //message d'erreur.
			return false;
		}
	}

	public static function sessionIsValid($bdd){
		if (isset($_COOKIE['getMePartners'])) {
			$cookie = $_COOKIE['getMePartners'];
			if($cookie != null){
				try {
					$time = time();
					$query = "CALL sessionIsValid('$time','$cookie')";
					$prepared = $bdd->prepare($query);
					$prepared->execute();
					$res = $prepared->fetch(PDO::FETCH_ASSOC);
					if($res != null){
						return true;
					}else{
						return false;
					}
				} catch (Exception $e) {
					echo "Error : ", $e->getMessage, "\n";
					return false;
				}
			}else{
				return false;					
			}
		}else{
			return false;
		}
	}


	public static function genKeyPass(){
		$chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		$code = "";
		for ($i=0; $i < 10; $i++) { 
			$code .= $chars[rand(0,35)];
		}
		return $code;
	}

	public static function smtpMailer($pass,$login) {
		$from = "greg.sauvaigo@gmail.com";
		$from_name = "GetMePartners Registration";
		$subject = "GetMePartners Registration - Bienvenu sur GetMePartners";
		$body = '<div style="width:100%;height:100%">'.
		'<img src="cid:logo" style="width:100px;height:100px;float:right;"/>'.
		'<h2 style"font-size:40px;padding:20px;">Bienvenue sur le site de la ligue des sports de lorainne.</h2></br></br>'.
		'<div style="width:100%;background:#ddd;padding:20px;">'.
		'<div style="width:100%;padding:20px;">Vous pouvez des à présent vous connecter sur le site  à l\'adresse suivante http://'.$_SERVER["REMOTE_ADDR"].'/getMePartners/ </div></br></br>'.
		'<div style="width:100%;padding:20px;background:#eee;text-align:center;">voici votre login: <div style="background:#fff;">'.$login.'</div></div></br></br>'.
		'<div style="width:100%;padding:20px;padding:20px;background:#eee;text-align:center;">voici mot de passe: <div style="background:#fff;">'.$pass.'</div></div></br></br>'.
		'<div style="width:100%;padding:20px;">A bientot sur le site http://'.$_SERVER["REMOTE_ADDR"].'/getMePartners/ </div></br></br>'.
		'<div style="width:100%;padding:20px;margin-top 5%;font-style:italic;"></br>'.
		'ce mail est généré automatiquement, ne repondez pas à cette adresse , en cas de problème contactez le support à l\'adresse suivante : greg.sauvaigo@gmail.com'.
		'</div></br></br>'.
		'</div></br></br>'.
		'</div>';
		require_once('./lib/PHPMailer-master/PHPMailerAutoload.php');
	    $mail = new PHPMailer();  // Cree un nouvel objet PHPMailer
	    $mail->IsSMTP(); // active SMTP
	    $mail->SMTPDebug = 0;  // debogage: 1 = Erreurs et messages, 2 = messages seulement
	    $mail->SMTPAuth = true;  // Authentification SMTP active
	    $mail->SMTPSecure = 'ssl'; // Gmail REQUIERT Le transfert securise
	    $mail->Host = 'smtp.gmail.com';
	    $mail->Port = 465;
	    $mail->Username = MAILUSER;
	    $mail->Password = MAILPASS;
	    $mail->SetFrom($from, $from_name);
	    $mail->Subject = $subject;
	    $mail->IsHTML(true);
	    $mail->CharSet = 'UTF-8';
	    $mail->AddEmbeddedImage('./images/logo.png', 'logo', 'lsl.png'); 
	    $mail->Body = $body;
	    $mail->AddAddress($login);
	    if(!$mail->Send()) {
	    	global $a;
	    	$a = "le mail n'est pas partit!";
	    	return False;
	    } else {
	    	global $a;
	    	$a = "le mail est partit!";
	    	return true;
	    }
	}

	public static function isUser($bdd, $mail){
		try {
			$pdo = $bdd;
			$req = $pdo->prepare("SELECT `id` from `user` where `mail` = '$mail' ");
			$req->execute();
			$res =  $req->fetch();
			if($res[0] != null){
				return True;
			}
			else{
				return False;
			}
		} catch (Exception $e) {
			return False;
		}
	}

	public static function updatePass($bdd, $mail, $code){
		try {
			$pdo = $bdd;
			$req = $pdo->prepare("UPDATE `user` set `password` = '$code' where `mail` = '$mail' ");
			$req->execute();
			$res = $req->rowCount();
			if($res === 1){
				return True;
			}
			else{
				return False;
			}
		} catch (Exception $e) {
			return False;
		}
	}

}

?>