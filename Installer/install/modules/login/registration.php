<?php
$status = '';
$_SESSION['antixsrf-form-registration'] = 'xxx';
$_POST['antixsrf'] = 'xxx';
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
	'email' => array(
		'title'=>'',
		'text' => '',
		'attr' => array(
			'placeholder' => 'e-mail',
			'class' => 'form-control',
		),
		'rules' => array(
			'email',
			'unique' => array('table'=>'fw_users','cell'=>'email')
		)
	),
]);
if($form->issend()) {
	$reg = new \FW\User\Registration;
	if($reg->regist($_POST['login'],$_POST['password'],$_POST['email'])) {
		$status = 'ok';
	} else {
		$status = 'Ошибка регистрации';
	}
} else {
	$status = $form->error;
}
echo $status;
exit;
