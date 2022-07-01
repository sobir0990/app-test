<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-api',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'api\controllers',
	'modules' => [
		'v1' => [
			'class' => \epa\v1\Module::class,
		]
	],
    'components' => [
		'request' => [
			'cookieValidationKey' => '8wjcm7qBeQBPQCvq_TI_UkWukV4KuZ7U',
			'parsers' => [
				'application/json' => 'yii\web\JsonParser',
			]
		],
		'response' => [
			'formatters' => [
				'json' => [
					'class' => \yii\web\JsonResponseFormatter::class,
					'prettyPrint' => YII_DEBUG,
					'encodeOptions' => JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE,
				]
			],
			'format' => \yii\web\Response::FORMAT_JSON,
		],
		'user' => [
			'identityClass' => 'common\models\User',
			'enableAutoLogin' => true,
			'enableSession' => false,
            'identityCookie' => ['name' => '_identity-api', 'httpOnly' => true],
		],
		'log' => [
			'traceLevel' => YII_DEBUG ? 3 : 0,
			'targets' => [
				[
					'class' => 'yii\log\FileTarget',
					'levels' => ['error', 'warning'],
				],
			],

		],
		'urlManager' => [
			'class' => \yii\web\UrlManager::class,
			'showScriptName'	=> false,
			'enablePrettyUrl'	=> true,
			'rules'	=> \epa\v1\Module::$urlRules,
		],
    ],
    'params' => $params,
];
