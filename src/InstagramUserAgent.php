<?php namespace Riedayme\InstagramKit;

class InstagramUserAgent
{

	const USERAGENT = [
	'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.163 Safari/537.36'
	];

	public static function Generate()
	{
		$randomIdx = array_rand(self::USERAGENT, 1);

		return self::USERAGENT[$randomIdx];
	}

	public static function GenerateStatic()
	{
		return "Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.163 Safari/537.36";
	}
}