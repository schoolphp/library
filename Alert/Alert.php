<?php
namespace FW\Alert\Alert;

class Alert
{
	static function set($mess, $type = 'warning', $global = false) {
		$_SESSION['alert'][($global ? 'global' : $_GET['_module'])] = ['type' => $type , 'mess' => $mess];
	}

	static function get($global = false) {
		if(!$alert = (isset($_SESSION['alert'][($global ? 'global' : $_GET['_module'])]) ? $_SESSION['alert'][($global ? 'global' : $_GET['_module'])] : false)) {
			return '';
		}

		unset($_SESSION['alert'][($global ? 'global' : $_GET['_module'])]);
		return '<div class="alert-'.$alert['type'].'">'.$alert['mess'].'</div>';
	}
}