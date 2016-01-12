<?php
$regist = new \FW\User\Registration;
if(!$regist->activate($_GET['id'],$_GET['hash'])) {
	$error = 'Ваш аккаунт уже активирован!';
}
