<?php namespace Riedayme\InstagramKit;


Class InstagramUserUnFollow
{

	Const URL = 'https://www.instagram.com/web/friendships/%s/unfollow/';

	public $cookie;
	public $csrftoken;
	public $useragent;		
	public $proxy;

	public function Required($data) 
	{

		if (!$data['cookie']) {
			die('Cookie Empty');
		}

		$this->cookie = $data['cookie'];
		$this->csrftoken = InstagramCookie::GetCSRFCookie($data['cookie']);

		if (!$data['useragent']) {
			$this->useragent = InstagramUserAgent::GenerateStatic();
		}else{
			$this->useragent = $data['useragent'];
		}

		if (!$data['proxy']) {
			$this->proxy = false;
		}else{
			$this->proxy = $data['proxy'];
		}

	}

	public function Process($userid)
	{

		$url = sprintf(self::URL,$userid);

		$headers = array();
		$headers[] = "User-Agent: ". $this->useragent;
		$headers[] = "X-Csrftoken: ".$this->csrftoken;
		$headers[] = "Cookie: ". $this->cookie;
		$headers[] = 'X-Instagram-Ajax: ';

		$access = InstagramHelper::curl($url, 'empty' , $headers , false, false, $this->proxy);

		$response = json_decode($access['body'],true);

		if ($response['status'] == 'ok') {
			return [
			'status' => true,
			'response' => 'success_unfollow',
			];
		}else{

			if (strpos($access['body'], 'DOCTYPE html')) {
				return [
				'status' => false,
				'response' => 'cookie_die'
				];
			}else{				
				return [
				'status' => false,
				'response' => $access['body']
				];
			}
		}
	}

}