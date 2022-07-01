<?php

$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'epa-backend',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'controllerMap' => [

    ],
    'language' => 'ru',
    'bootstrap' => [
        'log',
    ],
    'modules' => [
    ],

    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
        ],
        'view' => [
            'class' => 'backend\components\View',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'epa-backend',
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
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'assetManager' => [
            'appendTimestamp' => true,
//            'linkAssets' => true,
            'bundles' => [
                'class' => 'yii\web\AssetManager',
                'yii\web\JqueryAsset' => [
                    'sourcePath' => '@backend/assets/app',
                    'js' => [
                        'admin/plugins/jquery/jquery-3.2.1.min.js',
                    ]
                ],
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller>/<action>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>/<lang\d+>/<id\d+>/<hash\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ],
        ],
    ],
    'on beforeAction' => function () {
    },
    'as beforeRequest' => [  //if guest user access site so, redirect to login page.
        'class' => 'yii\filters\AccessControl',
        'rules' => [
            [
                'controllers' => ['site'],
                'actions' => ['login', 'logout','email'],
                'roles' => ['?', '@'],
                'allow' => true,
            ],
            [
                'allow' => true,
                'roles' => ['@'],
            ],
        ],
    ],
    'params' => $params,
];
