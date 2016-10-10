<?php
namespace FW\User;
use \Core;

class Registration {
	function regist($login,$password,$email) {
		$pass = password_hash($password,PASSWORD_DEFAULT);
		q("
			INSERT INTO `fw_users` SET
			`login` = '".es($login)."',
			`password` = '".es($pass)."',
			`email` = '".es($email)."'
		");
		$id = \DB::_()->insert_id;
		$hash = md5($id.microtime(true).rand(1,1000000).$pass);
		q("
			UPDATE `fw_users` SET
			`hash` = '".es($hash)."'
			WHERE `id` = ".$id."
		");
		return $this->sendActivate($id,$hash,$email);
	}

	function againActivate($email) {
		$res = q("
			SELECT `id`,`hash`
			FROM `fw_users`
			WHERE `email` = '".es($email)."'
			  AND `access` = 0
			LIMIT 1
		");
		if($res->num_rows) {
			$row = $res->fetch_assoc();
			return $this->sendActivate($row['id'],$row['hash'],$email);
		} else {
			return false;
		}
	}

	function sendActivate($id,$hash,$email) {
		try {
			$mail = new \FW\MailProxy\MailProxy(true);
			$mail->Subject = 'Вы зарегистрировались на сайте '.Core::$DOMAIN;
			$mail->addAddress($email,$email);
			$mail->msgHTML('<div>Вы успешно зарегистрировались на сайте.</div><div>Для завершения регистрации пройдите по ссылке:<br><a href="'.Core::$DOMAIN.'/login/activate/'.$id.'/'.$hash.'">'.Core::$DOMAIN.'/login/activate/'.$id.'/'.$hash.'</a></div>');
			$mail->send();
			unset($mail);
		} catch(\Exception $e) {
			trigger_error('Ошибка регистрации: '. print_r($e,1));
		}
		return true;
	}
	
	function activate($id,$hash) {
		q("
			UPDATE `fw_users` SET
			`access` = 1
			WHERE `id` = ".(int)$id."
			  AND `access` = 0
			  AND `hash` = '".es($hash)."'
		");
		if(!\DB::_()->affected_rows) {
			return false;
		}
		return true;
	}
}
