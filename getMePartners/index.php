<?php 
	error_reporting(E_ALL);
	ini_set("display_errors", 1);
    date_default_timezone_set('Europe/Paris');
    setlocale(LC_TIME, 'fr_FR.utf8','fra');
    
    //core
    require_once './core/psl-config.php';
    require_once './core/Db.php';
    //model
    require_once './model/User.php';
    require_once './model/Logs.php';
    require_once './model/Event.php';
    require_once './model/EventList.php';
    require_once './model/Session.php';
    require_once './model/Chat.php';

    $bdd = Db::dbConnect();
    $valid = Logs::sessionIsValid($bdd);
    global $a;
    $a = "no";
    if (isset($_COOKIE['getMePartners']) && $valid)
    {
        $user = new User($_COOKIE['getMePartners'], $bdd);
        $eventList = EventList::getAllEventsButMines($user->id, $bdd);
        if (isset($_GET['send'])){
            include_once("./view/get_event_by_order.php");
            return;
        }
        if(isset($_GET["get"])){
            include_once("./view/get_user_data.php");
            return;
        }
        if(isset($_GET["event"])){
            include_once("./view/get_event_data.php");
            return;
        }
        if(isset($_GET["delete"])){
            include_once("./view/delete_event_user.php");
            return;
        }
        if(isset($_GET["chat"])){
            include_once("./view/chat.php");
            return;
        }
    }

    //header
    include_once './view/header.php';

    //Views
    if (isset($_COOKIE['getMePartners']) && $valid)
    {
        $user = new User($_COOKIE['getMePartners'], $bdd);
        $eventList = EventList::getAllEventsButMines($user->id, $bdd);
        if(isset($_GET["setting"]) && $_GET["setting"] === "account_setting"){
            if(isset($_POST['upload']) && $_POST["upload"] != null){
                $user->uploadAvatar($user, $bdd);
            }
            else{
                include_once './view/account_setting.php';
                include_once 'view/footer.php'; 
                return;
            }
        }
        if(isset($_GET["voir"])){
            if(isset($_POST['joinEvent'])){
                $user->joinEvent($user->id, $_GET['voir'], $bdd);
            }
            $userList = User::getUsersByEventId($_GET['voir'], $bdd);
            include_once "./view/event_info.php"; 
        }else if(isset($_GET['page'])){
            if ($_GET['page'] == 'create' && !isset($_POST['create_event'])){
                include_once './view/left-container-profil.php';
                include_once './view/create_event.php';
            }else if ($_GET['page'] == 'search'){
                include_once './view/left-container-profil.php';
                include_once './view/search.php';
            }
        }else if(!isset($_GET['setting'])){
            include_once './view/left-container-profil.php';
            include_once './view/main_page.php';
        }
        else if (isset($_POST['create_event'])){
            $user->createEvent($bdd);
            include_once './view/left-container-profil.php';
            include_once './view/create_event.php';
        }
        else{
            include_once './view/left-container-profil.php';
            include_once './view/main_page.php';
        }
    }
    else if(isset( $_POST['login'])){
        Logs::login($_POST['email'], $_POST['pass'], $bdd);
        include_once './view/login_register.php';
    }   
    else if(isset($_POST['register'])){
        if(Logs::register($_POST['username'], $_POST['mail'], $_POST['pass'], $_POST['pass2'], $bdd)){
            include_once './view/login_register.php';
        }
        else{
            $a = Logs::$message;
            include_once './view/login_register.php';
        }
    }
    else if(isset($_POST['retrieve']) && isset($_POST['forgotten'])){
        $pass = Logs::genKeyPass();
        if(Logs::isUser($bdd, $_POST['forgotten'])){
            if(Logs::updatePass($bdd, $_POST['forgotten'], $pass)){
                if(Logs::smtpMailer($pass, $_POST['forgotten'])){
                    $a = "Un email récapitulatif vous a été adressé, il contient votre nouveau mot de passe, vous pouvez vous connecter dès à présent !";
                    include_once './view/login_register.php';
                }
            }
        }
    }else{
        include_once './view/login_register.php';
    }
    echo '</div>';

    //footer
    include_once 'view/footer.php'; 
?>