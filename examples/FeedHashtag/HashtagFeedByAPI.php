<?php  
require "../../vendor/autoload.php";

use Riedayme\InstagramKit\InstagramFeedHashtagAPI;

$cookie = '';

$hashtag = 'nature';

$readfeed = new InstagramFeedHashtagAPI();

$readfeed->Required([
  'cookie' => $cookie,
  'useragent' => false, //  false for auto genereate
  'proxy' => false // false for not use proxy 
  ]);

$next_id = false;
$all_data = array();
$count = 0;
$limit = 1;
do {
  
  $post = $readfeed->Process($hashtag,$next_id);

  if (!$post['status']) {
    echo $post['response'];
    break;
  }

  $data = $readfeed->Extract($post);

  $all_data = array_merge($all_data,$data);

  if ($post['next_id'] !== null) {
    $next_id = $post['next_id'];
  }else{
    $next_id = false;
  }

  $count = $count+1;
} while ($next_id !== false AND $count < $limit);


echo "<pre>";
var_dump($all_data);
echo "</pre>";