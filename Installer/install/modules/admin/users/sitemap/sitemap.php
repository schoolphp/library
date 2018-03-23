<?php
return [
	'change' => [
		'action' => [
			'req' => 1,
			'type' => 'string'
		],
		'id' => [
			'type' => 'int'
		],
		'value' => [
			'type' => 'string'
		],
	],
	'view' => [
		'page' => [
			'default' => '1',
			'type' => 'int'
		],
		'search' => [
			'type' => 'string'
		]
	],

	'groups-change' => [
		'action' => [
			'req' => 1,
			'type' => 'string'
		],
		'id' => [
			'type' => 'int'
		],
		'value' => [
			'type' => 'string'
		],
	],
	'groups-view' => [],
    'accesses' => [
        'id' => [
            'type' => 'int'
        ],
    ],
    'authorization' => [
        'id' => [
            'req' => 1,
            'type' => 'int'
        ],
    ],
];