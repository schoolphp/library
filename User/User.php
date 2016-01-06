<?php
namespace FW\User;

class User {
	static $data = NULL;
	static $access = NULL;
	static $error = '';
	static $autoupdate = true;
	static function Start() {
		if(isset($_SESSION['user']['id'])) {
			$res = q("
				SELECT *
				FROM `fw_users`
				WHERE `id` = ".(int)$_SESSION['user']['id']."
			");
			if(!$res->num_rows) {
				Authorization::logout();
				redirect('/');
			}
			$row = $res->fetch_assoc();
			if($row['access'] != 1) {
				Authorization::logout();
				$_SESSION['error'] = 'no-access';
				redirect('/');
			}
			self::$data = $row;
		} elseif(isset($_COOKIE['autologinid'],$_COOKIE['autologinhash'])) {
			$auth = new Authorization;
			if(!$auth->authByHash($_COOKIE['autologinid'],$_COOKIE['autologinhash'])) {
				Authorization::logout();
				redirect('/');
			}
		}
		
		if(!empty(self::$data['id']) && !empty(self::$autoupdate)) {
			q("
				UPDATE `fw_users` SET
				`browser` = '".(isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '')."',
				`ip` = '".es($_SERVER['REMOTE_ADDR'])."'
				WHERE `id` = ".(int)self::$data['id']."
			");
		}
	}
}
