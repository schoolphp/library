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

   if (!preg_match('/^[a-z0-9]{32}$/', $sessid)) {
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

class User extends \FW\User\User {}
//include './library/User/User.php';
User::start();

if(!isset($_SESSION['antixsrf'])) {
	$_SESSION['antixsrf'] = md5(time().$_SERVER['REMOTE_ADDR'].(isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : rand(1,99999)));
}
