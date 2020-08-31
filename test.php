<?php  
if (file_exists('scripts/log/unb-faanteyki-notfollow.json')) {

	$userlist = file_get_contents('scripts/log/unb-faanteyki-notfollow.json');

	$explode = explode(PHP_EOL, $userlist);

	foreach ($explode as $user) {
		$extract = explode("|", $user);
		$userid = $extract[0];

		echo $userid;
	}
}	
?>