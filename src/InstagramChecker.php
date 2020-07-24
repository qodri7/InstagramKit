<?php namespace Riedayme\InstagramKit;

Class InstagramChecker
{	

	public static function CheckLiveCookie($cookie){

		$userid = InstagramCookie::GetUIDCookie($cookie);

		if (empty($userid)) {

			return [
				'status' => false,
				'response' => 'No userid found'
			];			

		} 
		
		$userinfo = InstagramResourceUser::GetUserInfoByID($userid);

		if (!$userinfo['status']) {
			return [
				'status' => false,
				'response' => $userinfo['response']
			];
		}

		$url = 'https://www.instagram.com/'.$userinfo['response']['username'].'/?__a=1';

		$headers = array();
		$headers[] = "Cookie: ".$cookie;

		$access = InstagramHelper::curl($url, false , $headers, false, InstagramUserAgent::GenerateStatic());

		$result = json_decode($access['body']);

		if(is_object($result) AND $result->graphql->user->restricted_by_viewer === false){

			/* Result Success on explore/instagram-username-a=1.json */

			return [
				'status' => true,
				'response' => [
					'userid' => $userid,
					'username' => $userinfo['response']['username'],
					'photo' => $userinfo['response']['photo']
				]
			];

		}else{

			return [
				'status' => false,
				'response' => 'Cookie Die'
			];		

		}

	}	

	public static function IsIGTV($shortcode)
	{

		$url = 'https://www.instagram.com/p/'.$shortcode.'/?__a=1';

		$useragent = InstagramUserAgent::GenerateStatic();

		$access = InstagramHelper::curl($url, false , false, false, $useragent);

		$result = json_decode($access['body'],true);

		if(is_null($result)){
			return false;
		}elseif (empty($result['graphql']['shortcode_media']['product_type'])) {
			return false;
		}

		return ($result['graphql']['shortcode_media']['product_type'] == 'igtv') ? true : false;
	}

}