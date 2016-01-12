<?php
$status = '';
if(isset($_POST['login'],$_POST['pass'])) {
	$auth = new \FW\User\Authorization;
	$remember = (isset($_POST['checkbox']['remember'])? true : false);
	if($auth->authByLoginPass($_POST['login'],$_POST['pass'],true)) {
		$status = 'ok';
	} else {
		$status = $auth->getErrorMess();
		$_SESSION['wrong-form']['time'] = time();
		$_SESSION['wrong-form']['key'] = (isset($_SESSION['wrong-form']['key']) ? ($_SESSION['wrong-form']['key']+1) : 1);
	}
} elseif(isset($_SESSION['user']['id'],$_POST['action'],$_POST['age'],$_POST['email'],$_POST['name'],$_POST['color']) && $_POST['action'] == 'change') {
	q("
		UPDATE `fw_users` SET
		`email` = '".es($_POST['email'])."',
		`age` = '".(int)$_POST['age']."',
		`name` = '".es($_POST['name'])."',
		`color` = '".es($_POST['color'])."'
		WHERE `id` = ".(int)$_SESSION['user']['id']."
	");
	echo 'ok';
} else {
	$status = 'Заполните форму';
}
echo $status;
exit;
