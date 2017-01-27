<?php
namespace FW\MailProxy;

class MailProxy extends \PHPMailer\PHPMailer\PHPMailer {
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
		return parent::msgHTML($message, $basedir, $advanced);
	}
}
