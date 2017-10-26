<?php
namespace FW\Installer;
use \FW\Installer\Sitemap;

class Installer
{
	static $log = '';
	static $step = 0;
	static $installDir = __DIR__.'/install';
	static $basedir = '';

	static function addLog($type,$text) {
		self::$log .= '<div class="'.$type.'">- '.$text.'</div>';
		if($type == 'error') {
			throw new \Exception($text);
		}
	}

	static function init() {
		if(!file_exists(self::$basedir.'/skins/components/bootstrap/bootstrap.min.css')
			|| !file_exists(self::$basedir.'/skins/components/bower/jquery/dist/jquery.min.js')
		) {
			self::addLog('error','Отсутствует Bootstrap или Jquery. Запусите bower для загрузки пакетов на сайт!');
		}
		if(!isset($_SESSION['created'],$_SESSION['db-login'],$_SESSION['db-pass'],$_SESSION['db-local'],$_SESSION['db-name'],
			$_SESSION['domain'],$_SESSION['site-name'],$_SESSION['email'],$_SESSION['login'],$_SESSION['password'],$_SESSION['htaccess']))
		{
			self::addLog('error','Необходимо заполнить форму!');
		} else {
			self::addLog('success','Форма заполнена корректно!');
		}
	}

	static function copyFile($file) {
		if(!file_exists(self::$basedir.$file)) {
			if(!file_exists(self::$installDir.'/'.$file)) {
				self::addLog('error', 'Файл `'.$file.'` не существует!');
			} else {
				$temp = file_get_contents(self::$installDir.$file);
				if(file_put_contents(self::$basedir.$file, $temp) !== false) {
					self::addLog('success', 'Файл `'.$file.'` создан');
				} else {
					self::addLog('error', 'Файл `'.$file.'` не удалось создать!');
				}
			}
		} else {
			self::addLog('warning','Файл `'.$file.'` ранее был создан');
		}
	}

	static function copyModule($module,$files,$dir = '') {
		if(!file_exists(self::$basedir.'/modules/'.$dir.$module)) {
			self::makeDir('/modules/'.$dir.$module.'/view');
			foreach($files as $v) {
				if(file_exists(self::$installDir.'/modules/'.$dir.$module.'/sitemap/sitemap.php')) {
					self::makeDir('/modules/'.$dir.$module.'/sitemap');
					file_put_contents(self::$basedir.'/modules/'.$dir.$module.'/sitemap/sitemap.php', file_get_contents(self::$installDir.'/modules/'.$dir.$module.'/sitemap/sitemap.php'));
				}
				file_put_contents(self::$basedir.'/modules/'.$dir.$module.'/'.$v.'.php', file_get_contents(self::$installDir.'/modules/'.$dir.$module.'/'.$v.'.php'));
				file_put_contents(self::$basedir.'/modules/'.$dir.$module.'/view/'.$v.'.tpl', file_get_contents(self::$installDir.'/modules/'.$dir.$module.'/view/'.$v.'.tpl'));
			}
			self::addLog('success', 'Модуль `'.$module.'` создан');
		} else {
			self::addLog('warning','Модуль `'.$module.'` ранее был создан');
		}
	}
	static function makeDir($dir) {
		if(!file_exists(self::$basedir.$dir)) {
			if(!mkdir(self::$basedir.$dir,0775,true)) {
				self::addLog('error','Не удалось создать директорию '.$dir.'. Возможно нет прав на рабочий каталог');
			} else {
				self::addLog('success','Директория `'.$dir.'` была создана');
			}
		} else {
			self::addLog('warning','Директория `'.$dir.'` ранее была создана!');
		}
	}

	static function createDir() {
		self::$step = 1;

		self::makeDir('/config');
		self::makeDir('/skins/css');
		self::makeDir('/skins/img');
		self::makeDir('/skins/js');
		self::makeDir('/skins/libs');
		self::makeDir('/language');
		self::makeDir('/modules/admin');
		self::makeDir('/cache/block');
		self::makeDir('/cache/file');
		self::makeDir('/logs');
		self::makeDir('/library');
		self::makeDir('/uploads/tmp');

		if(!file_exists(self::$basedir.'/config/config.php')) {
			if(!isset($_SESSION['created'],$_SESSION['db-login'],$_SESSION['db-pass'],$_SESSION['db-local'],
				$_SESSION['db-name'],$_SESSION['domain'],$_SESSION['site-name'],$_SESSION['email'],$_SESSION['htaccess'],
				$_SESSION['login'],$_SESSION['password'])
			) {
				self::addLog('error','Для установки Вы должны заполнить форму!');
			} else {
				$content = file_get_contents(self::$installDir.'/config/config.php');
				$content = preg_replace_callback('#\$_POST\["(.*?)"\]#iu',function ($matches) {
					return $_SESSION[$matches[1]];
				}, $content);
				file_put_contents(self::$basedir.'/config/config.php',$content);
				self::addLog('success','`config.php` создан!');
			}
		} else {
			self::addLog('warning','Файл `/config/config.php` ранее был создан');
		}

		if(!file_exists(self::$basedir.'/robots.txt')) {
			$content = file_get_contents(self::$installDir.'/robots.txt');
			$content = preg_replace_callback('#\$_POST\["(.*?)"\]#iu',function ($matches) {
				return $_SESSION[$matches[1]];
			}, $content);
			file_put_contents(self::$basedir.'/robots.txt',$content);
			self::addLog('success','`robots.txt` создан!');
		} else {
			self::addLog('warning','Файл `/robots.txt` ранее был создан');
		}

		if(!file_exists(self::$basedir.'/sitemap.xml')) {
			$content = file_get_contents(self::$installDir.'/sitemap.xml');
			$content = preg_replace_callback('#\$_POST\["(.*?)"\]#iu',function ($matches) {
				return $_SESSION[$matches[1]];
			}, $content);
			file_put_contents(self::$basedir.'/sitemap.xml',$content);
			self::addLog('success','`sitemap.xml` создан!');
		} else {
			self::addLog('warning','Файл `/sitemap.xml` ранее был создан');
		}

		if(!file_exists(self::$basedir.'/.htaccess')) {
			if(!in_array($_SESSION['htaccess'],['full','openserver'])) {
				self::addLog('error','Файл `/.htaccess` нельзя создать, передан неправильный параметр: '.htmlspecialchars($_SESSION['htaccess']));
			} else {
				if(file_put_contents(self::$basedir.'/.htaccess', file_get_contents(self::$installDir.'/.htaccess-'.$_SESSION['htaccess']))) {
					self::addLog('success', 'Файл `/.htaccess` создан');
				} else {
					self::addLog('error','Файл `/.htaccess` не удалось создать!');
				}
			}
		} else {
			self::addLog('warning','Файл `/.htaccess` ранее был создан');
		}

		self::copyFile('/language/ru.php');
		self::copyFile('/config/sitemap_core.php');
		self::copyFile('/modules/_allmodules.php');
		self::copyModule('static',['404','error','jslogerror']);
		self::copyModule('main',['main']);
		self::copyModule('login',['main','registration','restoration','unsubscribe','exit','activate']);

		self::copyFile('/skins/index.tpl');
		self::copyFile('/skins/admin.tpl');
		self::copyFile('/skins/stubroutine.tpl');

		self::copyFile('/skins/css/admin.less');
		self::copyFile('/skins/css/admin.min.css');
		self::copyFile('/skins/css/begin.less');
		self::copyFile('/skins/css/begin.min.css');
		self::copyFile('/skins/css/end.less');
		self::copyFile('/skins/css/end.min.css');
		self::copyFile('/skins/css/normalize.less');
		self::copyFile('/skins/css/normalize.min.css');
		self::copyFile('/skins/css/bootstrap.min.css');

		self::copyFile('/skins/img/logo.jpg');
		self::copyFile('/skins/img/logo2.jpg');
		self::copyFile('/skins/img/logo2-bg.jpg');
		self::copyFile('/skins/js/scripts.js');
		self::copyFile('/skins/js/scripts.min.js');

		self::copyFile('/logs/js.log');
		self::copyFile('/logs/my.log');
		self::copyFile('/logs/mysql.log');
		self::copyFile('/logs/php.log');
		self::copyFile('/logs/slowquery.log');

		self::copyFile('/favicon.ico');
		self::copyFile('/touch-icon-ipad.png');
		self::copyFile('/touch-icon-ipad-retina.png');
		self::copyFile('/touch-icon-iphone.png');
		self::copyFile('/touch-icon-iphone-retina.png');

		self::copyFile('/config/sitemap_admin.php');
		self::copyFile('/config/sitemap_admin_core.php');
		self::copyFile('/modules/admin/_allmodules.php');
		self::copyModule('main',['main'],'admin/');
		self::copyModule('modules',['main'],'admin/');
		self::copyModule('static',['404'],'admin/');

		return true;
	}

	static function createDB() {
		self::$step = 2;
		$link = @new \mysqli($_SESSION['db-local'],$_SESSION['db-login'],$_SESSION['db-pass'],$_SESSION['db-name']); // WARNING
		if ($link->connect_error) {
			self::addLog('error','Не удалось подключиться к Базе Данных: '.$link->connect_errno.', '.$link->connect_error);
		}
		if(!$link->set_charset("utf8")) {
			self::addLog('error','Ошибка при загрузке набора символов utf8: '.$link->error);
		}
		if(!$link->query("
			CREATE TABLE IF NOT EXISTS `fw_shortlink` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `short` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
			  `full` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
			  PRIMARY KEY (`id`),
			  UNIQUE KEY `ixSF` (`short`,`full`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
		")) {
			self::addLog('error','Ошибка при работе с БД: '.$link->error);
		} else {
			self::addLog('success', 'MySQL Таблица `fw_shortlink` создана');
		}

		if(!$link->query("
			CREATE TABLE IF NOT EXISTS `fw_cache_data` (
			  `key` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
			  `value` text COLLATE utf8mb4_unicode_ci NOT NULL,
			  `expire` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			  PRIMARY KEY (`key`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
		")) {
			self::addLog('error','Ошибка при работе с БД: '.$link->error);
		} else {
			self::addLog('success', 'MySQL Таблица `fw_cache_data` создана');
		}

		if(!$link->query("
			CREATE TABLE IF NOT EXISTS `fw_sessions` (
			  `id` varchar(32) NOT NULL DEFAULT '',
			  `expires` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			  `ip` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
			  `useragent` text COLLATE utf8mb4_unicode_ci NOT NULL,
			  `data` text COLLATE utf8mb4_unicode_ci NOT NULL,
			  PRIMARY KEY (`id`),
			  KEY `ip` (`ip`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
		")) {
			self::addLog('error','Ошибка при работе с БД: '.$link->error);
		} else {
			self::addLog('success', 'MySQL Таблица `fw_sessions` создана');
		}

		if(!$link->query("
			CREATE TABLE IF NOT EXISTS `fw_unsubscribe` (
			  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
			  PRIMARY KEY (`email`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
		")) {
			self::addLog('error','Ошибка при работе с БД: '.$link->error);
		} else {
			self::addLog('success', 'MySQL Таблица `fw_unsubscribe` создана');
		}

		$res = $link->query("SELECT 1 FROM `fw_unsubscribe`");
		if(!$res->num_rows) {
			if(!$link->query("
			INSERT INTO `fw_unsubscribe` (`email`) VALUES
			('test@list.ru'), ('test2@list.ru'), ('test3@list.ru'), ('test4@list.ru'), ('test5@list.ru')
		")
			) {
				self::addLog('error', 'Ошибка при работе с БД: '.$link->error);
			}
			else {
				self::addLog('success', 'Записи в `fw_unsubscribe` добавлены');
			}
		} else {
			self::addLog('warning', 'Записи в `fw_unsubscribe` существуют');
		}

		if(!$link->query("
			CREATE TABLE IF NOT EXISTS `fw_users` (
			  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
			  `login` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
			  `password` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
			  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
			  `date` datetime NOT NULL,
			  `access` tinyint(4) NOT NULL DEFAULT '0',
			  `hash` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
			  `role` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
			  `ip` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
			  `browser` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
			  `lastactive` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			  `skype` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
			  `about` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
		")) {
			self::addLog('error','Ошибка при работе с БД: '.$link->error);
		} else {
			self::addLog('success', 'MySQL Таблица `fw_users` создана');
		}

		$res = $link->query("SELECT 1 FROM `fw_users`");
		if(!$res->num_rows) {
			if(!$link->query("
			INSERT INTO `fw_users` SET
			`login` = '".mysqli_real_escape_string($link, $_SESSION['login'])."',
			`password` = '".password_hash($_SESSION['password'],PASSWORD_DEFAULT)."',
			`date` = NOW(),
			`access` = 1,
			`role` = 'admin',
			`about` = ''
		")
			) {
				self::addLog('error', 'Ошибка при работе с БД: '.$link->error);
			}
			else {
				self::addLog('success', 'Записи в `fw_users` добавлены');
			}
		} else {
			self::addLog('warning', 'Записи в `fw_users` существуют');
		}


		if(!$link->query("
			CREATE TABLE IF NOT EXISTS `fw_users_ip_defender` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
			  `ip` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
			  `count` tinyint(4) NOT NULL DEFAULT '0',
			  PRIMARY KEY (`id`),
			  KEY `date` (`date`),
			  KEY `ip` (`ip`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
		")) {
			self::addLog('error','Ошибка при работе с БД: '.$link->error);
		} else {
			self::addLog('success', 'MySQL Таблица `fw_users_ip_defender` создана');
		}

		if(!$link->query("
			CREATE TABLE IF NOT EXISTS `fw_php_logs` (
			  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			  `link` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
			  `error` text COLLATE utf8mb4_unicode_ci NOT NULL,
			  `trace` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
			  PRIMARY KEY (`id`),
			  KEY `link` (`link`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1
		")) {
			self::addLog('error','Ошибка при работе с БД: '.$link->error);
		} else {
			self::addLog('success', 'MySQL Таблица `fw_php_logs` создана');
		}
		return true;
	}

	static function createSitemap() {
		self::$step = 3;
		if(!isset($_SESSION['sitemap'])) {
			self::addLog('error','Не заполнена карта сайта, необходимо сгенерировать');
		} else {
			self::addLog('success','Карта сайта сгенерирована!');
		}
		Sitemap::$basedir = self::$basedir;
		Sitemap::generateDiv($_SESSION['sitemap']);
		self::addLog('success','SiteMap созданы удачно!');
		return true;
	}

	static function delDir() {
		self::$step = 4;
		self::addLog('success','Установка завершена успешно!');
		@unlink('./install.php');
		self::$step = 5;
		return true;
	}
}
