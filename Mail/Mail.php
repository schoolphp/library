<?php

class Mail {
	use Mail\Config\Config;

	static function getHeaders($array = array()) {
		return array_merge(array(
			'MIME-Version: 1.0;',
			'From: =?UTF-8?b?'.self::$from_name.'?=<'.self::$from.'>',
			'Date: ' . date('r', $_SERVER['REQUEST_TIME']), // "Date: ". date('D, d M Y h:i:s O')
			'Message-ID: <' . $_SERVER['REQUEST_TIME'] . md5($_SERVER['REQUEST_TIME']) . '@' . $_SERVER['SERVER_NAME'] . '>', // Не уверен :/
			'Reply-To:'.self::$from,
			'Return-Path: '.self::$from,
			'X-Mailer: PHP/' . phpversion(),
			'Precedence: bulk', // FOR SPAM
		),$array);
	}

	static function attachFile($path,$name) {
		$fp = fopen($path,"rb");
		if (!$fp) {
			self::$error = 'Неверно открыть файл: '.$path;
			throw new Exception('Неверно открыть файл');
		}
		$file = fread($fp, filesize($path));
		fclose($fp);

		self::$files[] = array('file'=>$file,'name'=>$name);
	}

	static function send_mail() {
		if(!count(self::$files)) {
			$headers = self::getHeaders(array(
				'Content-Type: text/html; charset=utf-8',
			));
			$multipart = self::$html;
		} else {
			self::$boundary = "--".md5(uniqid(time()));
			$headers = self::getHeaders(array(
				'Content-Type: multipart/mixed; boundary="'.self::$boundary.'"',
			));
			  
			$multipart  = '--'.self::$boundary."\r\n";   
			$multipart .= "Content-Type: text/html; charset=utf-8\r\n";   
			$multipart .= "Content-Transfer-Encoding: base64\r\n";   
			$multipart .= "\r\n";
			$multipart .= chunk_split(base64_encode(self::$html));   
	
			foreach(self::$files as $v) {
				$multipart .=  "\r\n--".self::$boundary."\r\n";
				$multipart .= 'Content-Type: application/octet-stream; name="'.$v['name'].'"'."\r\n";
				$multipart .= "Content-Transfer-Encoding: base64\r\n";
				$multipart .= 'Content-Disposition: attachment; filename="'.$v['name'].'"'."\r\n";
				$multipart .= "\r\n";
				$multipart .= chunk_split(base64_encode($v['file']));
			}
			$multipart .= "\r\n--".self::$boundary."--\r\n";
		}

		if(!filter_var(self::$to, FILTER_VALIDATE_EMAIL)) {
			self::$error = 'Неверно указан email-адрес: '.self::$to;
			throw new Exception('Неверно указан email-адрес');
		}

		if(!empty(self::$unsubscribe)) {
			$headers[] = 'List-Unsubscribe: <'.self::$unsubscribe.'>';
		}

		if(!mail(self::$to, '=?utf-8?b?'.self::$subject.'?=', $multipart, implode("\r\n",$headers))) {
			self::$error = 'Письмо не было принято для передачи';
			throw new Exception('Письмо не было принято для передачи');
		} else {
			return true;  
		}  
		exit;  
	}
	
	static function htmlGet($file) {
		if(!file_exists($file)) {
			self::$error = 'Отсутствует HTML файл: '.$file;
			throw new Exception('Отсутствует HTML файл');
		}
		self::$html = file_get_contents($file);
		return true;
	}
	
	static function htmlBody($text) {
		return '<html>'.
			'<head>'.
			'<meta http-equiv="Content-Type" content="text/html; charset=utf-8">'.
			'<title>'.self::$subject.'</title>'.
			'</head>'.
			'<body>'.
			$text.
			'</body>'.
			'</html>';
	}
}
