<?php
$regist = new Registration;
if(!$regist->activate($_GET['id'],$_GET['hash'])) {
	$error = 'Ваш аккаунт уже активирован!';
}
