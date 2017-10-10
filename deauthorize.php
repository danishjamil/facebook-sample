<?php
require_once __DIR__.'/config.php';

if(isset($_REQUEST['signed_request'])) {
    $signedRequestHandler = new \Facebook\SignedRequest(new \Facebook\FacebookApp(FB_APP_ID,FB_APP_SECRET), $_REQUEST['signed_request']);
    $userId = $signedRequestHandler->getUserId();
    if(!empty($userId)) {
        $user = \FB\Models\User::where('fb_user_id','=',$userId)->first();
        if($user != NULL) {
            $user->is_active = 0;
            $user->save();
        }
    }
}