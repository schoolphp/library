<?php
namespace FW\MyErrorHandler;
use \Core;

class MyErrorHandler {
	static public $key = 0;
	static public $user_err = [256,512,1024];
	static public $tips = [
		'main' => 'main',
		'Undefined variable' => 'undefined_variable',
	];
	static public function handler($errno, $errstr, $errfile, $errline) {
		if(!error_reporting()) {
			return true;
		}

		if(self::$key > 3) {
			return true;
		} else {
			++self::$key;
		}

		if(file_exists(__DIR__.'/language/'.Core::$LANGUAGE['lang'].'.php')) {
			$text = include __DIR__.'/language/'.Core::$LANGUAGE['lang'].'.php';
		} else {
			$text = include __DIR__.'/language/ru.php';
		}

		$errors = [1 => 'Error',2 => 'Warning',4 => 'Parse',8 => 'Notice',16 => 'Core error',32 => 'Core warning',64 => 'Complite error',128 => 'Complite warning',256 => 'User Error',512 => 'User Warning',1024 => 'User Notice',2048 => 'Strict',4096 => 'Recoverable error',8192 => 'Deprecated',16384 => 'User Deprecated',32767 => 'All'];

		$trace = '';
		if(Core::$ERRORS['trace']) {
			$trace = "\r\nTrace:\r\n".print_r($GLOBALS,1);
		}

		if(Core::$ERRORS['errlvl'] > 1 || (Core::$ERRORS['errlvl'] == 1 && !in_array($errno,self::$user_err))) {
			if(Core::$ERRORS['file']) {
				file_put_contents('./logs/php.log',date("Y-m-d H:i:s").": ".$errors[$errno].': ['.$errstr."]\r\nat file ".$errfile.' in line '.$errline.$trace."\n\r=================================================\n\r\n\r",FILE_APPEND);
			}
			if(Core::$ERRORS['email']) {
				try {
					$mail = new \FW\MailProxy\MailProxy(true);
					$mail->Subject = 'На сайте '.Core::$DOMAIN.' произошла ошибка';
					$mail->addAddress(Core::$ADMIN,Core::$ADMIN);
					$mail->msgHTML('<div>'.date("Y-m-d H:i:s").": ".$errors[$errno].': ['.$errstr.'] at file '.$errfile.' in line '.$errline.'.<br>'.htmlspecialchars($trace).'</div>');
					$mail->send();
					unset($mail);
				} catch(\Exception $e) {
					trigger_error('Ошибка регистрации: '. print_r($e,1));
				}
				return true;
			}
			if(Core::$ERRORS['mysql']) {
				q("
					CREATE TABLE IF NOT EXISTS `fw_php_logs` (
					  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
					  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
					  `link` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
					  `error` text COLLATE utf8_unicode_ci NOT NULL,
					  `trace` longtext COLLATE utf8_unicode_ci NOT NULL,
					  PRIMARY KEY (`id`),
					  KEY `link` (`link`)
					) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1
				");
				q("
					INSERT INTO `fw_php_logs` SET
					`link` = '".es($_SERVER['REQUEST_URI'])."',
					`error` = '".es($errors[$errno].': ['.$errstr.'] в файле '.$errfile.' на линии '.$errline)."',
					`trace` = '".es($trace)."'
				");
			}
		}
		
		if(Core::$ERRORS['show']) {
			$tip = 'main';
			preg_match('#^(.*)\:#iusU',$errstr,$matches);
			if(!empty($matches[1])) {
				$matches[1] = trim($matches[1]);
				if(isset(self::$tips[$matches[1]])) {
					$tip = self::$tips[$matches[1]];
				}
			}
			echo '<div style="background-color:white; border:2px dotted red; padding:10px;">
			  <div style="background-color:#F0D9DA;">'.$errors[$errno].': ['.$errstr.'] '.$text['in_file'].' '.$errfile.' '.$text['on_line'].' '.$errline.'</div>
			  <div align="center">
				<a href="javascript:void(0)" onclick="document.getElementById(\'randerrorkeyGLOBALS'.($randomkey = rand(1,9999)).'\').style.display=\'block\'; return false;">'.$text['show'].' $GLOBALS</a> |
				<a href="javascript:void(0)" onclick="document.getElementById(\'randerrorkeyDEBUG'.($randomkey).'\').style.display=\'block\'; return false;">'.$text['show'].' Debug Backtrace</a> |
				<a href="javascript:void(0)" onclick="document.getElementById(\'randerrorkeyTIP'.($randomkey).'\').style.display=\'block\'; return false;">'.$text['show_tips'].'</a>
			  </div>
			  <div style="display:none; padding:5px; border: 1px dotted orange;" id="randerrorkeyGLOBALS'.$randomkey.'"><h1>GLOBALS:</h1><pre>'.htmlspecialchars(print_r($GLOBALS,1)).'</pre></div>
			  <div style="display:none; padding:5px; border: 1px dotted orange;" id="randerrorkeyDEBUG'.$randomkey.'"><h1>DEBUG BACKTRACE</h1><pre>';
				debug_print_backtrace();
			  echo '</pre></div>
			  <div style="display:none; padding:5px; border: 1px dotted orange;" id="randerrorkeyTIP'.$randomkey.'"><h1>TIPS</h1>';
			  include __DIR__.'/view/'.$tip.'.tpl';
			  echo '</div>
			  </div>';
		} elseif(Core::$ERRORS['redirect']) {
			if($errno == E_USER_ERROR) {
				header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");
				header('Refresh: 3; URL='.(Core::$LANGUAGE['status'] ? '/'.Core::$LANGUAGE['lang'] : '').'/404');
			} else {
				header("HTTP/1.1 307 Temporary Redirect");
				header('Refresh: 3; URL='.(Core::$LANGUAGE['status'] ? '/'.Core::$LANGUAGE['lang'] : '').'/error?error='.urlencode($text['error_mess']));
			}
			echo '<script>setTimeout(function() {window.location.href="'.(Core::$LANGUAGE['status'] ? '/'.Core::$LANGUAGE['lang'] : '').'/'.($errno == E_USER_ERROR ? '404' : 'error?error='.urlencode($text['error_mess'])).'";},3000); document.write(\'<div style="border:2px dotted red;">'.$text['redirect_text'].'</div>\');</script>';
			exit;
		} else {
			
		}

		self::$key = 0;

		if(Core::$ERRORS['stop']) {
			exit;
		}

		if($errno == E_USER_ERROR) {
			exit;
		}
		/* Не запускаем внутренний обработчик ошибок PHP
		return true;
		*/
		return null;
	}
}
