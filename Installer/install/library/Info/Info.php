<?php
class Info {
	static function set($type, $text, $key = 'Info') {
		if($type == 'error') $type = 'danger';
		if(!isset($_SESSION[$key])) {
			$_SESSION[$key] = '';
		}
		$_SESSION[$key] .= '<div class="alert alert-'.$type.'" role="alert">'.$text.'</div>';
	}
	static function get($key = 'Info') {
		if(isset($_SESSION[$key])) {
			$tmp = $_SESSION[$key];
			unset($_SESSION[$key]);
			return $tmp;
		} else {
			return '';
		}
	}
}
