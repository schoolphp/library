<?php
$status = '';
$status = '';
//$_SESSION['antixsrf-form-registration'] = 'xxx';
//$_POST['antixsrf'] = 'xxx';
$form = new \FW\Form\Form('registration');
$form->create([
	'login' => array(
		'title'=>'',
		'text' => '',
		'attr' => array(
			'placeholder' => 'Логин',
			'class' => 'form-control',
		),
		'rules' => array(
			'length' => '5,20',
			'unique' => array('table'=>'fw_users','cell'=>'login'),
		)
	),
	'password' => array(
		'title'=>'',
		'text' => '',
		'type' => 'password',
		'attr' => array(
			'placeholder' => 'Пароль',
			'class' => 'form-control',
		),
		'rules' => array(
			'length' => '6,20',
		)
	),
]);

if($form->issend()) {
	$auth = new \FW\User\Authorization;
	$remember = (isset($_POST['checkbox']['remember'])? true : false);
	if($auth->authByLoginPass($_POST['login'],$_POST['password'],true)) {
		$status = 'ok';
	} else {
		$status = $auth->getErrorMess();
		$_SESSION['wrong-form']['time'] = time();
		$_SESSION['wrong-form']['key'] = (isset($_SESSION['wrong-form']['key']) ? ($_SESSION['wrong-form']['key']+1) : 1);
	}
}
echo '<hr>'.$status.'<hr>';
//exit;
