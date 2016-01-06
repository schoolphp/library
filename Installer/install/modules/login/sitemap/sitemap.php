<?php
return array(
	'login' => array(
		'main' => array(
			'link' => 1,
		),
		'registration' => 1,
		'restoration' => 1,
		'unsubscribe' => 1,
		'exit' => 1,
		'activate' => array(
			'id' => array(
				'req' => 1,
				'rules' => '[0-9]+',
				'type' => 'int',
			),
			'hash' => array(
				'req' => 1,
			)
		),
	),
);
