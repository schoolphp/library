<?php
namespace Mail\Config;

trait Config {
	static $subject = 'Новое письмо';
	static $to = 'mymail@gmail.com';
	static $from = 'noreply@mydomain.com';
	static $from_name = 'School PHP';
	static $html = '';
	static $boundary = '';
	static $files = array();
	static $headers = array();
	static $error = '';
	static $unsubscribe = '';
}
