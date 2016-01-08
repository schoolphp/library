<?php
if(($_GET['_module'] != 'main' || $_GET['_page'] != 'main') && (empty(User::$data['role']) || User::$data['role'] != 'admin')) {
	$_GET['_module'] = 'main';
	$_GET['_page'] = 'main';
}