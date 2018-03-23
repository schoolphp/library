<?php
class MailProxy extends \PHPMailer\PHPMailer\PHPMailer {
	public $freehtml = false;
	public function __construct($exceptions = false) {
		$this->CharSet = 'UTF-8';
		$this->setFrom(\Core::$NOREPLY, \Core::$SITENAME);
		$this->addReplyTo(\Core::$NOREPLY, \Core::$SITENAME);
		return parent::__construct($exceptions);
	}

	public function addReplyTo($address, $name = '') {
		$this->ClearReplyTos();
		return parent::addReplyTo($address, $name);
	}

	public function addAddress($address, $name = '') {
		$res = q("
			SELECT 1
			FROM `fw_unsubscribe`
			WHERE `email` = '".es($address)."'
		");
		if($res->num_rows) {
			if($this->exceptions) {
				throw new phpmailerException('E-mail blocked');
			}
			$this->ErrorInfo = 'E-mail blocked';
			return false;
		}
		return parent::addAddress($address, $name);
	}

	public function msgHTML($message, $basedir = '', $advanced = false) {
		if(empty($this->to[0][0])) {
			if($this->exceptions) {
				throw new phpmailerException('E-mail not insert');
			}
			$this->ErrorInfo = 'E-mail not insert';
			return false;
		}

		$key = time();
		$hash = \myHash($this->to[0][0].preg_replace('#^.{2}(.+).{2}$#u',"\\1",$key));
		$unsubscribe = \Core::$DOMAIN.'/login/unsubscribe?email='.urlencode($this->to[0][0]).'&amp;key='.urlencode($key).'&amp;hash='.urlencode($hash);
		$this->addCustomHeader("List-Unsubscribe",'<mailto:'.\Core::$ADMIN.'>, <'.$unsubscribe.'>');

		if($this->freehtml) {
			$message = '<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>'.$this->Subject.'</title>
</head>
<body style="max-width:600px; margin:auto; font-size:14px; line-height:1.5">
<div role="main">
'.$message.'
</div>
</body>
</html>';
		} else {
			$message = '<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>'.$this->Subject.'</title>
</head>
<body style="max-width:600px; margin:auto; font-size:14px; line-height:1.5">
<div role="banner" style="margin:auto; width:80%; min-width:300px; padding-bottom:10px; border-bottom:1px solid #FAFAD2; text-align: center;">LOGO</div>
<div role="main">'.$message.'</div>
<hr style="border: none; height: 1px; background-color: #eee; margin-top: 30px; margin-bottom: 30px;">
<div role="footer">
<p>If you\'d like to stop receiving all notifications, please follow the link<br>
<a href="'.$unsubscribe.'" target="_blank">'.$unsubscribe.'</a></p>
<p>--<br>
'.\Core::$SITENAME.' mail delivery system.<br>
Auto-generated e-mail, please, do not reply!</p>
</div>
</body>
</html>';
		}

		return parent::msgHTML($message, $basedir, $advanced);
	}
}
