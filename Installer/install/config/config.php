<?php

class Core {
	static $CREATED  = '$_POST["created"]';
	static $STATUS = 1; // 0 = PRODUCT. 1 = IN WORK
	
	static $CONT     = 'modules';
	static $SKIN     = ''; // '/folder' to use folder in view
	static $MAINTPL  = 'index.tpl';
	static $ROOT     = '';
	static $HTTPS    = 0; // 0 - HTTP, 1 - HTTPS

	static $DB_NAME  = '$_POST["db-name"]';
	static $DB_LOGIN = '$_POST["db-login"]';
	static $DB_PASS  = '$_POST["db-pass"]';
	static $DB_LOCAL = '$_POST["db-local"]';
	static $DB_TIME_ZONE = ''; // '+03:00' . Установка временной зоны для MySQL, если у хостера другой часовой пояс.
	static $PHP_TIME_ZONE = 'Europe/Kiev'; // 'Europe/Moscow' . Установка временной зоны для PHP

	static $DOMAIN   = '$_POST["domain"]';
	static $ADMIN    = '$_POST["email"]';
	static $NOREPLY  = '$_POST["email"]';
	static $SITENAME = '$_POST["site-name"]';

	static $EVENTS  = true;
	static $SHORTLINK = true;
	static $AUTOCANONICAL = false;

	static $META = [
		'title'=>'стандартный TITLE',
		'description'=>'d',
		'keywords'=>'k',
		
		'canonical' => '',
		'shortlink' => '',
		'prev' => '',
		'next' => '',
		'dns-prefetch' => [],
		'head' => '',
	];

	static $LANGUAGE = [
		'status' => false,
		'lang' => 'ru',
		'html_locale' => 'ru-RU',
		'default' => 'ru',
		'allow' => ['ru','en'],
	];

	static $STUBROUTINE = [
		'status' => false,
		'ip_access' => [
			'ip' => ['127.0.0.1'], // Example '127.0.0.1'
			'ip_mask' => [], // Example '127.0.0.1/32'
		],
	];

	static $ERRORS = [
		'file' => 1, // Logged in file: logs/php.log
		'mysql' => 0, // Logged in mysql: `php_logs`
		'email' => 0, // Mess to email $ADMIN
		'show' => 1, // Write error on page
		'redirect' => 1, // Redirect on 404 
		'stop' => 0, // exit(); for everything notice or warning
		'errlvl' => 2, // Errors-logged: 2 - All errors, 1 - Not user
		'trace' => 0, // Save full TRACE
	];

	static $SITEMAP = [];

	/* Системное. НЕ трогать, если не шарите! */
	static $DIRECTORY;
	static $JS = [];
	static $CSS = [];
	
}

Core::$DIRECTORY = getcwd();
Core::$META['dns-prefetch'][] = Core::$DOMAIN;
mb_internal_encoding("UTF-8");
mb_regex_encoding("UTF-8");
date_default_timezone_set(Core::$PHP_TIME_ZONE);
setlocale(LC_ALL, 'ru_RU.UTF-8');
header('Content-Type: text/html; charset=utf-8');
