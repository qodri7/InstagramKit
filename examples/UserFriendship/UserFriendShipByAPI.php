<?php  
require "../../vendor/autoload.php";

use Riedayme\InstagramKit\InstagramUserFriendshipAPI;

$datacookie = 'cookie';

// $userid = '9868652404'; // relaxing.media

$userid = '1931014527'; // fvrskyla private account

$friendships = new InstagramUserFriendshipAPI();
$friendships->SetCookie($datacookie);

$results = $friendships->Process($userid);

echo "<pre>";
var_dump($results);
echo "</pre>";

/*
array(2) {
  ["status"]=>
  bool(true)
  ["response"]=>
  array(12) {
    ["blocking"]=>
    bool(false)
    ["followed_by"]=>
    bool(false)
    ["following"]=>
    bool(true)
    ["incoming_request"]=>
    bool(false)
    ["is_bestie"]=>
    bool(false)
    ["is_blocking_reel"]=>
    bool(false)
    ["is_muting_reel"]=>
    bool(false)
    ["is_private"]=>
    bool(false)
    ["is_restricted"]=>
    bool(false)
    ["muting"]=>
    bool(false)
    ["outgoing_request"]=>
    bool(false)
    ["status"]=>
    string(2) "ok"
  }
}
*/