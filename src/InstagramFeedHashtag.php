<?php namespace Riedayme\InstagramKit;

/*
If you Using Feed Hashtag By Web All Data Not Showing, like a video url, album upload, username, other.
*/
Class InstagramFeedHashtag
{

	Const URL = 'https://www.instagram.com/graphql/query/?query_hash=bd33792e9f52a56ae8fa0985521d141d&variables=';
	Const QUERY = '{"tag_name":"%s","first":12,"after":"%s"}';

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

	public function Process($hashtag,$fetch_media_item_cursor = false)
	{

		$url = self::URL.urlencode(sprintf(self::QUERY,$hashtag,$fetch_media_item_cursor));

		$headers = array();
		$headers[] = "Cookie: ".$this->cookie;

		$access = InstagramHelper::curl($url, false , $headers, false, $this->useragent, $this->proxy);

		$response = json_decode($access['body'],true);

		if ($response['status'] == 'ok' AND $response['data']['hashtag']['edge_hashtag_to_media']['edges'] != null) {		

			$cursor = $response['data']['hashtag']['edge_hashtag_to_media']['page_info']['end_cursor'];

			return [
				'status' => true,
				'response' => $response,
				'cursor' => $cursor
			];

		}else{

			if ($response['status'] == 'ok') {

				return [
					'status' => false,
					'response' => 'no_post'
				];
			}

			return [
				'status' => false,
				'response' => $access['body']
			];
		}

	}

	public function Extract($response)
	{

		if (!$response['status']) return $response;

		$jsondata = $response['response'];
		$edges = $jsondata['data']['hashtag']['edge_hashtag_to_media']['edges'];

		$extract = array();
		foreach ($edges as $key => $postdata) {

			/* type not suggest feed becaouse this no id found */
			if ($postdata['node']['__typename'] != 'GraphSuggestedUserFeedUnit') {

				$id =  $postdata['node']['id'];
				$userid = $postdata['node']['owner']['id'];
				$code = $postdata['node']['shortcode'];
				$url = "https://www.instagram.com/p/{$code}/";
				$caption = (!empty($postdata['node']['edge_media_to_caption']['edges'])) ? $postdata['node']['edge_media_to_caption']['edges'][0]['node']['text'] : false;
				//$haslike = $postdata['node']['viewer_has_liked'];

				/* in this feed graphsidecar not showing data we disable get all media only get first media */
				if ($postdata['node']['__typename'] == 'GraphSidecar') {

					// $SideCarData = $postdata['node']['edge_sidecar_to_children']['edges'];

					// $media = array();
					// foreach ($SideCarData as $postsidecar) {

					// 	$sidecartype = ($postsidecar['node']['is_video'] == false) ? 'image' : 'video';
					// 	$sidecarmedia = ($sidecartype == 'image') ? $postsidecar['node']['display_url'] : $postsidecar['node']['video_url'];

					// 	$media[] = [
					// 	'media' => $sidecarmedia,
					// 	'type' => $sidecartype,
					// 	];
					// }

					$media = $postdata['node']['display_url'];

					$type = 'carousel';
				}elseif ($postdata['node']['__typename'] == 'GraphVideo') {

					/* in this feed graphvideo not showing data we disable get all media only get thumbnail media */
					// $media = $postdata['node']['video_url'];
					$media = $postdata['node']['display_url'];
					$type = 'video';
				}elseif ($postdata['node']['__typename'] == 'GraphImage') {
					$media = $postdata['node']['display_url'];
					$type = 'image';
				}

				$extract[] = [
					'id' => $id,
					'userid' => $userid,
					'code' => $code,
					'url' => $url,
					'type' => $type,
					'media' => $media,
					'caption' => $caption,
					//'haslike' => $haslike,
				];
			}

		}

		return $extract;
	}

}