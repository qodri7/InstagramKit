<?php namespace Riedayme\InstagramKit;


Class InstagramUserInfo
{

	Const URL = 'https://www.instagram.com/%s/?__a=1';

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

	public function Process($username)
	{

		$url = sprintf(self::URL,$username);

		$headers = array();
		$headers[] = "User-Agent: ". $this->useragent;
		$headers[] = "X-Csrftoken: ".$this->csrftoken;
		$headers[] = "Cookie: ". $this->cookie;

		$access = InstagramHelper::curl($url, false , $headers , false, false, $this->proxy);

		if (strpos($access['body'], 'DOCTYPE html')) {
			return [
			'status' => false,
			'response' => 'cookie_die'
			];
		}else{				

			$response = json_decode($access['body'],true);

			if (InstagramHelper::findKey('graphql', $response)) {
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

	public function GetID($response)
	{
		return $response['response']['graphql']['user']['id'];
	}

	public function GetPostCount($response)
	{
		return $response['response']['graphql']['user']['edge_owner_to_timeline_media']['count'];
	}
}