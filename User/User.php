<?php
namespace FW\User;

class User {
	static $id = 0;
	static $login = '';
	static $role = '';
	static protected $datas = ['id','role','login'];
	static $data = NULL;
	static $autoupdate = true;
	static function Start($auth = []) {
		$called_class = get_called_class();

		if(!count($auth) && isset($_COOKIE['autologinid'],$_COOKIE['autologinhash'])) {
			$auth = new Authorization;
			if(!$auth->authByHash($_COOKIE['autologinid'],$_COOKIE['autologinhash'])) {
				Authorization::logout();
				redirect('/');
			}
			$auth = ['id' => (int)$_SESSION['user']['id']];
		}

		if(count($auth)) {
			$where = [];
			foreach($auth as $k=>$v) {
				$where[] = "`".es($k)."` = '".es($v)."'";
			}
			$res = q("
				SELECT `access`".(count($called_class::$datas) ? ',`'.implode('`,`',$called_class::$datas).'`' : '')."
				FROM `fw_users`
				WHERE ".implode(" AND ",$where)."
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
			foreach($called_class::$datas as $k=>$v) {
				$called_class::$$v = $row[$v];
			}
			if(count($row)) {
				self::$data = $row;
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
	static function getDatas() {
		return self::$datas;
	}
}
