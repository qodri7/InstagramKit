<?php namespace Riedayme\InstagramKit;

Class InstagramUserAgentAPI
{

	const REQUIRED_ANDROID_VERSION = '2.2';

	const DEVICES = [
        /* OnePlus 3T. Released: November 2016.
         * https://www.amazon.com/OnePlus-A3010-64GB-Gunmetal-International/dp/B01N4H00V8
         * https://www.handsetdetection.com/properties/devices/OnePlus/A3010
         */
        '24/7.0; 380dpi; 1080x1920; OnePlus; ONEPLUS A3010; OnePlus3T; qcom',

        /* LG G5. Released: April 2016.
         * https://www.amazon.com/LG-Unlocked-Phone-Titan-Warranty/dp/B01DJE22C2
         * https://www.handsetdetection.com/properties/devices/LG/RS988
         */
        '23/6.0.1; 640dpi; 1440x2392; LGE/lge; RS988; h1; h1',

        /* Huawei Mate 9 Pro. Released: January 2017.
         * https://www.amazon.com/Huawei-Dual-Sim-Titanium-Unlocked-International/dp/B01N9O1L6N
         * https://www.handsetdetection.com/properties/devices/Huawei/LON-L29
         */
        '24/7.0; 640dpi; 1440x2560; HUAWEI; LON-L29; HWLON; hi3660',

        /* ZTE Axon 7. Released: June 2016.
         * https://www.frequencycheck.com/models/OMYDK/zte-axon-7-a2017u-dual-sim-lte-a-64gb
         * https://www.handsetdetection.com/properties/devices/ZTE/A2017U
         */
        '23/6.0.1; 640dpi; 1440x2560; ZTE; ZTE A2017U; ailsa_ii; qcom',

        /* Samsung Galaxy S7 Edge SM-G935F. Released: March 2016.
         * https://www.amazon.com/Samsung-SM-G935F-Factory-Unlocked-Smartphone/dp/B01C5OIINO
         * https://www.handsetdetection.com/properties/devices/Samsung/SM-G935F
         */
        '23/6.0.1; 640dpi; 1440x2560; samsung; SM-G935F; hero2lte; samsungexynos8890',

        /* Samsung Galaxy S7 SM-G930F. Released: March 2016.
         * https://www.amazon.com/Samsung-SM-G930F-Factory-Unlocked-Smartphone/dp/B01J6MS6BC
         * https://www.handsetdetection.com/properties/devices/Samsung/SM-G930F
         */
        '23/6.0.1; 640dpi; 1440x2560; samsung; SM-G930F; herolte; samsungexynos8890',

        /* New devices. Last update: October, 2019.
         * https://github.com/dilame/instagram-private-api/blob/3d495dd2930453b7745e84be199af1d740459180/src/samples/devices.json
         */
        '25/7.1.1; 440dpi; 1080x1920; Xiaomi; Mi Note 3; jason; qcom',
        '23/6.0.1; 480dpi; 1080x1920; Xiaomi; Redmi Note 3; kenzo; qcom',
        '23/6.0; 480dpi; 1080x1920; Xiaomi; Redmi Note 4; nikel; mt6797',
        '24/7.0; 480dpi; 1080x1920; Xiaomi/xiaomi; Redmi Note 4; mido; qcom',
        '23/6.0; 480dpi; 1080x1920; Xiaomi; Redmi Note 4X; nikel; mt6797',
        '27/8.1.0; 440dpi; 1080x2030; Xiaomi/xiaomi; Redmi Note 5; whyred; qcom',
        '23/6.0.1; 480dpi; 1080x1920; Xiaomi; Redmi 4; markw; qcom',
        '27/8.1.0; 440dpi; 1080x2030; Xiaomi/xiaomi; Redmi 5 Plus; vince; qcom',
        '25/7.1.2; 440dpi; 1080x2030; Xiaomi/xiaomi; Redmi 5 Plus; vince; qcom',
        '26/8.0.0; 480dpi; 1080x1920; Xiaomi; MI 5; gemini; qcom',
        '27/8.1.0; 480dpi; 1080x1920; Xiaomi/xiaomi; Mi A1; tissot_sprout; qcom',
        '26/8.0.0; 480dpi; 1080x1920; Xiaomi; MI 6; sagit; qcom',
        '25/7.1.1; 440dpi; 1080x1920; Xiaomi; MI MAX 2; oxygen; qcom',
        '24/7.0; 480dpi; 1080x1920; Xiaomi; MI 5s; capricorn; qcom',
        '26/8.0.0; 480dpi; 1080x1920; samsung; SM-A520F; a5y17lte; samsungexynos7880',
        '26/8.0.0; 480dpi; 1080x2076; samsung; SM-G950F; dreamlte; samsungexynos8895',
        '26/8.0.0; 640dpi; 1440x2768; samsung; SM-G950F; dreamlte; samsungexynos8895',
        '26/8.0.0; 420dpi; 1080x2094; samsung; SM-G955F; dream2lte; samsungexynos8895',
        '26/8.0.0; 560dpi; 1440x2792; samsung; SM-G955F; dream2lte; samsungexynos8895',
        '24/7.0; 480dpi; 1080x1920; samsung; SM-A510F; a5xelte; samsungexynos7580',
        '26/8.0.0; 420dpi; 1080x2094; samsung; SM-G965F; star2lte; samsungexynos9810',
        '26/8.0.0; 480dpi; 1080x2076; samsung; SM-A530F; jackpotlte; samsungexynos7885',
        '24/7.0; 640dpi; 1440x2560; samsung; SM-G925F; zerolte; samsungexynos7420',
        '26/8.0.0; 420dpi; 1080x1920; samsung; SM-A720F; a7y17lte; samsungexynos7880',
        '24/7.0; 640dpi; 1440x2560; samsung; SM-G920F; zeroflte; samsungexynos7420',
        '24/7.0; 420dpi; 1080x1920; samsung; SM-J730FM; j7y17lte; samsungexynos7870',
        '26/8.0.0; 480dpi; 1080x2076; samsung; SM-G960F; starlte; samsungexynos9810',
        '26/8.0.0; 420dpi; 1080x2094; samsung; SM-N950F; greatlte; samsungexynos8895',
        '26/8.0.0; 420dpi; 1080x2094; samsung; SM-A730F; jackpot2lte; samsungexynos7885',
        '26/8.0.0; 420dpi; 1080x2094; samsung; SM-A605FN; a6plte; qcom',
        '26/8.0.0; 480dpi; 1080x1920; HUAWEI/HONOR; STF-L09; HWSTF; hi3660',
        '27/8.1.0; 480dpi; 1080x2280; HUAWEI/HONOR; COL-L29; HWCOL; kirin970',
        '26/8.0.0; 480dpi; 1080x2032; HUAWEI/HONOR; LLD-L31; HWLLD-H; hi6250',
        '26/8.0.0; 480dpi; 1080x2150; HUAWEI; ANE-LX1; HWANE; hi6250',
        '26/8.0.0; 480dpi; 1080x2032; HUAWEI; FIG-LX1; HWFIG-H; hi6250',
        '27/8.1.0; 480dpi; 1080x2150; HUAWEI/HONOR; COL-L29; HWCOL; kirin970',
        '26/8.0.0; 480dpi; 1080x2038; HUAWEI/HONOR; BND-L21; HWBND-H; hi6250'
    ]; //close

    public static function randomDevices()
    {
    	$randomIdx = array_rand(self::DEVICES, 1);

    	return self::DEVICES[$randomIdx];
    }     

    public static function Build($device,$userLocale = 'en_US',$appVersion = '133.0.0.32.120',$version_code = '204019472')
    {

    	$user_agent_format = 'Instagram %s Android (%s/%s; %s; %s; %s; %s; %s; %s; %s; %s)';

	    // Build the appropriate "Manufacturer" or "Manufacturer/Brand" string.
    	$manufacturerWithBrand = $device['Manufacturer'];
    	if ($device['Brand'] !== null) {
    		$manufacturerWithBrand .= '/'.$device['Brand'];
    	}

        // Generate the final User-Agent string.
    	return sprintf(
    		$user_agent_format,
    		$appVersion,
    		$device['AndroidVersion'],
    		$device['AndroidRelease'],
    		$device['DPI'],
    		$device['Resolution'],
    		$manufacturerWithBrand,
    		$device['Model'],
    		$device['Device'],
    		$device['CPU'],
    		$userLocale,
    		$version_code
    		);
    }

    public static function Generate()
    {

    	$deviceString = self::randomDevices();

    	if (!is_string($deviceString) || empty($deviceString)) {
    		throw new \RuntimeException('Device string is empty.');
    	}

        // Split the device identifier into its components and verify it.
    	$parts = explode('; ', $deviceString);
    	if (count($parts) !== 7) {
    		throw new \RuntimeException(sprintf('Device string "%s" does not conform to the required device format.', $deviceString));
    	}

        // Check the android version.
    	$androidOS = explode('/', $parts[0], 2);
    	if (version_compare($androidOS[1], self::REQUIRED_ANDROID_VERSION, '<')) {
    		throw new \RuntimeException(sprintf('Device string "%s" does not meet the minimum required Android version "%s" for Instagram.', $deviceString, self::REQUIRED_ANDROID_VERSION));
    	}

        // Check the screen resolution.
    	$resolution = explode('x', $parts[2], 2);
    	$pixelCount = (int) $resolution[0] * (int) $resolution[1];
        if ($pixelCount < 2073600) { // 1920x1080.
        	throw new \RuntimeException(sprintf('Device string "%s" does not meet the minimum resolution requirement of 1920x1080.', $deviceString));
        }

        // Extract "Manufacturer/Brand" string into separate fields.
        $manufacturerAndBrand = explode('/', $parts[3], 2);


        $device_data['Manufacturer'] = $manufacturerAndBrand[0];
        $device_data['Brand'] = (isset($manufacturerAndBrand[1]) ? $manufacturerAndBrand[1] : null);
        $device_data['AndroidVersion'] = $androidOS[0];
        $device_data['AndroidRelease'] = $androidOS[1];
        $device_data['DPI'] = $parts[1];
        $device_data['Resolution'] = $parts[2];
        $device_data['Model'] = $parts[4];
        $device_data['Device'] = $parts[5];
        $device_data['CPU'] = $parts[6];

        // Build our user agent.
        return self::Build($device_data);
    }   	

    public static function GenerateStatic()
    {
        return "Instagram 133.0.0.32.120 Android (18/4.3; 320dpi; 720x1280; Xiaomi; HM 1SW; armani; qcom; en_US)";
    }
}