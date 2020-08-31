<?php namespace Riedayme\InstagramKit;


Class InstagramUserFollowing
{

	public $cookie;	

	public function SetCookie($data) 
	{
		$this->cookie = $data;
		$this->csrftoken = InstagramCookie::GetCSRFCookie($data);
	}

	public function Process($userid,$fetch_media_item_cursor = false)
	{

		if ($fetch_media_item_cursor) {
			$variables = '{"id":"'. $userid .'","include_reel":true,"fetch_mutual":false,"first":50,"after":"'. $fetch_media_item_cursor .'"}';
			$url = 'https://www.instagram.com/graphql/query/?query_hash=d04b0a864b4b54837c0d870b0e77e076&variables='.urlencode($variables);
		}else{
			$variables = '{"id":"'. $userid .'","include_reel":true,"fetch_mutual":false,"first":50}';
			$url = 'https://www.instagram.com/graphql/query/?query_hash=d04b0a864b4b54837c0d870b0e77e076&variables='.urlencode($variables);
		}

		$headers = array();
		$headers[] = "User-Agent: ". InstagramUserAgent::GenerateStatic();
		$headers[] = "X-Csrftoken: ".$this->csrftoken;
		$headers[] = "Cookie: ". $this->cookie;

		$access = InstagramHelper::curl($url, false , $headers);

		$response = json_decode($access['body'],true);

		if ($response['status'] == 'ok' AND $response['data']['user']['edge_follow']['edges'] != null) {		

			$cursor = $response['data']['user']['edge_follow']['page_info']['end_cursor'];

			return [
				'status' => true,
				'response' => $response,
				'cursor' => $cursor
			];

		}else{

			if ($response['status'] == 'ok') {

				if ($response['data']['user']['edge_follow']['page_info']['has_next_page']) {

					$cursor = $response['data']['user']['edge_follow']['page_info']['end_cursor'];

					return [
						'status' => false,
						'response' => 'invalid_cursor',
						'cursor' => $cursor
					];
				}
				elseif ($response['data']['user']['edge_follow']['count'] > 0) {
					return [
						'status' => false,
						'response' => 'cookie_die'
					];
				}else{
					return [
						'status' => false,
						'response' => 'no_followers'
					];
				}
			}

			return [
				'status' => false,
				'response' => $access['body']
			];
		}

	}

	public function Extract($response){

		if (!$response['status']) return $response;

		$jsondata = $response['response'];
		$edges = $jsondata['data']['user']['edge_follow']['edges'];

		$extract = array();
		foreach ($edges as $node) {
			$user = $node['node'];
			$reel = $user['reel'];

			$extract[] = [
				'userid' => $user['id'],
				'username' => $user['username'],
				'photo' => $user['profile_pic_url'],
				'is_private' => $user['is_private'],
				'is_verified' => $user['is_verified'],
				'latest_reel_media' => $reel['latest_reel_media']
			];
		}

		return $extract;
	}

}