<?php  
/**
* Instagram Follow DM Like
* Last Update 23 Juli 2020
* Author : Faanteyki
*/
require "AUTH.php";

use Riedayme\InstagramKit\InstagramHelper;
use Riedayme\InstagramKit\InstagramUserInfo;

use Riedayme\InstagramKit\InstagramUserFollowers;
use Riedayme\InstagramKit\InstagramUserFriendshipAPI;
use Riedayme\InstagramKit\InstagramUserFollow;

use Riedayme\InstagramKit\InstagramDirectCreateAPI;
use Riedayme\InstagramKit\InstagramDirectBroadcastAPI;

use Riedayme\InstagramKit\InstagramUserPost;
use Riedayme\InstagramKit\InstagramPostLike;
use Riedayme\InstagramKit\InstagramPostComment;

Class InstagramFollowDMLike
{

	public $logindata; 
	public $required_access;

	public $directmessage;
	public $targets;

	public $current_loop_target = 0;
	public $current_loop_message = 0;		

	public $next_id = array();

	public $count_process = 0;

	public $delay_bot = 10;
	public $delay_bot_default = 15;
	public $delay_bot_count = 0;

	public $direct_checktime = false;

	public $count_delay;

	public $limit_process = 60; // limit 60 follow/day

	public $fileconfig = "./data/fdl.json";	
	public $filelog = "./log/fdl-%s.json";	

	public function GetInputTargets() {

		echo "[?] Masukan Akun target pisah dengan tanda , : ".PHP_EOL;	

		$input = trim(fgets(STDIN));

		return (!$input) ? die('Target akun masih kosong') : $input;
	}	

	public function GetInputDirectMessage() {

		echo "[?] Masukan pesan untuk direct message | : ".PHP_EOL;

		$input = trim(fgets(STDIN));

		return (!$input) ? die('Jawaban pertanyaan masih kosong') : $input;
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

	public function BuildUserTarget()
	{

		$targetlist = explode(',', $this->targets);

		echo "[•] Membaca userID Target".PHP_EOL;

		$userinfo = new InstagramUserInfo();

		$userinfo->Required($this->required_access);

		$this->targets = array();
		foreach ($targetlist as $username) {

			$username = trim($username);

			$process = $userinfo->Process($username);

			if (!$process['status']) {

				echo "[•] Gagal Membaca User{$username}".PHP_EOL;
				echo "[•] Response : {$process['response']}".PHP_EOL;

			}else{

				$id = $userinfo->GetID($process);
				$followers = $userinfo->GetFollowersCount($process);

				if ($followers > 0) {
					echo "[•] User {$username} | id => [$id] followers => {$followers}".PHP_EOL;

					$this->targets[] = [
					'userid' => $id,
					'username' => $username
					];
				}else {
					echo "[!] User {$username} Tidak memiliki followers, SKIP".PHP_EOL;
				}
				
			}

		}

		if (count($this->targets) < 1) {
			die("[!] Tidak ada user yang valid untuk di proses");
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

	public function GetFollowersTarget()
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

		echo "[•] {$type}Mendapatkan List Followers {$usernametarget}".PHP_EOL;

		$results = false;
		$retry = 0;
		do {

			if ( $retry == 3 ) {
				echo "[•] Gagal Mendapatkan List Followers sebanyak 3x Relog Akun".PHP_EOL;

				$login = new Auth();
				$this->logindata = $login->Run($this->logindata);
			}elseif ($retry == 4) {
				die("[!] Gagal 4x Mendapatkan List Followers, Error");
			}

			$results = self::GetFollowersTargetByWeb($useridtarget,$next_id);

			if (!$results)
			{
				echo "[•] Gagal Mendapatkan List Followers, Coba Lagi".PHP_EOL;
				sleep(5);
			}

			$retry += 1;
		} while ( !$results );

		echo "[•] Berhasil mendapatkan ".count($results)." User".PHP_EOL;

		/* reset value */
		$this->count_delay = 0;

		/* delay bot */
		self::DelayBot();

		return $results;
	}

	public function GetFollowersTargetByWeb($useridtarget,$next_id)
	{
		$readfollowers = new InstagramUserFollowers();
		$readfollowers->SetCookie($this->logindata['cookie']);
		$userlist = $readfollowers->Process($useridtarget,$next_id);

		if (!$userlist['status']) return false;

		if ($userlist['cursor'] !== null) {
			$this->next_id[$useridtarget] = $userlist['cursor'];
		}else{
			$this->next_id[$useridtarget] = false;
		}

		$results = $readfollowers->Extract($userlist);

		return $results;
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


			if ($results['response']['following'] == true) {

				echo "[•] User {$userdata['username']} Sudah di Follow".PHP_EOL;

				$status = false;
			}else{

				echo "[•] User {$userdata['username']} Belum di Follow".PHP_EOL;

				$status = true;
			}

			/* delay bot */
			self::DelayBot();

			return $status;
		}

	}

	public function FollowUser($userdata)
	{

		echo "[•] Proses Follow User {$userdata['username']}".PHP_EOL;

		$follow = new InstagramUserFollow();
		$follow->SetCookie($this->logindata['cookie']);

		$results['status'] = false;
		$retry = 0;
		do {

			if ( $retry == 3 ) {
				echo "[•] Gagal Follow User {$userdata['username']} sebanyak 3x Relog Akun".PHP_EOL;

				$login = new Auth();
				$this->logindata = $login->Run($this->logindata);
			}elseif ($retry == 4) {
				die("[!] Gagal 4x Follow User {$userdata['username']}, Error");
			}			

			$results = $follow->Process($userdata['userid']);

			if (!$results['status'])
			{
				echo "[•] Gagal Follow User {$userdata['username']}, Coba Lagi".PHP_EOL;
				sleep(5);
			}

			$retry += 1;
		} while ( !$results['status'] );

		if ($results['status'] != false) {
			echo "[".date('d-m-Y H:i:s')."] Sukses Follow User {$userdata['username']}".PHP_EOL;
			echo "[•] Response : {$results['response']}".PHP_EOL;	

			self::SaveLog(strtolower($this->logindata['username']),$userdata['userid']);

			/* reset value */
			$this->count_delay = 0;
			
			/* delay bot */
			self::DelayBot();		

			return true;
		}
	}

	public function DirectUser($userdata)
	{

		if ($this->direct_checktime AND strtotime(date('Y-m-d H:i:s')) <= strtotime($this->direct_checktime)) {
			echo "[SKIP] Skip Direct Message sampai : {$this->direct_checktime}".PHP_EOL;
			return false;
		}

		echo "[•] Proses Kirim Pesan ke {$userdata['username']}".PHP_EOL;

		$userids[] = $userdata['userid'];
		$message = self::GetShuffleMessage($this->current_loop_message);

		$directcreate = new InstagramDirectCreateAPI();
		$directcreate->SetCookie($this->logindata['cookie']);
		$get_thread_id = $directcreate->Process($userids);

		if (!$get_thread_id['status']) {

			$extract_error = json_decode($get_thread_id['response'],true);

			if ($extract_error['message'] == 'login_required') {

				echo "[•] Gagal Melakukan Aksi Karena Akun Logout Otomatis, Relog Akun".PHP_EOL;

				$login = new Auth();
				$this->logindata = $login->Run($this->logindata);

				/* re process again */
				self::DirectUser($userdata);
			}else{

				echo "[•] Gagal membuat pesan dengan : {$userdata['username']}".PHP_EOL;					
				echo "[•] Response : {$get_thread_id['response']}".PHP_EOL;

				/* create log time for skip and check again */
				$this->direct_checktime = date('Y-m-d H:i:s',strtotime("+60 minutes"));

				return false;
			}
		}else{

			$this->direct_checktime = false; /* reset value */

			/* delay bot */
			self::DelayBot();
		}

		$thread_ids = [$get_thread_id['response']['thread_id']];

		$directcreate = new InstagramDirectBroadcastAPI();
		$directcreate->SetCookie($this->logindata['cookie']);
		$results = $directcreate->Process($message,$thread_ids);

		if ($results['status'] != false) {
			echo "[".date('d-m-Y H:i:s')."] Sukses Kirim Pesan ke {$userdata['username']} text {$message}".PHP_EOL;
			echo "[•] Response : {$results['response']}".PHP_EOL;			

			/* delay bot */
			self::DelayBot();

			return true;
		}else{

			$extract_error = json_decode($get_thread_id['response'],true);

			if ($extract_error['message'] == 'login_required') {

				echo "[•] Gagal Melakukan Aksi Karena Akun Logout Otomatis, Relog Akun".PHP_EOL;

				$login = new Auth();
				$this->logindata = $login->Run($this->logindata);

				/* re process again */
				self::DirectUser($userdata);
				
			}else{				
				echo "[".date('d-m-Y H:i:s')."] Gagal Kirim Pesan ke {$userdata['username']} text {$message}".PHP_EOL;
				echo "[•] Response : {$results['response']}".PHP_EOL;			
				return false;
			}
		}	
	}

	public function LikePost($userdata,$send_comment = false)
	{

		echo "[•] Mendapatkan post dari {$userdata['username']}".PHP_EOL;

		$readpost = new InstagramUserPost();
		$readpost->SetCookie($this->logindata['cookie']);

		$getpost['status'] = false;
		$retry = 0;
		do {

			if ( $retry == 3 ) {
				echo "[•] Gagal mendapatkan post dari {$userdata['username']} sebanyak 3x Relog Akun".PHP_EOL;

				$login = new Auth();
				$this->logindata = $login->Run($this->logindata);
			}elseif ($retry == 4) {
				die("[!] Gagal 4x mendapatkan post dari {$userdata['username']}, Error");
			}

			$getpost = $readpost->Process($userdata['userid']);

			if (!$getpost['status'])
			{

				if ($getpost['response'] == 'no_post') {

					echo "[•] User {$userdata['username']} tidak memiliki post".PHP_EOL;

					return false;
				}

				echo "[•] Gagal mendapatkan post dari {$userdata['username']}, Coba Lagi".PHP_EOL;
				sleep(5);
			}

			$retry += 1;
		} while ( !$getpost['status'] );

		$postdata = $readpost->Extract($getpost);

		/* delay bot */
		self::DelayBot();

		$likepost = new InstagramPostLike();
		$likepost->SetCookie($this->logindata['cookie']);

		$current = 0;
		$limit = 3;
		$delay = 14;
		foreach ($postdata as $post) {

			$results['status'] = false;
			$retry = 0;
			do {

				if ( $retry == 3 ) {
					echo "[•] Gagal Like post {$post['url']} sebanyak 3x Relog Akun".PHP_EOL;

					$login = new Auth();
					$this->logindata = $login->Run($this->logindata);
				}elseif ($retry == 4) {
					die("[!] Gagal 4x Like post {$post['url']}, Error");
				}

				$results = $likepost->Process($post['id']);

				if (!$results['status'])
				{

					if ($results['response'] == 'media_not_found') {
						echo "[•] post {$post['url']} tidak ditemukan, SKIP".PHP_EOL;
						echo "[•] Kemungkinan user menghapusnya atau anda diblokir olehnya".PHP_EOL;						
						sleep(5);
						break;
					}else{						
						echo "[•] Gagal Like post {$post['url']}, Coba Lagi".PHP_EOL;
						sleep(5);
					}

				}

				$retry += 1;
			} while ( !$results['status'] );

			if ($results['status'] != false) {
				echo "[".date('d-m-Y H:i:s')."] Sukses Like post {$post['url']}".PHP_EOL;
				echo "[•] Response : {$results['response']}".PHP_EOL;	

				echo "[•] Delay {$delay}".PHP_EOL;
				sleep($delay);
				$this->count_delay += $delay;
				$delay = $delay+5;

				$current++;
				
			}

			if ($current == $limit) {

				if ($send_comment) {

					// -1 because shuffle message dm change
					$message = self::GetShuffleMessage($this->current_loop_message - 1);

					$likecomment = new InstagramPostComment();
					$likecomment->SetCookie($this->logindata['cookie']);

					$results['status'] = false;
					$retry = 0;
					do {

						if ( $retry == 3 ) {
							echo "[•]  Gagal Kirim Komentar ke {$post['url']} sebanyak 3x Relog Akun".PHP_EOL;

							$login = new Auth();
							$this->logindata = $login->Run($this->logindata);
						}elseif ($retry == 4) {
							die("[!] Gagal 4x Kirim Komentar ke {$post['url']}, Error");
						}

						$results = $likecomment->Process($post['id'],$message);

						if (!$results['status'])
						{

							echo "[•]  Gagal Kirim Komentar ke {$post['url']}, Coba Lagi".PHP_EOL;
							sleep(5);
						}

						$retry += 1;
					} while ( !$results['status'] );

					if ($results['status'] != false) {
						echo "[".date('d-m-Y H:i:s')."] Sukses Kirim Komentar ke {$post['url']}".PHP_EOL;
						echo "[•] Response : {$results['response']}".PHP_EOL;	

						echo "[•] Delay {$delay}".PHP_EOL;
						sleep($delay);
						$delay = $delay+5;

						$current++;

					}
				}

				break;
			}

		}

	}	

	public function GetShuffleMessage($index)
	{

		$message = explode('|', $this->directmessage);

		/* reset index to 0 */
		if ($index >= count($message)) {
			$index = 0;
			$this->current_loop_message = 1;
		}else{
			$this->current_loop_message = $this->current_loop_message + 1;
		}

		return trim($message[$index]);		
	}

	public function SyncUser($userid)
	{

		$ReadLog = self::ReadLog(strtolower($this->logindata['username']));

		if (is_array($ReadLog) AND in_array($userid, $ReadLog)) 
		{
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

		echo "Instagram Follow DM Like".PHP_EOL;

		$login = new Auth();
		$this->logindata = $login->Run();		
		$this->required_access = [
		'cookie' => $this->logindata['cookie'],
			'useragent' => false, //  false for auto genereate
			'proxy' => false // false for not use proxy 
			];		

			if ($check = self::ReadSavedData($this->logindata['userid'])){
				echo "[?] Anda Memiliki konfigurasi yang tersimpan, gunakan kembali (y/n) : ";

				$reuse = trim(fgets(STDIN));

				if (!in_array(strtolower($reuse),['y','n'])) 
				{
					die("Pilihan tidak diketahui");
				}

				if ($reuse == 'y') {

					$targets = $check['targets'];
					$directmessage = $check['directmessage'];

				}else{

					$targets = self::GetInputTargets();
					$directmessage = self::GetInputDirectMessage();	

					/* save new config data */
					self::SaveData([
						'userid' => $this->logindata['userid'],
						'username' => $this->logindata['username'],
						'targets' => $targets,
						'directmessage' => $directmessage
						]);
				}
			}else{

				$targets = self::GetInputTargets();
				$directmessage = self::GetInputDirectMessage();

				self::SaveData([
					'userid' => $this->logindata['userid'],
					'username' => $this->logindata['username'],
					'targets' => $targets,
					'directmessage' => $directmessage
					]);
			}	

			/* set config */
			$this->targets = $targets;
			$this->directmessage = $directmessage;

			self::BuildUserTarget();

			while (true) {

				$userlist = self::GetFollowersTarget();

				foreach ($userlist as $userdata) {

					if($userdata['is_private']) continue;
					if($userdata['is_verified']) continue;
					if(!$userdata['latest_reel_media']) continue;

					/* sync user data with log file */
					if (self::SyncUser($userdata['userid'])) continue;

					$process_check = self::CheckUser($userdata);

					if (!$process_check) continue;

					$process_follow = self::FollowUser($userdata);

					$process_dm = self::DirectUser($userdata);
					$send_comment = false;
					if (!$process_dm) {
						$send_comment = true;
					}

					$process_like_post = self::LikePost($userdata,$send_comment);

					$this->count_process = $this->count_process + 1;
					echo "[•] Total Proses berjalan : {$this->count_process}".PHP_EOL;

					/* limit delay calculate process/day */
					$limit_delay = InstagramHelper::GetSleepTimeByLimit($this->limit_process) - $this->count_delay;
					echo "[•] Delay Selama : " . ceil($limit_delay / 60) ." menit".PHP_EOL;
					echo "[•] Proses Berikutnya pada Waktu : " . date('d-m-Y H:i:s', strtotime('+'.ceil($limit_delay / 60).' minutes')).PHP_EOL;
					sleep($limit_delay);
				}

			}		

		}	
	}

	$x = new InstagramFollowDMLike();
	$x->Run();
// use at you own risk