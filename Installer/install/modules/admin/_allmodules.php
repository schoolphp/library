<?php
if(($_GET['_module'] != 'main' || $_GET['_page'] != 'main') && !isAdmin()) {
	$_GET['_module'] = 'main';
	$_GET['_page'] = 'main';
}