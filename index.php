<?php
require_once __DIR__.'/config.php';


$redirectUrl = APP_URL;

$permissions = ['email'];
$loginUrl    = $helper->getLoginUrl($redirectUrl, $permissions);
if(isset($_SESSION['fb']['access_token']) && !empty($_SESSION['fb']['access_token'])) {
    $cilent = $fb->getOAuth2Client();
    $accessToken = $_SESSION['fb']['access_token'];
    $tokenMetadata = $cilent->debugToken($accessToken);
    try {
        $tokenMetadata->validateAppId(FB_APP_ID);
        $tokenMetadata->validateExpiration();
    } catch (\Facebook\Exceptions\FacebookSDKException $e) {
        header('Location: '.$loginUrl);
        exit;
    }
    header('Location: home.php');
    exit;
}

try {
    $accessToken = $helper->getAccessToken();
} catch(\Facebook\Exceptions\FacebookSDKException $e) {
    echo $e->getMessage();
    exit;
}
if (isset($accessToken)) {
    $cilent = $fb->getOAuth2Client();
    try {
        $longLived = $cilent->getLongLivedAccessToken($accessToken);
    } catch(\Facebook\Exceptions\FacebookSDKException $e) {
        echo $e->getMessage();
        exit;
    }
    try {
        $response = $fb->get('/me?fields=id,name', $longLived->getValue());
        
    } catch(\Facebook\Exceptions\FacebookResponseException $e) {
        echo 'Graph returned an error: ' . $e->getMessage();
        exit;
    } catch(\Facebook\Exceptions\FacebookSDKException $e) {
        echo 'Facebook SDK returned an error: ' . $e->getMessage();
        exit;
    }
    $graphUser = $response->getGraphUser();
    $data = [];
    $data['fb_user_id'] = $graphUser->getId();
    $data['name'] = $graphUser->getName();
    $data['token'] = $longLived->getValue();
    $data['expires_at'] = $longLived->getExpiresAt();
    $data['is_active'] = 1;
    $user =  \FB\Helper::findOrCreateUser($data);
    
    if($user === false) {
        echo 'Oops! We are unable to save your record';
        exit;
    }
    $a = [
            'access_token' =>$longLived->getValue(),
            'expires_at' => $longLived->getExpiresAt(),
            'user_id' => $graphUser->getId()
        ];
    $_SESSION['fb'] = $a;
    header('Location: home.php');
    exit;
} elseif ($helper->getError()) {
    exit;
} else {
    header('Location: '.$loginUrl);
    exit;
}