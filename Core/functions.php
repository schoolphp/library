<?php
function wtf($array, $stop = false) {
	echo '<pre>'.htmlspecialchars(print_r($array,1)).'</pre>';
	if(!$stop) {
		exit();
	}
}

function trimAll($el,$array = false) {
	if(!is_array($el)) {
		$el = trim($el);
	} else {
		$el = array_map('trimAll',$el);
	}
	return $el;
}

function intAll($el,$array = false) {
	if(!is_array($el)) {
		$el = (int)($el);
	} else {
		$el = array_map('intAll',$el);
	}
	return $el;
}

function floatAll($el,$array = false) {
	if(!is_array($el)) {
		$el = (float)($el);
	} else {
		$el = array_map('floatAll',$el);
	}
	return $el;
}

function hc($el,$array = false) {
	if(!$array && !is_string($el)) {
		throw new Exception('Был передан массив, необходимо передавать строку');
	}
	if(!is_array($el)) {
		$el = htmlspecialchars($el);
	} else {
		$el = array_map('hc',$el,$array);
	}
	return $el;
}


function myAuthoload($class) {
	$module = (isset($_GET['_module']) ? $_GET['_module'] : 'main');
	if(strpos($class,'\\') === false) {
		if(file_exists('./modules/'.$module.'/model/'.$class.'.php')) {
			require_once './modules/'.$module.'/model/'.$class.'.php';
		} elseif(file_exists('./library/'.$class.'/'.$class.'.php')) {
			require_once './library/'.$class.'/'.$class.'.php';
		}
	} else {
		$class = str_replace('\\',DIRECTORY_SEPARATOR,$class);

		if(file_exists('./modules/'.$module.'/model/'.$class.'.php')) {
			require_once './modules/'.$module.'/model/'.$class.'.php';
		} elseif(file_exists('./library/'.$class.'.php')) {
			require './library/'.$class.'.php';
		}

	}
}
spl_autoload_register('myAuthoload');

function q($query,$key = 0) {
	if(Core::$EVENTS) \FW\Event\Event::trigger('BeforeQuery');
	$res = \FW\DB\DB::_($key)->query($query);
	if(Core::$EVENTS) \FW\Event\Event::trigger('AfterQuery');
	if($res === false) {
		$info = debug_backtrace();
		$error = "QUERY: ".$query."<br>\r\n".\FW\DB\DB::_($key)->error."<br>\r\n".
			"file: ".$info[0]['file']."<br>\r\n".
			"line: ".$info[0]['line']."<br>\r\n".
			"date: ".date("Y-m-d H:i:s")."<br>\r\n".
			"===================================";

		file_put_contents('./logs/mysql.log',strip_tags($error)."\r\n\r\n",FILE_APPEND);
		echo $error;
		exit();
	}
	return $res;
}

function es($el,$key = 0) {
	return \FW\DB\DB::_($key)->real_escape_string($el);
}

set_error_handler('\FW\MyErrorHandler\MyErrorHandler::handler');

function shutDownFunction() {
	$error = error_get_last();
	if(Core::$EVENTS) {
		chdir(Core::$DIRECTORY);
		\FW\Event\Event::trigger('ShutDownSystem');
	}
	if (is_array($error)) {
		chdir(Core::$DIRECTORY);
		if(Core::$EVENTS) \FW\Event\Event::trigger('ShutDownSystemError');
		return \FW\MyErrorHandler\myErrorHandler::handler($error['type'],$error['message'],$error['file'],$error['line']);
	}
}
register_shutdown_function('shutdownFunction');

function createUrl($url = '', $clearget = true, $lang = '') {
	if($url == 'this') {
		if(empty($_SERVER['REQUEST_URI'])) {
			$temp = '/';
		} else {
			$temp = $_SERVER['REQUEST_URI'];
			if($clearget) {
				$temp = preg_replace('#\?.*$#iusU','',$temp);
			}
		}
		if(!empty($lang)) {
			if(stripos($temp,'/'.Core::$LANGUAGE['lang'].'/') === 0) {
				$temp = preg_replace('#^\/'.Core::$LANGUAGE['lang'].'\/#iusU',($lang != Core::$LANGUAGE['default'] ? '/'.$lang : '').'/',$temp);
			} elseif($lang != Core::$LANGUAGE['default']) {
				$temp = '/'.$lang.$temp;
			}
		}

		return $temp;
	}

	if(empty($lang)) {
		$lang = (Core::$LANGUAGE['status'] && Core::$LANGUAGE['lang'] != Core::$LANGUAGE['default'] ? '/'.Core::$LANGUAGE['lang'] : '');
	}

	if(empty($url))
		return (empty($lang) ? '/' : $lang);

	if(!is_array($url)) {
		return $lang.'/'.trim($url,'/');
	} else {
		$temp = $lang;
		$module = (isset($url['_module']) ? $url['_module'] : (isset($_GET['_module']) ? $_GET['_module'] : 'main'));
		$temp .= '/'.$module;

		if(!count(Core::$SITEMAP[$module])) {
			$defaultpage = 'main';
		} else {
			reset(Core::$SITEMAP[$module]);
			$defaultpage = key(Core::$SITEMAP[$module]);
		}

		if(isset($url['_page'])) {
			$page = $url['_page'];
		} elseif(isset($_GET['_module']) && $_GET['_module'] == $module) {
			$page = $_GET['_page'];
		} else {
			$page = $defaultpage;
		}
		$temp .= (!empty($page) && $page != $defaultpage ? '/'.$page : '');

		if(isset(Core::$SITEMAP[$module][$page]) && is_array(Core::$SITEMAP[$module][$page])) {
			foreach(Core::$SITEMAP[$module][$page] as $k=>$v) {
				if(isset($url[$k])) {
					$temp .= '/'.$url[$k];
					unset($url[$k]);
				} elseif(!empty($v['req']) || count($url)) {
					if(isset($_GET[$k])) {
						$temp .= '/'.$_GET[$k];
					} elseif(!empty($v['default'])) {
						$temp .= '/'.$v['default'];
					} else {
						throw new Exception('Cant create Url. No require argument!');
					}
				}
			}
		}

		if(!$clearget && isset($_SERVER['REQUEST_URI']) && preg_match('#\?.*$#iusU',$_SERVER['REQUEST_URI'],$matches)) {
			$temp .= $matches[0];
		}
		if(defined('ADMIN')) {
			$temp = '/admin'.$temp;
		}
		return $temp;
	}
}

function redirect($link = '',$clearget = true) {
	$url = createUrl($link,$clearget);
	if (!headers_sent()) {
		header("Location: ".$url);
	} else {
		echo '<script>window.location.href="'.$url.'";</script><noscript><meta http-equiv="refresh" content="0;url='.$url.'"></noscript>';
	}
	exit;
}

function urlencoder($url,$action = 'encode') {
	$from = '/';
	$to = '--slash--';
	if($action == 'encode') {
		return str_replace($from,$to,$url);
	} else {
		return str_replace($to,$from,$url);
	}
}

function urlFix($text) {
	$text = preg_replace('#[^a-zа-яё\s\_\-\w\d]#ius','',$text);
	$text = preg_replace('#[\s\_]#ius','-',$text);
	$text = preg_replace('#\-{2,}#ius','-',$text);
	$text = mb_strtolower($text);
	$text = htmlspecialchars($text);
	return $text;
}

function getEng($text) {
	$tr = array(
		"А"=>"A","Б"=>"B","В"=>"V","Г"=>"G",
		"Д"=>"D","Е"=>"E","Ж"=>"J","З"=>"Z","И"=>"I",
		"Й"=>"Y","К"=>"K","Л"=>"L","М"=>"M","Н"=>"N",
		"О"=>"O","П"=>"P","Р"=>"R","С"=>"S","Т"=>"T",
		"У"=>"U","Ф"=>"F","Х"=>"H","Ц"=>"TS","Ч"=>"CH",
		"Ш"=>"SH","Щ"=>"SCH","Ъ"=>"","Ы"=>"YI","Ь"=>"",
		"Э"=>"E","Ю"=>"YU","Я"=>"YA","а"=>"a","б"=>"b",
		"в"=>"v","г"=>"g","д"=>"d","е"=>"e","ж"=>"j",
		"з"=>"z","и"=>"i","й"=>"y","к"=>"k","л"=>"l",
		"м"=>"m","н"=>"n","о"=>"o","п"=>"p","р"=>"r",
		"с"=>"s","т"=>"t","у"=>"u","ф"=>"f","х"=>"h",
		"ц"=>"ts","ч"=>"ch","ш"=>"sh","щ"=>"sch","ъ"=>"y",
		"ы"=>"yi","ь"=>"'","э"=>"e","ю"=>"yu","я"=>"ya",
		"ё"=>"e","Ё"=>'E',
		"."=>"-"," "=>"-","?"=>"_","/"=>"_","\\"=>"_",
		"*"=>"-",":"=>"_","*"=>"_","\""=>"_","<"=>"_",
		">"=>"-","|"=>"-"
	);
	return mb_strtolower(strtr($text,$tr),'UTF-8');
}

function myHash($var) {
	$salt = 'mmomoj';
	$salt2 = 'wamfwmlo';
	$var = crypt(md5($var.$salt),$salt2);
	return $var;
}

function doError($key) {

}

function isAdmin() {
	if(isset($_SESSION['user']['id']) && $_SESSION['user']['id'] == 1) {
		return true;
	}
	return false;
}
