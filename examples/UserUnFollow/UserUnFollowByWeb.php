<?php  
require "../../vendor/autoload.php";

use Riedayme\InstagramKit\InstagramUserUnFollow;

$cookie = '';

$userid = '9868652404'; // relaxing.media

$unfollow = new InstagramUserUnFollow();

$unfollow->Required([
	'cookie' => $cookie,
	'useragent' => false, //  false for auto genereate
	'proxy' => false // false for not use proxy 
	]);

$results = $unfollow->Process($userid);

echo "<pre>";
var_dump($results);
echo "</pre>";