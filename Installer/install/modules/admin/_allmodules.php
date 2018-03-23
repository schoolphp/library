<?php
if(($_GET['_module'] != 'main' || $_GET['_page'] != 'main') && !\Access\Access::isAllowAdmin()) {
	$_GET['_module'] = 'main';
	$_GET['_page'] = 'main';
}