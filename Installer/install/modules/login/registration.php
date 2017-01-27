<?php
$form = new \FW\Form\Form('registration');
$form->create([
	'login' => [
		'title'=> 'Введите логин*',
		'text' => '',
		'attr' => [
			'placeholder' => 'Логин',
			'class' => 'form-control',
		],
		'rules' => [
			'length' => '5,20',
			'unique' => ['table'=>'fw_users','cell'=>'login'],
		]
	],
	'password' => [
		'title'=> 'Введите пароль*',
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
	'email' => [
		'title'=> 'Введите e-mail*',
		'text' => '',
		'attr' => [
			'placeholder' => 'e-mail',
			'class' => 'form-control',
		],
		'rules' => [
			'email',
			'unique' => ['table'=>'fw_users','cell'=>'email']
		]
	],
	'submit' => [
		'title'=> '',
		'text' => '',
		'value'=> 'Регистрация',
		'type' => 'submit',
		'attr' => ['class' => 'btn btn-primary']
	]
]);
if($form->issend()) {
	$reg = new \FW\User\Registration;
	if($reg->regist($_POST['login'],$_POST['password'],$_POST['email'])) {
		$status = 'ok';
	} else {
		$status = 'Ошибка регистрации';
	}
} else {
	$error = $form->error;
}
