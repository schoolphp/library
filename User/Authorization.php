<?php
namespace FW\User;

class Authorization {
	use Authorization\Config;

	function ipDefender() {
		q("
			DELETE FROM `fw_users_ip_defender`
			WHERE `date` < NOW() - INTERVAL 15 MINUTE
		");
		$res = q("
			SELECT `id`,`count`
			FROM `fw_users_ip_defender`
			WHERE `ip` = '".es($_SERVER['REMOTE_ADDR'])."'
			LIMIT 1
		");
		if($res->num_rows) {
			$row = $res->fetch_assoc();
			$res->close();
			if($row['count'] > 9) {
				return false;
			} else {
				q("
					UPDATE `fw_users_ip_defender` SET
					`date` = NOW(),
					`count` = `count` + 1
					WHERE `id` = ".(int)$row['id']."
				");
			}
		} else {
			q("
				INSERT INTO `fw_users_ip_defender` SET
				`date` = NOW(),
				`count` = 1,
				`ip` = '".es($_SERVER['REMOTE_ADDR'])."'
			");
		}
		return true;
	}

	function authByLoginPass($login,$password,$rememberme = false) {
		// IP CONTROL
		if(!$this->ipDefender()) {
			$this->errors = ['ip-defender'];
			return false;
		}
		
		$res = q("
			SELECT *
			FROM `fw_users`
			WHERE `login` = '".es($login)."'
			LIMIT 1
		");
		if(!$res->num_rows) {
			$this->errors = ['login'=>'wrong-login'];
			return false;
		}
		$row = $res->fetch_assoc();
		if(!password_verify($password, $row['password'])) {
			$this->errors = ['password'=>'wrong-password'];
			return false;
		}
		if($row['access'] != 1) {
			if($row['access'] == 0) {
				$this->errors = ['wrong-access-confirm'];
			} else {
				$this->errors = ['wrong-access'];
			}
			return false;
		}
		
		if($rememberme) {
			$row['hash'] = $this->rememberMe($row['id']);
		}
		\User::$data = $row;
		$_SESSION['user']['id'] = $row['id'];
		return true;
	}

	function rememberMe($id) {
		$hash = md5($id.microtime(true).rand(1,1000000).\Core::$DOMAIN);
		q("
			UPDATE `fw_users` SET
			`hash` = '".es($hash)."',
			`browser` = '".(isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '')."',
			`ip` = '".es($_SERVER['REMOTE_ADDR'])."'
			WHERE `id` = ".(int)$id."
		");
		setcookie('autologinid',$id,time()+2592000,'/',str_ireplace(['http://','https://','www.'],'',\Core::$DOMAIN),true,true);
		setcookie('autologinhash',$hash,time()+2592000,'/',str_ireplace(['http://','https://','www.'],'',\Core::$DOMAIN),true,true);
		return $hash;
	}

	function authByHash($id,$hash) {
/*
		if(!$this->ipDefender()) {
			$this->error = 'ip-defender';
			return false;
		}
*/
		$res = q("
			SELECT *
			FROM `fw_users`
			WHERE `id` = ".(int)$id."
			  AND `hash` = '".es($hash)."'
			LIMIT 1
		");
		if(!$res->num_rows) {
			$this->errors = ['wrong-hash'];
			return false;
		}
		$row = $res->fetch_assoc();
		if($row['access'] != 1) {
			if($row['access'] == 0) {
				$this->errors = ['wrong-access-confirm'];
			} else {
				$this->errors = ['wrong-access'];
			}
			return false;
		}
		
		if($this->browser) {
			if($row['browser'] != $_SERVER['HTTP_USER_AGENT']) {
				$this->errors = ['wrong-browser'];
				return false;
			}
		}

		if($this->ip == 1) {
			if($row['ip'] != $_SERVER['REMOTE_ADDR']) {
				$this->errors = ['wrong-ip'];
				return false;
			}
		}elseif($this->ip == 2) {
			preg_match('#(^\d+\.\d+\.)#isuU',$row['ip'],$matches);
			if(isset($matches[1]))
				$ipbase = $matches[1];

			preg_match('#(^\d+\.\d+\.)#isuU',$row['REMOTE_ADDR'],$matches);
			if(isset($matches[1]))
				$ipnow = $matches[1];

			if(isset($ipbase,$ipnow) && $ipbase != $ipnow) {
				$this->errors = ['wrong-ip'];
				return false;
			}
		}

		$row['hash'] = $this->rememberMe($row['id']);

		\User::$data = $row;
		$_SESSION['user']['id'] = $row['id'];
		return true;
	}

	public function getErrorMess() {
		require __DIR__.'/Authorization/language/'.\Core::$LANGUAGE['lang'].'.php';
		$errors = [];
		foreach($this->errors as $k=>$v) {
			if(isset($language[$this->errors[$k]]))
				$errors[$k] = $language[$this->errors[$k]];
			else
				throw new \Exception('Wrong error!');
		}
		return $errors;
	}

	static function logout() {
		if(isset($_SESSION['user'])) {
			unset($_SESSION['user']);
		}
		if(isset($_COOKIE['autologinid']) || isset($_COOKIE['autologinhash'])) {
			setcookie('autologinid','',time()-3600,'/',str_ireplace(['http://','https://','www.'],'',\Core::$DOMAIN),true,true);
			setcookie('autologinhash','',time()-3600,'/',str_ireplace(['http://','https://','www.'],'',\Core::$DOMAIN),true,true);
		}
	}
}
