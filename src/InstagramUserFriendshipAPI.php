<?php namespace Riedayme\InstagramKit;

Class InstagramUserFriendshipAPI
{

	public $cookie;	

	public function SetCookie($data) 
	{
		$this->cookie = $data;
	}

	public function Process($userid)
	{         

		$url = 'https://i.instagram.com/api/v1/friendships/show/'.$userid;

		$access = InstagramHelperAPI::curl($url, false , false, $this->cookie, InstagramUserAgentAPI::GenerateStatic());

		$response = json_decode($access['body'],true);

		if ($response['status'] == 'ok') {
			return [
			'status' => true,
			'response' => $response
			];
		}else{
			return [
			'status' => false,
			'response' => $access['body']
			];
		}	
	}

}