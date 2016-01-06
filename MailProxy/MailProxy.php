<?php
namespace FW\MailProxy;

class MailProxy extends \PHPMailer\PHPMailer\PHPMailer {
    public function __construct($exceptions = false) {
		$this->CharSet = 'UTF-8';
		$this->setFrom(Core::$NOREPLY, Core::$SITENAME);
		$this->addReplyTo(Core::$NOREPLY, Core::$SITENAME);
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
		$hash = myHash($this->to[0][0].preg_replace('#^.{2}(.+).{2}$#u',"\\1",$key));
		$unsubscribe = 'https://school-php.com/login/unsubscribe?email='.urlencode($this->to[0][0]).'&amp;key='.urlencode($key).'&amp;hash='.urlencode($hash);
		$this->addCustomHeader("List-Unsubscribe",'<mailto:unsubscribe@school-php.com>, <'.$unsubscribe.'>');
		$this->addAttachment('./skins/img/logo.png');
		$this->addAttachment('./skins/img/edu4.png');
		$this->addAttachment('./skins/img/mailbg.png');

		$message = '<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>'.hc($this->Subject).'</title>
</head>
<body>
<div style="background-color:#eee; padding:20px;">
<div style="background:white url(https://school-php.com/skins/img/mailbg.png) no-repeat top right;border: 1px solid #dfdfdf;width: 560px;padding: 0px 25px 25px;margin: 0 auto;">
<div role="banner" style="padding:15px;border-bottom: 1px solid #eee;"><h1 style="margin-bottom:0px; padding-bottom:0px;"><img src="https://school-php.com/skins/img/logo.png" alt="Школа программирования"><br>Школа PHP Программирования</h1></div>
<div role="main" style="margin: 30px 10px 50px 10px; font-size: 14px; line-height: 1.5;">
'.$message.'
</div>
<div role="footer" style="padding-top:10px; border-top: 1px solid #eee;">
<p>
Автоматическая система рассылок school-php.com!<br>
Вы можете отписаться от писем пройдя по следующей ссылке:<br>
<a href="'.$unsubscribe.'">'.$unsubscribe.'</a>
</p>
</div>
</div>
<div align="center"><img src="https://school-php.com/skins/img/edu4.png"></div>
</div>
</body>
</html>';

		return parent::msgHTML($message, $basedir, $advanced);
	}
}
