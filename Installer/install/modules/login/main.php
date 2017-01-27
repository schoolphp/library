<?php
$form = new \FW\Form\Form('authorization');
$form->create([
	'login' => [
		'title'=> '',
		'text' => '',
		'attr' => [
			'placeholder' => 'Логин',
			'class' => 'form-control',
		],
		'rules' => [
			'length' => '5,20',
		]
	],
	'password' => [
		'title'=>'',
		'text' => '',
		'type' => 'password',
		'attr' => [
			'placeholder' => 'Пароль',
			'class' => 'form-control',
		],
		'rules' => [
			'length' => '6,20',
		]
	],
	'submit' => [
		'title'=> '',
		'text' => '',
		'value'=> 'Авторизироваться',
		'type' => 'submit',
		'attr' => ['class' => 'btn btn-primary']
	]
]);
if($form->issend()) {
	$auth = new \FW\User\Authorization;
	$remember = (isset($_POST['checkbox']['remember'])? true : false);
	if($auth->authByLoginPass($_POST['login'],$_POST['password'],true)) {
		$status = 'ok';
		header("Location: /".$_GET['_module']);
		exit;
	} else {
		$status = '';

		foreach($auth->getErrorMess() as $k=>$v) {
			if(isset($form->content[$k])) {
				$form->content[$k]['error'] = $v;
			} else {
				$status = $v;
			}
		}
		$form->error = $status;
		$_SESSION['wrong-form']['time'] = time();
		$_SESSION['wrong-form']['key'] = (isset($_SESSION['wrong-form']['key']) ? ($_SESSION['wrong-form']['key']+1) : 1);
	}
}
