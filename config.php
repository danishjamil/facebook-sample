<?php
session_start();
date_default_timezone_set('UTC');

require __DIR__.'/vendor/autoload.php';
$protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://';
$url = $protocol.$_SERVER['HTTP_HOST'];

define('FB_APP_ID','1545268325511624');
define('FB_APP_SECRET','3b608e310b33c26a49f526bc6e26e946');
define('APP_URL',$url);

use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule;

$capsule->addConnection([
    'driver'    => 'mysql',
    'host'      => 'localhost',
    'database'  => 'facebook_sample',
    'username'  => 'facebook_sample',
    'password'  => 'facebook_sample',
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
]);

$capsule->bootEloquent();

$fb = new \Facebook\Facebook([
    'app_id' => FB_APP_ID,
    'app_secret' => FB_APP_SECRET,
    'default_graph_version' => 'v2.10',
    ]);
    
$helper = $fb->getRedirectLoginHelper();