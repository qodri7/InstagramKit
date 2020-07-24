<?php  
require "../../vendor/autoload.php";

use Riedayme\InstagramKit\InstagramFeedHashtag;

$cookie = '';

$hashtag = 'nature';

$readfeed = new InstagramFeedHashtag();

$readfeed->Required([
  'cookie' => $cookie,
  'useragent' => false, //  false for auto genereate
  'proxy' => false // false for not use proxy 
  ]);

$cursor = false;
$all_data = array();
$count = 0;
$limit = 1;
do {
  
  $post = $readfeed->Process($hashtag,$cursor);

  if (!$post['status']) {
    echo $post['response'];
    break;
  }

  $data = $readfeed->Extract($post);

  $all_data = array_merge($all_data,$data);

  if ($post['cursor'] !== null) {
    $cursor = $post['cursor'];
  }else{
    $cursor = false;
  }

  $count = $count+1;
} while ($cursor !== false AND $count < $limit);


echo "<pre>";
var_dump($all_data);
echo "</pre>";