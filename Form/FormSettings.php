<?php
namespace FW\Form;

class FormSettings {
	public $settings = array(
		'mode' => 1, // Варианты: developer (2), user (1), product (0)
		'path' => './skins/libs/form/', // Путь к шаблону
		'name' => '',
		'full_path' => '',
		'reload' => true,
		'file_rights' => 755,
		'method' => 'POST',
		'action' => '',
		'origin_type' => 'table',
		'generate_form' => true,
		'error' => ''
		
	);
	
	public $language = array(
		'ErrorForm' => 'Невозможно загрузить форму',
		'ErrorCreateDir' => 'Ошибка установки. Невозможно создать папку в указанной директории',
		'ErrorCreateFile' => 'Ошибка установки. Невозможно создать файл в указанной директории',
		'ErrorNeedInstall' => 'Невозможно загрузить форму. Её необходимо установить',
		'NoTemplateInput' => 'Отсутствует Template',
		'NoItemType' => 'Отсутствует Item Type',
		'NoItemFile' => 'Отсутствует Item File',
		'NoTplFile' => 'Отсутствует TPL File',
		'CheckInt' => 'Вы ввели не число',
		'CheckLength' => 'Длина символов',
		'CheckEmpty' => 'Заполните поле',
		'CheckEmail' => 'Вы неверно ввели email',
		'CheckCaptcha' => 'Неправильно ввели код с картинки',
		'CheckUnique' => 'Такое значение уже есть в Базе. Введите другое',
	);
	
	public $template = array(
		'password' => array(
			'title' => 'Пароль',
			'type' => 'password',
			'value' => '',
			'text' => '',
			'rules' => array(
				'length' => '7,12',
			),
		),
		'captcha' => array(
			'title' => 'Пароль с картинки',
			'type' => 'captcha',
			'value' => '',
			'text' => '',
			'rules' => array(
				'captcha',
			),
		),
		'email' => array(
			'title' => 'E-mail',
			'type' => 'email',
			'rules' => array(
				'email'
			),
		),
		'submit' => array(
			'type' => 'submit',
			'value' => 'Отправить',
		),
	);
}
