<?php

namespace epa\v1;

use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\Cors;
use yii\helpers\ArrayHelper;

/**
 * v1 module definition class
 */
class Module extends \yii\base\Module
{
    public static $urlRules = [
        array(
            'class' => 'yii\rest\UrlRule',
            'controller' => 'v1/default',
            'pluralize' => false,
            'extraPatterns' => array(
                'GET index' => 'index',

                'GET,HEAD translations/<lang:\w+>/<category:\w+>' => 'translations',
                'POST translations/<lang:\w+>/<category:\w+>' => 'add-translation',
                'OPTIONS translations/<lang:\w+>/<category:\w+>' => 'options',

            ),
        ),
        [
            'class' => '\yii\rest\UrlRule',
            'controller' => 'v1/profile/user',
            'pluralize' => false,
            'extraPatterns' => [
                'POST signin' => 'login',
                'OPTIONS signin' => 'options',

                'POST logout' => 'logout',
                'OPTIONS logout' => 'options',

                'POST signup' => 'sign-up',
                'OPTIONS signup' => 'options',

                'POST approve' => 'approve',
                'OPTIONS approve' => 'options',
            ],
        ],
        [
            'class' => '\yii\rest\UrlRule',
            'controller' => 'v1/profile/filemanager',
            'pluralize' => false,
            'extraPatterns' => [
                'POST upload' => 'upload',
                'OPTIONS upload' => 'options',
            ],
        ],
        [
            'class' => '\yii\rest\UrlRule',
            'controller' => 'v1/category',
            'pluralize' => false,
            'extraPatterns' => [
                'GET <slug:>' => 'by-slug',
                'OPTIONS <slug:>' => 'options'
            ],
        ],
        [
            'class' => '\yii\rest\UrlRule',
            'controller' => 'v1/company',
            'pluralize' => false,
            'extraPatterns' => [
                'POST request' => 'post-request',
                'OPTIONS request' => 'options'
            ],
        ],
        [
            'class' => '\yii\rest\UrlRule',
            'controller' => 'v1/trading-platforms',
            'pluralize' => false,
            'extraPatterns' => [

            ],
        ],
        [
            'class' => '\yii\rest\UrlRule',
            'controller' => 'v1/document',
            'pluralize' => false,
            'extraPatterns' => [],
        ],
        [
            'class' => '\yii\rest\UrlRule',
            'controller' => 'v1/document-group',
            'pluralize' => false,
            'extraPatterns' => [],
        ],
        [
            'class' => '\yii\rest\UrlRule',
            'controller' => 'v1/event',
            'pluralize' => false,
            'extraPatterns' => [
                'GET,HEAD search' => 'search',
                'OPTIONS search' => 'options',

                'POST request' => 'post-request',
                'OPTIONS request' => 'options'
            ],
        ],
        [
            'class' => '\yii\rest\UrlRule',
            'controller' => 'v1/executive',
            'pluralize' => false,
            'extraPatterns' => [],
        ],
        [
            'class' => '\yii\rest\UrlRule',
            'controller' => 'v1/faq-answer',
            'pluralize' => false,
            'extraPatterns' => [],
        ],
        [
            'class' => '\yii\rest\UrlRule',
            'controller' => 'v1/menu',
            'pluralize' => false,
            'extraPatterns' => [
                'GET,HEAD <slug:\w+>' => 'by-slug',
                'OPTIONS <slug:\w+>' => 'options',
            ],
        ],
        [
            'class' => '\yii\rest\UrlRule',
            'controller' => 'v1/faq-question',
            'pluralize' => false,
            'extraPatterns' => [
                'GET,HEAD <id:\d+>/answers' => 'answers',

                'POST create-question' => 'create-faq',
                'OPTIONS create-question' => 'options',

                'PUT <id:\d+>/useful' => 'useful',
                'OPTIONS <id:\d+>/useful' => 'options',
            ],
        ],
        [
            'class' => '\yii\rest\UrlRule',
            'controller' => 'v1/gallery',
            'pluralize' => false,
            'extraPatterns' => [],
        ],
        [
            'class' => '\yii\rest\UrlRule',
            'controller' => 'v1/gallery-item',
            'pluralize' => false,
            'extraPatterns' => [],
        ],
        [
            'class' => '\yii\rest\UrlRule',
            'controller' => 'v1/post',
            'pluralize' => false,
            'extraPatterns' => [],
        ],
        [
            'class' => '\yii\rest\UrlRule',
            'controller' => 'v1/digest',
            'pluralize' => false,
            'extraPatterns' => [],
        ],
        [
            'class' => '\yii\rest\UrlRule',
            'controller' => 'v1/banner',
            'pluralize' => false,
            'extraPatterns' => [],
        ],
        [
            'class' => '\yii\rest\UrlRule',
            'controller' => 'v1/pages',
            'pluralize' => false,
            'extraPatterns' => [
                'GET list/<category:>' => 'list',
                'OPTIONS list/<category:>' => 'options',
            ],
        ],
        [
            'class' => '\yii\rest\UrlRule',
            'controller' => 'v1/review',
            'pluralize' => false,
            'extraPatterns' => [],
        ],
        [
            'class' => '\yii\rest\UrlRule',
            'controller' => 'v1/quotes',
            'pluralize' => false,
            'extraPatterns' => [],
        ],
        [
            'class' => '\yii\rest\UrlRule',
            'controller' => 'v1/settings',
            'pluralize' => false,
            'extraPatterns' => [
                'GET contact' => 'contact',
                'OPTIONS contact' => 'options',

                'GET value' => 'get-value',
                'OPTIONS value' => 'options',
            ],
        ],
        [
            'class' => '\yii\rest\UrlRule',
            'controller' => 'v1/service',
            'pluralize' => false,
            'extraPatterns' => [
                'GET,HEAD root' => 'get-root-services',
                'OPTIONS root' => 'options',

                'POST request' => 'post-request',
                'OPTIONS request' => 'options'
            ],
        ],
        [
            'class' => '\yii\rest\UrlRule',
            'controller' => 'v1/profile/bank',
            'pluralize' => false,
            'extraPatterns' => [
                'GET fields' => 'fields',
                'OPTIONS fields' => 'options',
            ],
        ],
        [
            'class' => '\yii\rest\UrlRule',
            'controller' => 'v1/profile/certificates',
            'pluralize' => false,
            'extraPatterns' => [
                'GET fields' => 'fields',
                'OPTIONS fields' => 'options',
            ],
        ],
        [
            'class' => '\yii\rest\UrlRule',
            'controller' => 'v1/profile/csdp',
            'pluralize' => false,
            'extraPatterns' => [
                'GET fields' => 'fields',
                'OPTIONS fields' => 'options',
            ],
        ],
        [
            'class' => '\yii\rest\UrlRule',
            'controller' => 'v1/profile/enterprise',
            'pluralize' => false,
            'extraPatterns' => [
                'GET fields' => 'fields',
                'OPTIONS fields' => 'options',
            ],
        ],
        [
            'class' => '\yii\rest\UrlRule',
            'controller' => 'v1/profile/materials',
            'pluralize' => false,
            'extraPatterns' => [
                'GET fields' => 'fields',
                'OPTIONS fields' => 'options',
            ],
        ],
        [
            'class' => '\yii\rest\UrlRule',
            'controller' => 'v1/profile/oked',
            'pluralize' => false,
            'extraPatterns' => [
                'GET fields' => 'fields',
                'OPTIONS fields' => 'options',
            ],
        ],
        [
            'class' => '\yii\rest\UrlRule',
            'controller' => 'v1/profile/products',
            'pluralize' => false,
            'extraPatterns' => [
                'GET fields' => 'fields',
                'OPTIONS fields' => 'options',
            ],
        ],
        [
            'class' => '\yii\rest\UrlRule',
            'controller' => 'v1/profile/soogu',
            'pluralize' => false,
            'extraPatterns' => [
                'GET fields' => 'fields',
                'OPTIONS fields' => 'options',
            ],
        ],
        [
            'class' => '\yii\rest\UrlRule',
            'controller' => 'v1/profile/storage-balance',
            'pluralize' => false,
            'extraPatterns' => [
                'GET fields' => 'fields',
                'OPTIONS fields' => 'options',
            ],
        ],
        [
            'class' => '\yii\rest\UrlRule',
            'controller' => 'v1/profile/subscribe',
            'pluralize' => false,
            'extraPatterns' => [
                'GET fields' => 'fields',
                'OPTIONS fields' => 'options',
            ],
        ],
        [
            'class' => '\yii\rest\UrlRule',
            'controller' => 'v1/profile/tnved',
            'pluralize' => false,
            'extraPatterns' => [
                'GET fields' => 'fields',
                'OPTIONS fields' => 'options',
            ],
        ],
        [
            'class' => '\yii\rest\UrlRule',
            'controller' => 'v1/profile/unit',
            'pluralize' => false,
            'extraPatterns' => [
                'GET fields' => 'fields',
                'OPTIONS fields' => 'options',
            ],
        ],
        [
            'class' => '\yii\rest\UrlRule',
            'controller' => 'v1/profile/location',
            'pluralize' => false,
            'extraPatterns' => [
                'GET fields' => 'fields',
                'OPTIONS fields' => 'options',

                'GET root' => 'root',
                'OPTIONS root' => 'options',
                'GET root/<id:>' => 'root',
                'OPTIONS root/<id:>' => 'options',
            ],
        ],
        [
            'class' => '\yii\rest\UrlRule',
            'controller' => 'v1/profile/user-info',
            'pluralize' => false,
            'extraPatterns' => [
                'GET fields' => 'fields',
                'OPTIONS fields' => 'options',
            ],
        ],
        [
            'class' => '\yii\rest\UrlRule',
            'controller' => 'v1/profile/user-faq',
            'pluralize' => false,
            'extraPatterns' => [
                'GET fields' => 'fields',
                'OPTIONS fields' => 'options',

                'GET draft' => 'get-drafts',
                'POST draft' => 'draft',
                'OPTIONS draft' => 'options',
            ],
        ],
    ];
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'epa\v1\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        error_reporting(2215);
        parent::init();

        // custom initialization code goes here
    }

    public function behaviors()
    {
        $only = array(
            'profile/bank/*',
            'profile/products/*',
            'profile/certificate/*',
            'profile/user/get-me',
            'profile/user/index',
            'profile/user/update',
            'profile/user/approve',
            'profile/user-info/*',
            'profile/filemanager/*',
            'profile/location/*',
            'profile/user-faq/*',
            'profile/user/logout',
            'profile/enterprise/*',
        );

        return ArrayHelper::merge(parent::behaviors(), [
            'corsFilter' => [
                'class' => Cors::class,
                'cors' => [
                    'Origin' => static::allowedDomains(),
                    'Access-Control-Request-Method' => ['*'],
                    'Access-Control-Max-Age' => 3600,
                    'Access-Control-Request-Headers' => ['*'],
                    'Access-Control-Expose-Headers' => ['*']
                ],
            ],
            'authenticator' => [
                'class' => CompositeAuth::class,
                'only' => $only,
                'except' => [
                    '*/options',

                ],
                'authMethods' => [
                    HttpBearerAuth::class,
                ],
            ],
        ]);
    }

    public static function allowedDomains()
    {
        return [
            '*',
        ];
    }
}
