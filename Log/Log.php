<?php
namespace FW\Log;

class Log implements \Psr\Log\LoggerInterface
{
	private $backtrace = '';
	public function __construct()
	{
		$i = 1;
		$tmp = debug_backtrace();
		foreach($tmp as $v) {
			$this->backtrace .= $i++.') '.$v['file'].'('.$v['line'].'): '.($v['class'] ?? '').($v['type'] ?? '').($v['function'] ?? '')."\r\n";
		}
	}

	private function mail($error)
	{
		$res = \q("SELECT `timer` FROM `fw_log_timer` WHERE `timer` < NOW() - INTERVAL 10 MINUTE");
		if($res->num_rows) {
			\q("UPDATE `fw_log_timer` SET `timer` = NOW()");

			try {
				$mail = new \MailProxy(true);

				$mail->Subject = \Core::$DOMAIN.': произошла критическая ошибка!';
				$mail->msgHTML('<p>'.$error.'</p><p>Backtrace:<br>'.nl2br(htmlspecialchars($this->backtrace)).'</p>');

				foreach(\Core::$ALARMMAIL as $v) {
					$mail->addAddress(\Core::$ALARMMAIL);
					$mail->send();
				}
			} catch(\Exception $e) {
				(new Log())->error('Mail notification not working: '.$e->getMessage());
			}
		}
	}

	private function file($error)
	{
		$error = $error."\r\n".
			'--date: '.date("Y-m-d H:i:s")."\r\n".
			"Trace:\r\n".
			$this->backtrace.
			"\r\n===================================";

		file_put_contents('./logs/mysql.log',$error."\r\n\r\n",FILE_APPEND);

	}

	private function mysql($error)
	{
		\q("
			INSERT INTO `fw_log` SET 
			`page` = '".\es($_GET['route'] ?? '')."',
			`text` = '".\es($error)."',
			`ip` = '".\es($_SERVER['REMOTE_ADDR'] ?? '')."'
		");
	}

	private function notification($error)
	{
		\q("
			INSERT INTO `fw_log_notification` SET
			`title` = 'Ошибка на сервере',
			`text` = '".\es($error)."\r\nTrace:\r\n".\es($this->backtrace)."',
			`key` = 'role',
			`value` = 'admin'
		");
	}

	private function sms($error)
	{
		if(!count(\Core::$ERRORS['sms_phone'])) {
			(new Log)->alert('SMS PHONE not insered');
		} elseif(empty(\Core::$ERRORS['sms_login']) || empty(\Core::$ERRORS['sms_pass'])) {
			(new Log)->alert('SMS LOGIN or PASS not insered');
		}

		file_get_contents('https://smsc.ru/sys/send.php?login='.\Core::$ERRORS['sms_login'].'&psw='.mb_strtolower(md5(\Core::$ERRORS['sms_pass'])).'&phones='.implode(',', \Core::$ERRORS['sms_phone']).'&mes='.mb_substr($error,0,60));
	}

	public function emergency($message, array $context = array())
	{
		return $this->alert($message, $context);
	}

	public function alert($message, array $context = array())
	{
		if(\Core::$ERRORS['sms']) {
			$this->sms($message);
		}

		if(\Core::$ERRORS['mail']) {
			$this->mail($message);
		}

		return $this->critical($message, $context);
	}

	public function critical($message, array $context = array())
	{
		return $this->error($message, $context);
	}

	public function error($message, array $context = array())
	{
		if(\Core::$ERRORS['file']) {
			$this->file($message);
		}

		if(\Core::$ERRORS['mysql']) {
			$this->mysql($message);
		}

		if(\Core::$ERRORS['notification']) {
			$this->notification($message);
		}

		return $this->warning($message, $context);
	}

	public function warning($message, array $context = array())
	{
		return $this->notice($message, $context);
	}

	public function notice($message, array $context = array())
	{
		return $this->info($message, $context);
	}

	public function info($message, array $context = array())
	{
		return $this->debug($message, $context);
	}

	public function debug($message, array $context = array())
	{
		if(\Core::$ERRORS['show']) {

		}

		return $this->log($message, $context);
	}

	public function log($level, $message, array $context = array())
	{
		return true;
	}
}