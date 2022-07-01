<?php

namespace epa\v1\controllers;

use api\components\ApiController;
use common\models\ServiceSearch;
use common\modules\settings\models\Settings;
use yii\helpers\ArrayHelper;
use yii\rest\IndexAction;

class SettingsController extends ApiController
{
    public $modelClass = Settings::class;
    public $searchModelClass = ServiceSearch::class;

    public function actionContact()
    {
        $phone = Settings::value('phone-number');
        $phone1 = Settings::value('phone-number2');
        $email = Settings::value('email');
        $address = Settings::value('adress');
        $map = Settings::value('map');

        return [
            'phone' => $phone,
            'phone1' => $phone1,
            'email' => $email,
            'address' => $address,
            'map' => $map
        ];
    }

    public function actionGetValue()
    {
        if ($slug = \Yii::$app->request->getQueryParam('slug')) {
            return Settings::value($slug);
        }
    }
}
