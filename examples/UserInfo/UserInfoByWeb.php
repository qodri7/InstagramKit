<?php  
require "../../vendor/autoload.php";

use Riedayme\InstagramKit\InstagramUserInfo;

$cookie = '1';

$username = 'relaxing.media'; 

$userinfo = new InstagramUserInfo();

$userinfo->Required([
	'cookie' => $cookie,
	'useragent' => false, //  false for auto genereate
	'proxy' => false // false for not use proxy 
	]);

$process = $userinfo->Process($username);

if (!$process['status']) {
	die($process['response']);
}

$id = $userinfo->GetID($process);
$postcount = $userinfo->GetPostCount($process);

echo $id.PHP_EOL;
echo $postcount;