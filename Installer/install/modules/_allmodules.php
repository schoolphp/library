<?php
function my_session_start() {
	if (ini_get('session.use_cookies') && isset($_COOKIE['PHPSESSID'])) {
		$sessid = $_COOKIE['PHPSESSID'];
	} elseif (!ini_get('session.use_only_cookies') && isset($_GET['PHPSESSID'])) {
		$sessid = $_GET['PHPSESSID'];
	} else {
		session_start();
		return true;
	}

	if (!preg_match('/^[-,a-zA-Z0-9]{1,128}$/', $sessid)) {
		return false;
	}
	session_start();

   return true;
}
if (!my_session_start()) {
    session_id(uniqid());
    session_start();
    session_regenerate_id();
}

class User extends \FW\User\User {
	static $avatar = '';
	static $datas = ['id','role','login','avatar'];
}
User::start(isset($_SESSION['user']['id']) ? ['id' => (int)$_SESSION['user']['id']] : []);

if(!isset($_SESSION['antixsrf'])) {
	$_SESSION['antixsrf'] = md5(time().$_SERVER['REMOTE_ADDR'].(isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : rand(1,99999)));
}
