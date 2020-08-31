<?php  
/**
* Instagram Unfollow Not Follow Back
* Last Update 30 Agustus 2020
* Author : Faanteyki
*/
require "AUTH.php";

use Riedayme\InstagramKit\InstagramHelper;

use Riedayme\InstagramKit\InstagramUserFollowing;
use Riedayme\InstagramKit\InstagramUserFriendshipAPI;

use Riedayme\InstagramKit\InstagramUserUnFollow;

Class InstagramUnfollowNotFollowBack
{

	public $logindata; 
	public $required_access;

	public $userexception;

	public $count_process = 0;

	public $delay_bot = 10;
	public $delay_bot_default = 15;
	public $delay_bot_count = 0;

	public $count_delay;

	public $limit_process = 50; // limit 50 unfollow/hours

	public $fileconfig = "./data/unb.json";	
	public $filelog = "./log/unb-%s.json";

	public $filelogfollowing = "./log/unb-%s-following.json";
	public $filelognotfollow = "./log/unb-%s-notfollow.json";

	public function GetInputExceptionUnfollow() {

		echo "[?] Masukan Akun yang tidak ingin di unfollow (pisah dengan ,) : ".PHP_EOL;	

		$input = trim(fgets(STDIN));

		return $input;
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

	public function GetFollowing()
	{

		$readfollowing = new InstagramUserFollowing();
		$readfollowing->SetCookie($this->logindata['cookie']);

		$cursor = false;
		$count = 1;
		$all_data = [];
		do {

			echo "[•] Mendapatkan List Following {$count}".PHP_EOL;

			$post = $readfollowing->Process($this->logindata['userid'],$cursor);

			if (!$post['status']) {
				echo "[•] Gagal mendapatkan List Following".PHP_EOL;
				echo "[•] Response : {$post['response']}".PHP_EOL;

				if ($post['response'] == 'invalid_cursor') {
					$cursor = $post['cursor'];
				}

				continue;
			}

			$data = $readfollowing->Extract($post);

			$all_data = array_merge($all_data,$data);

			if ($post['cursor'] !== null) {
				$cursor = $post['cursor'];
			}else{
				$cursor = false;
			}

			$count += 1;

			echo "[•] Berhasil mendapatkan ".count($all_data)." Following".PHP_EOL;

			/* delay bot */
			self::DelayBot();

		} while ($cursor !== false);

		file_put_contents(sprintf($this->filelogfollowing,$this->logindata['username']), json_encode($all_data,JSON_PRETTY_PRINT));

		return json_decode(file_get_contents(sprintf($this->filelogfollowing,$this->logindata['username'])),true);
	}	

	public function CheckFriendship($userdata)
	{
		echo "[•] Proses Cek User {$userdata['username']}".PHP_EOL;

		if (file_exists(sprintf($this->filelognotfollow,$this->logindata['username']))) {

			$userlist = file_get_contents(sprintf($this->filelognotfollow,$this->logindata['username']));

			$explode = explode(PHP_EOL, $userlist);

			foreach ($explode as $user) {
				$extract = explode("|", $user);
				$userid = $extract[0];

				/* check if userid exist on log > skip */
				if ($userdata['userid'] == $userid) {
					echo "[•] User {$userdata['username']} Sudah diproses, SKIP".PHP_EOL;
					return false;
				}
			}
		}	

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

				/* delay bot */
				self::DelayBot();

				return false;
			}
		}else{


			if ($results['response']['followed_by'] == true) {

				echo "[•] User {$userdata['username']} Sudah Follow".PHP_EOL;

				$status = false;

				/* delay bot */
				self::DelayBot();

			}else{

				echo "[•] User {$userdata['username']} Belum Follow".PHP_EOL;

				$status = true;
			}

			return $status;
		}
	}

	public function ProcessUnFollow($userdata)
	{

		echo "[•] Proses Unfollow User {$userdata['username']}".PHP_EOL;

		$unfollow = new InstagramUserUnFollow();

		$unfollow->Required($this->required_access);

		$results = $unfollow->Process($userdata['userid']);

		if ($results['status']) {

			echo "[•] Proses Unfollow User {$userdata['username']} Berhasil".PHP_EOL;

			/* create log not follow > for exclusion next process */
			file_put_contents(sprintf($this->filelognotfollow,$this->logindata['username']), $userdata['userid'].'|'.$userdata['username'].PHP_EOL, FILE_APPEND);

			return true;
		}else{

			echo "[•] Proses Unfollow User {$userdata['username']} Gagal".PHP_EOL;
			echo "[•] Response : {$results['response']}".PHP_EOL;

			return false;
		}
	}

	public function CheckUserException($username) 
	{
		$extract = explode(',', $this->userexception);

		$status = false;
		foreach ($extract as $userexception) {
			if ($username == $userexception) {
				$status = true;
				break;
			}else{
				$status = false;
			}
		}

		return $status;
	}

	public function SyncProcess($userid)
	{

		$ReadLog = self::ReadLog(strtolower($this->logindata['username']));

		if (is_array($ReadLog) AND in_array($userid, $ReadLog)) 
		{

			echo "[•] User {$userid} Sudah diproses, SKIP".PHP_EOL;

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

		echo "Instagram Unfollow Not Follow Back".PHP_EOL;

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

				$userexception = $check['userexception'];

			}else{

				$userexception = self::GetInputExceptionUnfollow();

				/* save new config data */
				self::SaveData([
					'userid' => $this->logindata['userid'],
					'username' => $this->logindata['username'],
					'userexception' => $userexception,
				]);
			}
		}else{

			$userexception = self::GetInputExceptionUnfollow();

			self::SaveData([
				'userid' => $this->logindata['userid'],
				'username' => $this->logindata['username'],
				'userexception' => $userexception
			]);
		}	

		/* set config */
		$this->userexception = $userexception;

		$getfollowinglist = false;
		if (file_exists(sprintf($this->filelogfollowing,$this->logindata['username']))) {
			echo "[?] Anda Memiliki list following yang tersimpan, gunakan kembali (y/n) : ";

			$reuse = trim(fgets(STDIN));

			if (!in_array(strtolower($reuse),['y','n'])) 
			{
				die("Pilihan tidak diketahui");
			}

			if ($reuse == 'y') {

				$getfollowinglist = json_decode(file_get_contents(sprintf($this->filelogfollowing,$this->logindata['username'])),true);
			}
		}	

		if (!$getfollowinglist) {
			$getfollowinglist = self::GetFollowing();
		}

		foreach ($getfollowinglist as $userfollowing) {

			/* sync process data with log file */
			if (self::SyncProcess($userfollowing['userid'])) continue;

			/* skip if username same as exception list */
			if (self::CheckUserException($userfollowing['username'])) continue;

			/* check if user not follow you > unfollow */
			if (self::CheckFriendship($userfollowing)) {
				$unfollow = self::ProcessUnFollow($userfollowing);

				$this->count_process = $this->count_process + 1;
				echo "[•] Total Proses berjalan : {$this->count_process}".PHP_EOL;

				/* limit delay calculate process/day */
				$limit_delay = InstagramHelper::GetSleepTimeByLimit($this->limit_process,"hours") - $this->count_delay;
				echo "[•] Delay Selama : " . ceil($limit_delay / 60) ." menit".PHP_EOL;
				echo "[•] Proses Berikutnya pada Waktu : " . date('d-m-Y H:i:s', strtotime('+'.ceil($limit_delay / 60).' minutes')).PHP_EOL;
				sleep($limit_delay);
			}

			/* create log process */
			self::SaveLog(strtolower($this->logindata['username']),$userfollowing['userid']);

		}

	}		
}

$x = new InstagramUnfollowNotFollowBack();
$x->Run();
// use at you own risk