<?php  
/**
* Instagram Repost Target
* Last Update 24 Juli 2020
* Author : Faanteyki -[0]_[0]-
*/
require "AUTH.php";

use Riedayme\InstagramKit\InstagramHelper;
use Riedayme\InstagramKit\InstagramChecker;
use Riedayme\InstagramKit\InstagramResourceUser;

use Riedayme\InstagramKit\InstagramUserFriendshipAPI;
use Riedayme\InstagramKit\InstagramUserPost;

use Riedayme\InstagramKit\InstagramFeedHashtagAPI;

use Riedayme\InstagramKit\InstagramPostUploadAPI;

Class InstagramRepostTarget
{

	public $logindata; 

	public $choice_target;
	public $targets;
	public $custom_caption;

	public $limit_process = 20;

	public $current_loop_target = 0;

	public $next_id = array();

	public $count_process = 0;

	public $delay_bot = 10;
	public $delay_bot_default = 15;
	public $delay_bot_count = 0;

	public $count_delay;

	public $fileconfig = "./data/rt.json";	
	public $filelog = "./log/rt-%s.json";	
	public $temp = './temp/';

	public function GetInputChoiceTargets() {

		echo "[?] Pilih Sumber Media : ".PHP_EOL;
		echo "[1] User".PHP_EOL;
		echo "[2] Hashtag".PHP_EOL;
		echo "[?] Pilihan anda [1/2] : ";

		$input = trim(fgets(STDIN));

		if (!in_array(strtolower($input),['1','2'])) 
		{
			die("Pilihan tidak diketahui");
		}

		return (!$input) ? die('Sumber media masih kosong') : $input;
	}	

	public function GetInputTargetsUser() {

		echo "[?] Masukan Username Akun target pisah dengan tanda , : ".PHP_EOL;	

		$input = trim(fgets(STDIN));

		return (!$input) ? die('Target akun masih kosong') : $input;
	}	

	public function GetInputTargetsHashtag() {

		echo "[?] Masukan Hashtag tanpa tanda # pisah dengan tanda , : ".PHP_EOL;	

		$input = trim(fgets(STDIN));

		return (!$input) ? die('Target akun masih kosong') : $input;
	}		

	public function GetInputLimit() {

		echo "[?] Masukan Limit Repost per jam (angka) : ";

		$input = trim(fgets(STDIN));

		if (strval($input) !== strval(intval($input))) 
		{
			die("Salah memasukan format, pastikan hanya angka");
		}

		return (!$input) ? die('Limit masih kosong') : $input;
	}	

	public function GetInputChoiceCaption() {

		echo "[?] Custom Caption (y/n) : ".PHP_EOL;

		$input = trim(fgets(STDIN));

		if (!in_array(strtolower($input),['y','n'])) 
		{
			die("Pilihan tidak diketahui");
		}

		return (!$input) ? die('Pilihan masih kosong') : $input;
	}

	public function GetInputCaption() {

		echo "[?] Short Code : [USERNAME],[CAPTION]".PHP_EOL;
		echo "[?] Masukan Custom Caption : ";

		$input = trim(fgets(STDIN));

		return (!$input) ? die('Caption masih kosong') : $input;
	}		

	public function SaveData($data){

		$filename = $this->fileconfig;

		if (file_exists($filename)) {
			$read = file_get_contents($filename);
			$read = json_decode($read,true);
			$dataexist = false;
			foreach ($read as $key => $logdata) {
				if ($logdata['userid'] == $data['userid']) {
					$inputdata[] = $data;
					$dataexist = true;
				}else{
					$inputdata[] = $logdata;
				}
			}

			if (!$dataexist) {
				$inputdata[] = $data;
			}
		}else{
			$inputdata[] = $data;
		}

		return file_put_contents($filename, json_encode($inputdata,JSON_PRETTY_PRINT));
	}

	public function ReadSavedData($userid)
	{

		$filename = $this->fileconfig;

		if (file_exists($filename)) {

			$inputdata = false;
			$read = file_get_contents($filename);
			$read = json_decode($read,TRUE);
			foreach ($read as $key => $logdata) {
				if ($logdata['userid'] == $userid) {
					$inputdata = $logdata;
				}
			}

			return $inputdata;
		}else{
			return false;
		}
	}

	public function GetShuffleTarget($index){

		$targetlist = $this->targets;

		/* reset index to 0 */
		if ($index >= count($targetlist)) {
			$index = 0;
			$this->current_loop_target = 1;
		}else{
			$this->current_loop_target = $this->current_loop_target + 1;
		}

		return $targetlist[$index];
	}	

	public function BuildHashtagTarget()
	{
		$targetlist = explode(',', $this->targets);

		$this->targets = array();

		foreach ($targetlist as $hashtag_name) {

			$this->targets[] = $hashtag_name;

		}
	}

	public function GetHashtagPostTarget()
	{

		$current_hashtag = self::GetShuffleTarget($this->current_loop_target);

		$type = false;
		$next_id = false;
		if (!empty($this->next_id[$current_hashtag])) {
			$type = 'Lanjut-'.$this->next_id[$current_hashtag."_count"].' ';
			$this->next_id[$current_hashtag."_count"] = $this->next_id[$current_hashtag."_count"]+1;
			$next_id = $this->next_id[$current_hashtag];
		}else{
			$this->next_id[$current_hashtag."_count"] = 1;
		}

		echo "[•] {$type}Mendapatkan Post #{$current_hashtag}".PHP_EOL;

		$results = false;
		$retry = 0;
		do {

			if ( $retry == 3 ) {
				echo "[•] Gagal Mendapatkan Post #{$current_hashtag} sebanyak 3x Relog Akun".PHP_EOL;

				$login = new Auth();
				$this->logindata = $login->Run($this->logindata);
			}elseif ($retry == 4) {
				die("[!] Gagal 4x mendapatkan post #{$current_hashtag}, Error");
			}

			$results = self::GetHashtagPostByAPI($current_hashtag,$next_id);

			if (!$results)
			{
				echo "[•] Gagal Mendapatkan Post, Coba Lagi".PHP_EOL;
				sleep(5);
			}

			$retry += 1;
		} while ( !$results );

		echo "[•] Berhasil mendapatkan ".count($results)." Post".PHP_EOL;

		/* reset value */
		$this->count_delay = 0;

		/* delay bot */
		self::DelayBot();

		return $results;
	}

	public function GetHashtagPostByAPI($hashtag_name,$next_id)
	{
		$readfeed = new InstagramFeedHashtagAPI();
		$readfeed->Required([
			'cookie' => $this->logindata['cookie'],
			'useragent' => false, //  false for auto genereate
			'proxy' => false // false for not use proxy 
		]);

		$resource = $readfeed->Process($hashtag_name,$next_id);

		if (!$resource['status']) return false;

		if ($resource['next_id'] !== null) {
			$this->next_id[$hashtag_name] = $resource['next_id'];
		}else{
			$this->next_id[$hashtag_name] = false;
		}

		$results = $readfeed->Extract($resource);

		return $results;
	}

	public function BuildUserTarget()
	{

		$targetlist = explode(',', $this->targets);

		echo "[•] Membaca UserId Target".PHP_EOL;

		$this->targets = array();

		foreach ($targetlist as $username) {

			$username = trim($username);
			$getuserid = InstagramResourceUser::GetUserIdByWeb($username);						

			if ($getuserid) {
				echo "[•] User {$username} | id => [$getuserid]".PHP_EOL;

				$this->targets[] = [
					'userid' => $getuserid,
					'username' => $username
				];
			}else{
				echo "[•] Failed Read User {$username}".PHP_EOL;
			}

		}

	}	

	public function CheckUser()
	{

		$friendships = new InstagramUserFriendshipAPI();
		$friendships->SetCookie($this->logindata['cookie']);

		foreach ($this->targets as $userdata) {

			echo "[•] Proses Cek User {$userdata['username']}".PHP_EOL;

			$results = $friendships->Process($userdata['userid']);

			if (!$results['status']) {

				$extract_error = json_decode($results['response'],true);

				if ($extract_error['message'] == 'login_required') {

					echo "[•] Gagal Melakukan Aksi Karena Akun Logout Otomatis, Relog Akun".PHP_EOL;

					$login = new Auth();
					$this->logindata = $login->Run($this->logindata);

					/* re process again */
					self::CheckUser($userdata);
				}else{

					echo "[•] Gagal mengecek user : {$userdata['username']}".PHP_EOL;					
					echo "[•] Response : {$results['response']}".PHP_EOL;

					return false;
				}
			}else{

				if ($results['response']['is_private']) {

					echo "[•] User {$userdata['username']} Bersifat Private".PHP_EOL;

					if ($results['response']['following'] == true) {

						echo "[•] User {$userdata['username']} Sudah di Follow".PHP_EOL;

						$status = true;

						/* delay bot */
						self::DelayBot();

					}else{

						if ($results['response']['outgoing_request'] == true) {
							echo "[•] User {$userdata['username']} Sudah di Follow namun Belum di Approve".PHP_EOL;
						}else{
							echo "[•] User {$userdata['username']} Belum di Follow".PHP_EOL;
						}

						$status = false;
					}
				}else{
					$status = true;

					echo "[•] User {$userdata['username']} Bersifat Public ".PHP_EOL;
				}
				
				/* filter invalid target */
				if ($status == true) {

					$this->targets[] = $userdata;
				}
			}
		}

	}	

	public function GetUserPostTarget()
	{

		$getTarget = self::GetShuffleTarget($this->current_loop_target);

		$usernametarget = $getTarget['username'];
		$useridtarget = $getTarget['userid'];

		$type = false;
		$next_id = false;
		if (!empty($this->next_id[$useridtarget])) {
			$type = 'Lanjut-'.$this->next_id[$useridtarget."_count"].' ';
			$this->next_id[$useridtarget."_count"] = $this->next_id[$useridtarget."_count"]+1;
			$next_id = $this->next_id[$useridtarget];
		}else{
			$this->next_id[$useridtarget."_count"] = 1;
		}

		echo "[•] {$type}Mendapatkan Post {$usernametarget}".PHP_EOL;

		$results = false;
		$retry = 0;
		do {

			if ( $retry == 3 ) {
				echo "[•] Gagal Mendapatkan Post sebanyak 3x Relog Akun".PHP_EOL;

				self::Auth($this->active_data);
			}

			$results = self::GetUserPostByWeb($useridtarget,$next_id);

			if (!$results)
			{
				echo "[•] Gagal Mendapatkan Post, Coba Lagi".PHP_EOL;
				sleep(5);
			}

			$retry += 1;
		} while ( !$results );

		echo "[•] Berhasil mendapatkan ".count($results)." Post".PHP_EOL;

		/* reset value */
		$this->count_delay = 0;

		/* delay bot */
		self::DelayBot();

		return $results;
	}

	public function GetUserPostByWeb($useridtarget,$next_id)
	{
		$readpost = new InstagramUserPost();
		$readpost->SetCookie($this->logindata['cookie']);
		$userlist = $readpost->Process($useridtarget,$next_id);

		if (!$userlist['status']) return false;

		if ($userlist['cursor'] !== null) {
			$this->next_id[$useridtarget] = $userlist['cursor'];
		}else{
			$this->next_id[$useridtarget] = false;
		}

		$results = $readpost->Extract($userlist);

		return $results;
	}

	public function DownloadMedia($post)
	{

		echo "[•] Proses Download Media {$post['id']}".PHP_EOL;

		if ($post['type'] == 'carousel') {

			$medias_data = [];
			foreach ($post['media'] as $media) {
				if ($media['type'] == 'video') {

					$process_media = InstagramHelper::DownloadByURL($media['media'],$this->temp);

					if ($process_media) {

						echo "[•] Sukses Download Media {$post['id']}".PHP_EOL;

						echo "[•] Proses Download Thumbnail {$post['id']}".PHP_EOL;	

						if ($process_thumbnail = InstagramHelper::DownloadByURL($post['thumbnail'],$this->temp)) {

							echo "[•] Sukses Download Thumbnail {$post['id']}".PHP_EOL;

							$medias_data[] = [
								'filename' => $process_media,
								'thumbnail' => $process_thumbnail,
								'type' => 'video'
							];

						}else{

							echo "[•] Gagal Download Media {$post['id']}".PHP_EOL;

							if (count($medias_data) > 0) {
								foreach ($medias_data as $temp_medias) {
									unlink($this->temp.$temp_medias['thumbnail']);
									unlink($this->temp.$temp_medias['filename']);
								}
							}

							return false;
						}

					}else{

						echo "[•] Gagal Download Media {$post['id']}".PHP_EOL;

						if (count($medias_data) > 0) {
							foreach ($medias_data as $temp_medias) {
								unlink($this->temp.$temp_medias['filename']);
							}
						}

						return false;
					}

				}elseif ($media['type'] == 'image') {

					$process_media = InstagramHelper::DownloadByURL($media['media'],$this->temp);

					if ($process_media) {
						echo "[•] Sukses Download Media {$post['id']}".PHP_EOL;

						$medias_data[] = [
							'filename' => $process_media,
							'type' => 'image'
						];

					}else{

						echo "[•] Gagal Download Media {$post['id']}".PHP_EOL;

						if (count($medias_data) > 0) {
							foreach ($medias_data as $temp_medias) {
								unlink($this->temp.$temp_medias['filename']);
							}
						}

						return false;
					}

				}
			}

			return [
				'all_medias' => $medias_data
			];

		}elseif ($post['type'] == 'video') {

			$process_media = InstagramHelper::DownloadByURL($post['media'],$this->temp);

			if ($process_media) {
				echo "[•] Sukses Download Media {$post['id']}".PHP_EOL;

				echo "[•] Proses Download Thumbnail {$post['id']}".PHP_EOL;	

				if ($process_thumbnail = InstagramHelper::DownloadByURL($post['thumbnail'],$this->temp)) {

					echo "[•] Sukses Download Thumbnail {$post['id']}".PHP_EOL;

					return [
						'filename' => $process_media,
						'thumbnail' => $process_thumbnail
					];

				}else{

					echo "[•] Gagal Download Media {$post['id']}".PHP_EOL;

					unlink($this->temp.$process_media);
					unlink($this->temp.$process_thumbnail);

					return false;
				}
			}else{

				echo "[•] Gagal Download Media {$post['id']}".PHP_EOL;

				unlink($this->temp.$process_media);

				return false;
			}

		}elseif ($post['type'] == 'image') {

			$process_media = InstagramHelper::DownloadByURL($post['media'],$this->temp);

			if ($process_media) {
				echo "[•] Sukses Download Media {$post['id']}".PHP_EOL;

				return [
					'filename' => $process_media
				];

			}else{

				echo "[•] Gagal Download Media {$post['id']}".PHP_EOL;

				unlink($this->temp.$process_media);

				return false;
			}

		}

	}

	public function UploadMedia($post)
	{

		echo "[•] Proses Upload Media {$post['id']}".PHP_EOL;	

		if ($this->custom_caption) {
			$post['caption'] = !empty($post['caption']) ? $post['caption'] : '-' ;
			$short_code = array('[USERNAME]','[CAPTION]');
			$short_code_replace = array("@{$post['username']}","{$post['caption']}");
			$post['caption'] = str_replace($short_code, $short_code_replace, $this->custom_caption);
		}else{
			$post['caption'] = "Repost from @{$post['username']} \n Caption : \n\n {$post['caption']}";
		}

		$postupload = new InstagramPostUploadAPI();
		$postupload->SetCookie($this->logindata['cookie']);

		if ($post['type'] == 'image') {

			$upload = $postupload->ProcessUploadPhoto($this->temp.$post['filename']);

			if (!$upload['status']) {

				$extract_error = json_decode($upload['response'],true);

				if ($extract_error['message'] == 'login_required') {

					echo "[•] Gagal Melakukan Aksi Karena Akun Logout Otomatis, Relog Akun".PHP_EOL;

					$login = new Auth();
					$this->logindata = $login->Run($this->logindata);

					/* re process again */
					self::UploadMedia($post);
				}else{
					echo "[•] Gagal Upload Media {$post['id']}".PHP_EOL;
					echo "[•] Response : {$upload['response']}".PHP_EOL;	

					unlink($this->temp.$post['filename']);

					/* delay bot */
					self::DelayBot();

					return false;
				}
				
			}

			$upload_id = $upload['response']['upload_id'];

			echo "[•] Proses Konfigurasi Media {$post['id']}".PHP_EOL;	

			$configure = $postupload->ConfigurePhoto($upload_id,$post['caption']);

			if (!$configure['status']) {

				$extract_error = json_decode($configure['response'],true);

				if ($extract_error['message'] == 'login_required') {

					echo "[•] Gagal Melakukan Aksi Karena Akun Logout Otomatis, Relog Akun".PHP_EOL;

					$login = new Auth();
					$this->logindata = $login->Run($this->logindata);

					/* re process again */
					self::UploadMedia($post);
				}else{
					
					echo "[•] Gagal Konfigurasi Photo {$post['id']}".PHP_EOL;
					echo "[•] Response : {$configure['response']}".PHP_EOL;	

					unlink($this->temp.$post['filename']);

					/* delay bot */
					self::DelayBot();	

					return false;
				}

			}

			unlink($this->temp.$post['filename']);

		}elseif ($post['type'] == 'video') {

			$upload = $postupload->ProcessUploadVideo($this->temp.$post['filename']);

			if (!$upload['status']) {

				$extract_error = json_decode($upload['response'],true);

				if ($extract_error['message'] == 'login_required') {

					echo "[•] Gagal Melakukan Aksi Karena Akun Logout Otomatis, Relog Akun".PHP_EOL;

					$login = new Auth();
					$this->logindata = $login->Run($this->logindata);

					/* re process again */
					self::UploadMedia($post);
				}else{

					echo "[•] Gagal Upload Media {$post['id']}".PHP_EOL;
					echo "[•] Response : {$upload['response']}".PHP_EOL;	

					unlink($this->temp.$post['filename']);
					unlink($this->temp.$post['thumbnail']);

					/* delay bot */
					self::DelayBot();	

					return false;
				}

			}

			$upload_id = $upload['response']['upload_id'];

			sleep(5);

			echo "[•] Proses Upload Thumbnail {$post['id']}".PHP_EOL;	

			$upload = $postupload->ProcessUploadPhoto($this->temp.$post['thumbnail'],$upload_id);

			if (!$upload['status']) {

				$extract_error = json_decode($upload['response'],true);

				if ($extract_error['message'] == 'login_required') {

					echo "[•] Gagal Melakukan Aksi Karena Akun Logout Otomatis, Relog Akun".PHP_EOL;

					$login = new Auth();
					$this->logindata = $login->Run($this->logindata);

					/* re process again */
					self::UploadMedia($post);
				}else{

					echo "[•] Gagal Upload Thumbnail {$post['id']}".PHP_EOL;
					echo "[•] Response : {$upload['response']}".PHP_EOL;	

					unlink($this->temp.$post['filename']);
					unlink($this->temp.$post['thumbnail']);

					/* delay bot */
					self::DelayBot();	

					return false;
				}

			}

			echo "[•] Proses Konfigurasi Media {$post['id']}".PHP_EOL;	

			$configure = $postupload->ConfigureVideo($upload_id,$post['caption']);

			if (!$configure['status']) {

				$extract_error = json_decode($configure['response'],true);

				if ($extract_error['message'] == 'login_required') {

					echo "[•] Gagal Melakukan Aksi Karena Akun Logout Otomatis, Relog Akun".PHP_EOL;

					$login = new Auth();
					$this->logindata = $login->Run($this->logindata);

					/* re process again */
					self::UploadMedia($post);
				}else{

					echo "[•] Gagal Konfigurasi Video {$post['id']}".PHP_EOL;
					echo "[•] Response : {$configure['response']}".PHP_EOL;	

					unlink($this->temp.$post['filename']);
					unlink($this->temp.$post['thumbnail']);

					/* delay bot */
					self::DelayBot();	

					return false;
				}

			}

			unlink($this->temp.$post['filename']);
			unlink($this->temp.$post['thumbnail']);

		}elseif ($post['type'] == 'carousel') {

			$medias = [];
			foreach ($post['all_medias'] as $media) {

				if ($media['type'] == 'image') {

					$upload = $postupload->ProcessUploadPhoto($this->temp.$media['filename'],false,true);

					if (!$upload['status']) {

						$extract_error = json_decode($upload['response'],true);

						if ($extract_error['message'] == 'login_required') {

							echo "[•] Gagal Melakukan Aksi Karena Akun Logout Otomatis, Relog Akun".PHP_EOL;

							$login = new Auth();
							$this->logindata = $login->Run($this->logindata);

							/* re process again */
							self::UploadMedia($post);
						}else{

							echo "[•] Gagal Upload Media {$post['id']}".PHP_EOL;
							echo "[•] Response : {$upload['response']}".PHP_EOL;	

							foreach ($post['all_medias'] as $temp_medias) {
								unlink($this->temp.$temp_medias['filename']);
							}

							/* delay bot */
							self::DelayBot();	

							return false;
						}

					}

					$upload_id = $upload['response']['upload_id'];


				}elseif ($media['type'] == 'video') {

					$upload = $postupload->ProcessUploadVideo($this->temp.$media['filename'],true);

					if (!$upload['status']) {

						$extract_error = json_decode($upload['response'],true);

						if ($extract_error['message'] == 'login_required') {

							echo "[•] Gagal Melakukan Aksi Karena Akun Logout Otomatis, Relog Akun".PHP_EOL;

							$login = new Auth();
							$this->logindata = $login->Run($this->logindata);

							/* re process again */
							self::UploadMedia($post);
						}else{

							echo "[•] Gagal Upload Media {$post['id']}".PHP_EOL;
							echo "[•] Response : {$upload['response']}".PHP_EOL;	

							foreach ($post['all_medias'] as $temp_medias) {
								unlink($this->temp.$temp_medias['filename']);
							}

							/* delay bot */
							self::DelayBot();	

							return false;
						}

					}

					$upload_id = $upload['response']['upload_id'];

					sleep(5);

					$upload = $postupload->ProcessUploadPhoto($this->temp.$media['thumbnail'],$upload_id);

				}

				$medias[] = array_merge($media,array('upload_id' => $upload_id));
			}

			$configure = $postupload->ConfigureAlbum($medias,$post['caption']);

			if (!$configure['status']) {

				$extract_error = json_decode($configure['response'],true);

				if ($extract_error['message'] == 'login_required') {

					echo "[•] Gagal Melakukan Aksi Karena Akun Logout Otomatis, Relog Akun".PHP_EOL;

					$login = new Auth();
					$this->logindata = $login->Run($this->logindata);

					/* re process again */
					self::UploadMedia($post);
				}else{

					echo "[•] Gagal Konfigurasi Album".PHP_EOL;
					echo "[•] Response : {$configure['response']}".PHP_EOL;	

					foreach ($post['all_medias'] as $temp_medias) {
						unlink($this->temp.$temp_medias['filename']);
					}

					/* delay bot */
					self::DelayBot();	

					return false;
				}

			}

			foreach ($post['all_medias'] as $temp_medias) {
				unlink($this->temp.$temp_medias['filename']);
			}
		}

		echo "[".date('d-m-Y H:i:s')."] Sukses Upload Media {$post['id']}".PHP_EOL;
		echo "[•] Response : ".$configure['response']['url'].PHP_EOL;	

		self::SaveLog(strtolower($this->logindata['username']),$post['id']);

		return true;
	}

	public function SyncPost($postid)
	{

		$ReadLog = self::ReadLog(strtolower($this->logindata['username']));

		if (is_array($ReadLog) AND in_array($postid, $ReadLog)) 
		{

			echo "[•] Media : {$postid} Sudah dipost, SKIP".PHP_EOL;

			return true;
		}

		return false;
	}

	public function ReadLog($identity)
	{		

		$logfilename = sprintf($this->filelog,$identity);
		$log_id = array();
		if (file_exists($logfilename)) 
		{
			$log_id = file_get_contents($logfilename);
			$log_id  = explode(PHP_EOL, $log_id);
		}

		return $log_id;
	}

	public function SaveLog($identity,$datastory)
	{
		return file_put_contents(sprintf($this->filelog,$identity), $datastory.PHP_EOL, FILE_APPEND);
	}	

	public function DelayBot()
	{

		/* reset sleep value to default */
		if ($this->delay_bot_count >= 5) {
			$this->delay_bot = $this->delay_bot_default;
			$this->delay_bot_count = 0;
		}	

		echo "[•] Delay {$this->delay_bot}".PHP_EOL;
		sleep($this->delay_bot);
		$this->count_delay += $this->delay_bot;
		$this->delay_bot = $this->delay_bot+5;
		$this->delay_bot_count++;
	}

	public function Run()
	{

		echo "Instagram Repost Target".PHP_EOL;

		$login = new Auth();
		$this->logindata = $login->Run();		

		if ($check = self::ReadSavedData($this->logindata['userid'])){
			echo "[?] Anda Memiliki konfigurasi yang tersimpan, gunakan kembali (y/n) : ";

			$reuse = trim(fgets(STDIN));

			if (!in_array(strtolower($reuse),['y','n'])) 
			{
				die("Pilihan tidak diketahui");
			}

			if ($reuse == 'y') {

				$choice_target = $check['choice_target'];
				$targets = $check['targets'];
				$custom_caption = $check['custom_caption'];

			}else{

				$choice_target = self::GetInputChoiceTargets();

				if ($choice_target == '1') {
					$choice_target = 'user';
					$targets = self::GetInputTargetsUser();
				}else{
					$choice_target = 'hashtag';
					$targets = self::GetInputTargetsHashtag();
				}

				$choice_caption = self::GetInputChoiceCaption();

				if ($choice_caption == 'y') {
					$custom_caption = self::GetInputCaption();
				}else{
					$custom_caption = false;
				}

				/* save new config data */
				self::SaveData([
					'userid' => $this->logindata['userid'],
					'username' => $this->logindata['username'],
					'choice_target' => $choice_target,
					'targets' => $targets,
					'custom_caption' => $custom_caption
				]);
			}
		}else{

			$choice_target = self::GetInputChoiceTargets();

			if ($choice_target == '1') {
				$choice_target = 'user';
				$targets = self::GetInputTargetsUser();
			}else{
				$choice_target = 'hashtag';
				$targets = self::GetInputTargetsHashtag();
			}

			$choice_caption = self::GetInputChoiceCaption();

			if ($choice_caption == 'y') {
				$custom_caption = self::GetInputCaption();
			}else{
				$custom_caption = false;
			}


			self::SaveData([
				'userid' => $this->logindata['userid'],
				'username' => $this->logindata['username'],
				'choice_target' => $choice_target,
				'targets' => $targets,
				'custom_caption' => $custom_caption
			]);
		}	

		/* set config */
		$this->choice_target = $choice_target;		
		$this->targets = $targets;
		$this->custom_caption = $custom_caption;		
		$this->limit_process = self::GetInputLimit();

		if ($choice_target == 'user') {
			self::BuildUserTarget();

			/* check if account is private and you not followed this account and in requested */
			self::CheckUser();
		}else{
			self::BuildHashtagTarget();
		}

		$no_activity = true;
		while (true) {

			if ($choice_target == 'user') {
				$postlist = self::GetUserPostTarget();		
			}else{
				$postlist = self::GetHashtagPostTarget();		
			}

			foreach ($postlist as $postdata) {

				/* sync post data with log file */
				if (self::SyncPost($postdata['id'])) continue;

				/* if media is video check type product  if igtv skip... */
				if ($postdata['type'] == 'video') {
					$check_video = InstagramChecker::IsIGTV($postdata['code']);

					if ($check_video) continue;
				}

				$process_download = self::DownloadMedia($postdata);
				if (!$process_download) {					
					continue;
				}else{
					$postdata = array_merge($postdata,$process_download);
				}

				$process_upload = self::UploadMedia($postdata);
				if (!$process_upload) {					
					continue;
				}

				$no_activity = false; /* activty detected */
				$this->count_process = $this->count_process + 1;
				echo "[•] Total Proses berjalan : {$this->count_process}".PHP_EOL;

				/* limit delay calculate process/hours */
				$limit_delay = InstagramHelper::GetSleepTimeByLimit($this->limit_process,'hours') - $this->count_delay;
				echo "[•] Delay Selama : " . ceil($limit_delay / 60) ." menit".PHP_EOL;
				echo "[•] Proses Berikutnya pada Waktu : " . date('d-m-Y H:i:s', strtotime('+'.ceil($limit_delay / 60).' minutes')).PHP_EOL;
				sleep($limit_delay);
			}

			/* if no activity sleep long.. */
			if ($no_activity) {
				$sleep_long = InstagramHelper::GetSleepTime('60minutes');
				echo "[•] Tidak ada postingan yang diproses, sleep selama : ". ceil($sleep_long / 60) ." menit".PHP_EOL;
				echo "[•] Proses Berikutnya pada Waktu : " . date('d-m-Y H:i:s', strtotime('+'.ceil($sleep_long / 60).' minutes')).PHP_EOL;
				sleep($sleep_long);
			}
		}		

	}	
}

$x = new InstagramRepostTarget();
$x->Run();
// use at you own risk