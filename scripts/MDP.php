<?php  
/**
* Instagram Mass Downloader People
* Last Update 24 Juli 2020
* Author : Faanteyki
*/
require "AUTH.php";

use Riedayme\InstagramKit\InstagramHelper;
use Riedayme\InstagramKit\InstagramUserInfo;
use Riedayme\InstagramKit\InstagramUserFriendshipAPI;
use Riedayme\InstagramKit\InstagramUserPost;
use Riedayme\InstagramKit\InstagramFeedHighlight;
use Riedayme\InstagramKit\InstagramUserHiglight;

Class InstagramMassDownloaderPeople
{

	public $logindata; 
	public $required_access;

	public $targets;

	public $next_id = array();

	public $count_process = 0;
	public $count_success = 0;
	public $count_failed = 0;

	public $delay_bot = 10;
	public $delay_bot_default = 15;
	public $delay_bot_count = 0;

	public $filelog = "./log/mdp";

	public function GetInputTargets() {

		echo "[?] Masukan Akun target pisah dengan tanda , : ".PHP_EOL;	

		$input = trim(fgets(STDIN));

		return (!$input) ? die('Target akun masih kosong') : $input;
	}	

	public function GetInputHighlight() {

		echo "[?] Download Highlight (y/n) : ";

		$input = trim(fgets(STDIN));

		if (!in_array(strtolower($input),['y','n'])) 
		{
			die("Pilihan tidak diketahui");
		}

		return (!$input) ? die('Pilihan masih Kosong') : $input;
	}	

	public function CheckUser($userdata)
	{

		echo "[•] Proses Cek User {$userdata['username']}".PHP_EOL;

		$friendships = new InstagramUserFriendshipAPI();
		$friendships->SetCookie($this->logindata['cookie']);

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
			}

			return $status;
		}
	}

	public function GetUserPostTarget($usertarget)
	{

		$usernametarget = $usertarget['username'];
		$useridtarget = $usertarget['userid'];

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

				$login = new Auth();
				$this->logindata = $login->Run($this->logindata);

				echo "[•] {$type}Mendapatkan Post {$usernametarget}".PHP_EOL;
			}

			$results = self::GetUserPostByWeb($useridtarget,$next_id);

			if (!$results)
			{				
				echo "[•] Gagal Mendapatkan Post, Coba Lagi".PHP_EOL;
				sleep(5);
			}elseif ($results == 'skip') {
				return false;
			}

			$retry += 1;
		} while ( !$results );

		echo "[•] Berhasil mendapatkan ".count($results)." Post".PHP_EOL;

		/* delay bot */
		self::DelayBot();

		return $results;
	}

	public function GetUserPostByWeb($useridtarget,$next_id)
	{
		$readpost = new InstagramUserPost();
		$readpost->SetCookie($this->logindata['cookie']);
		$postlist = $readpost->Process($useridtarget,$next_id);		

		if (!$postlist['status']) {
			if ($postlist['response'] == 'no_post') {

				echo "[•] Tidak dapat mengambil post".PHP_EOL;
				echo "[•] Response : {$postlist['response']}".PHP_EOL;

				$this->next_id[$useridtarget] = false;

				return 'skip'; // skip becaouse no post 
			}else{
				return false;
			}
		}

		if ($postlist['cursor'] !== null) {
			$this->next_id[$useridtarget] = $postlist['cursor'];
		}else{
			$this->next_id[$useridtarget] = false;
		}

		$results = $readpost->Extract($postlist);

		return $results;
	}

	public function GetUserHiglightTarget($usertarget)
	{

		echo "[•] Mendapatkan Feed Highlight : {$usertarget['username']}".PHP_EOL;

		$readfeed = new InstagramFeedHighlight();
		$readfeed->SetCookie($this->logindata['cookie']);
		$feedlight = $readfeed->Process($usertarget['userid']);

		if (!$feedlight['status']) {
			echo "[•] Gagal Mendapatkan Feed Highlight".PHP_EOL;
			echo "[•] Response : {$feedlight['response']}".PHP_EOL;
			return false;
		}

		$idshiglight = $readfeed->Extract($feedlight);

		echo "[•] Mendapatkan Higlight User {$usertarget['username']}".PHP_EOL;

		$readhiglight = new InstagramUserHiglight();
		$readhiglight->SetCookie($this->logindata['cookie']);
		$highlightdata = $readhiglight->Process($idshiglight);

		if (!$highlightdata['status']) {
			echo "[•] Gagal Mendapatkan Highlight User".PHP_EOL;
			return false;
		}

		return $readhiglight->Extract($highlightdata);
	}

	public function DownloadMedia($post,$usernametarget)
	{

		if (empty($post['url'])) {
			$post['url'] = $post['id'];
		}

		if (!is_dir('./download/'. $usernametarget)) {
			mkdir('./download/'. $usernametarget);
		}

		$destination = './download/'. $usernametarget .'/';

		echo "[•] Proses Download Media {$post['url']}".PHP_EOL;

		if ($post['type'] == 'carousel') {

			$medias_data = [];
			$failed = false;
			foreach ($post['media'] as $media) {
				if ($media['type'] == 'video') {

					$process_media = InstagramHelper::DownloadByURL($media['media'],$destination);

					if ($process_media) {

						echo "[•] Sukses Download Media {$post['url']}".PHP_EOL;

						echo "[•] Proses Download Thumbnail {$post['url']}".PHP_EOL;	

						if ($process_thumbnail = InstagramHelper::DownloadByURL($post['thumbnail'],$destination)) {

							echo "[•] Sukses Download Thumbnail {$post['url']}".PHP_EOL;

							$medias_data[] = [
								'filename' => $process_media,
								'thumbnail' => $process_thumbnail,
								'type' => 'video'
							];

						}else{

							echo "[•] Gagal Download Media {$post['url']}".PHP_EOL;

							$failed = true;
						}

					}else{

						echo "[•] Gagal Download Media {$post['url']}".PHP_EOL;

						$failed = true;
					}

				}elseif ($media['type'] == 'image') {

					$process_media = InstagramHelper::DownloadByURL($media['media'],$destination);

					if ($process_media) {
						echo "[•] Sukses Download Media {$post['url']}".PHP_EOL;

						$medias_data[] = [
							'filename' => $process_media,
							'type' => 'image'
						];

					}else{

						echo "[•] Gagal Download Media {$post['url']}".PHP_EOL;

						$failed = true;
					}

				}
			}


			/* if part download failed > failed all and deleted media */
			if ($failed) {

				if (count($medias_data) > 0) {
					foreach ($medias_data as $temp_medias) {
						if ($temp_medias['type'] == 'video') {
							unlink($destination.$temp_medias['thumbnail']);
							unlink($destination.$temp_medias['filename']);
						}else{
							unlink($destination.$temp_medias['filename']);
						}
					}
				}

				return false;
			}else{	

				$this->count_success += 1;
				self::SaveLog($post['id']);

				return true;
			}

		}elseif ($post['type'] == 'video') {

			$process_media = InstagramHelper::DownloadByURL($post['media'],$destination);

			if ($process_media) {
				echo "[•] Sukses Download Media {$post['url']}".PHP_EOL;

				echo "[•] Proses Download Thumbnail {$post['url']}".PHP_EOL;	

				if ($process_thumbnail = InstagramHelper::DownloadByURL($post['thumbnail'],$destination)) {

					echo "[•] Sukses Download Thumbnail {$post['url']}".PHP_EOL;

					$this->count_success += 1;

					self::SaveLog($post['id']);

					return true;
				}else{

					echo "[•] Gagal Download Thumbnail {$post['url']}".PHP_EOL;

					unlink($destination.$process_media);
					unlink($destination.$process_thumbnail);

					$this->count_failed += 1;

					return false;
				}
			}else{

				echo "[•] Gagal Download Media {$post['url']}".PHP_EOL;

				unlink($destination.$process_media);

				return false;
			}

		}elseif ($post['type'] == 'image') {

			$process_media = InstagramHelper::DownloadByURL($post['media'],$destination);

			if ($process_media) {
				echo "[•] Sukses Download Media {$post['url']}".PHP_EOL;

				$this->count_success += 1;

				self::SaveLog($post['id']);

				return true;
			}else{

				echo "[•] Gagal Download Media {$post['url']}".PHP_EOL;

				unlink($destination.$process_media);

				$this->count_failed += 1;

				return false;
			}

		}

	}

	public function SyncPost($postid)
	{

		$ReadLog = self::ReadLog();

		if (is_array($ReadLog) AND in_array($postid, $ReadLog)) 
		{

			echo "[•] Media : {$postid} Sudah di Download, SKIP".PHP_EOL;

			return true;
		}

		return false;
	}

	public function ReadLog()
	{		

		$logfilename = $this->filelog;
		$log_id = array();
		if (file_exists($logfilename)) 
		{
			$log_id = file_get_contents($logfilename);
			$log_id  = explode(PHP_EOL, $log_id);
		}

		return $log_id;
	}

	public function SaveLog($logdata)
	{
		return file_put_contents($this->filelog, $logdata.PHP_EOL, FILE_APPEND);
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
		$this->delay_bot = $this->delay_bot+5;
		$this->delay_bot_count++;
	}

	public function Run()
	{

		echo "Instagram Repost Target People".PHP_EOL;

		$login = new Auth();

		$this->logindata = $login->Run();
		$this->required_access = [
			'cookie' => $this->logindata['cookie'],
			'useragent' => false, //  false for auto genereate
			'proxy' => false // false for not use proxy 
		];

		$targets = self::GetInputTargets();
		$highlight = self::GetInputHighlight();

		echo "[•] Membaca UserId Target".PHP_EOL;

		$userinfo = new InstagramUserInfo();
		$userinfo->Required($this->required_access);

		$targetlist = explode(',', $targets);
		foreach ($targetlist as $username) {

			$username = trim($username);

			$process = $userinfo->Process($username);

			if (!$process['status']) {

				echo "[•] Gagal Membaca User{$username}".PHP_EOL;
				echo "[•] Response : {$process['response']}".PHP_EOL;

			}else{

				$id = $userinfo->GetID($process);
				$postcount = $userinfo->GetPostCount($process);

				if ($postcount > 0) {
					echo "[•] User {$username} | id => [$id] post => {$postcount}".PHP_EOL;

					$usertarget = [
						'userid' => $id,
						'username' => $username
					];
				}else {
					echo "[!] User {$username} Tidak memiliki post, SKIP".PHP_EOL;
					continue;
				}
				
			}

			/* check if account is private and you hasben followed this account and not in requested */
			$check_target = self::CheckUser($usertarget);
			if (!$check_target) continue;

			do {

				$postlist = self::GetUserPostTarget($usertarget);	

				if (!$postlist) continue;

				foreach ($postlist as $postdata) {

					/* sync post data with log file */
					if (self::SyncPost($postdata['id'])) continue;

					$process_download = self::DownloadMedia($postdata,$username);

					$this->count_process = $this->count_process + 1;
					echo "[•] Total Proses berjalan : {$this->count_process}".PHP_EOL;
					echo "[•] Total Berhasil : {$this->count_success}".PHP_EOL;
					echo "[•] Total Gagal : {$this->count_failed}".PHP_EOL;								
				}

			} while ($this->next_id[$usertarget['userid']] != false);

			if ($highlight == 'y') {
				$highlightlist = self::GetUserHiglightTarget($usertarget);

				if (!$highlightlist) {
					continue;
				}

				foreach ($highlightlist as $highlightdata) {
					/* sync post data with log file */
					if (self::SyncPost($highlightdata['id'])) continue;

					$process_download = self::DownloadMedia($highlightdata,$username);

					$this->count_process = $this->count_process + 1;
					echo "[•] Total Proses berjalan : {$this->count_process}".PHP_EOL;
					echo "[•] Total Berhasil : {$this->count_success}".PHP_EOL;
					echo "[•] Total Gagal : {$this->count_failed}".PHP_EOL;				
				}
			}

		}

	}	
}

$x = new InstagramMassDownloaderPeople();
$x->Run();
// use at you own risk