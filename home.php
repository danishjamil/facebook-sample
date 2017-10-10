<?php
require_once __DIR__.'/config.php';


if(!isset($_SESSION['fb'])) {
    header('Location: '.APP_URL);
    exit;
}else if(!isset($_SESSION['fb']['user_id'])) {
    session_destroy();
    header('Location: '.APP_URL);
    exit;
} else {
    $fb_user_id = $_SESSION['fb']['user_id'];
    $user = \FB\Models\User::where('fb_user_id','=',$fb_user_id)->where('is_active','=',1)->first();
    if(is_null($user)) {
        session_destroy();
        header('Location: '.APP_URL);
        exit;
    }
}

echo 'Name: '. $user->name;
echo '<br/>';
echo 'Picture : <img src="https://graph.facebook.com/v2.10/'.$user->fb_user_id.'/picture"/>';