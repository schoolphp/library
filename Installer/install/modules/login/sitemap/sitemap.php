<?php
return [
	'main' => [
		'link' => 1,
	],
	'registration' => 1,
	'restoration' => 1,
	'unsubscribe' => 1,
	'exit' => 1,
	'activate' => [
		'id' => [
			'req' => 1,
			'rules' => '[0-9]+',
			'type' => 'int',
		],
		'hash' => [
			'req' => 1,
		]
	],
];
