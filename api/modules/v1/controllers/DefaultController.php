<?php

namespace epa\v1\controllers;

use api\components\ApiController;
use common\models\City;
use common\models\Country;
use common\models\Region;
use common\modules\translations\models\Message;
use common\modules\translations\models\SourceMessage;
use oks\langs\components\Lang;
use yii\web\ServerErrorHttpException;

/**
 * Default controller for the `v1` module
 */
class DefaultController extends ApiController
{
    public function actions()
    {
        $actions = parent::actions();
        unset($actions['index']);
        unset($actions['view']);
        return $actions;
    }


    public function actionIndex()
    {
        return "welcome to EPA API v1";
    }

    public function actionSignup()
    {

    }

    public function actionLogin()
    {

    }

    public function actionLogout()
    {

    }

    public function actionCountry()
    {
        return Country::find()->andWhere(['lang' => Lang::getLangId()])->all();
    }

    public function actionRegion()
    {
        return Region::findAll(['country_id' => 1]);
    }

    public function actionCity(int $id)
    {
        return City::findAll(['country_id' => $id]);
    }

    /**
     * @param null $lang
     * @param string $category
     * @return array
     */
    public function actionTranslations($lang = null, $category = "react")
    {
        $duration = 1800;
        if ($lang == null) {
            $lang = \Yii::$app->language;
        }
        $translates = \Yii::$app->cache->get('getAllTranslates');

        if ($translates === false) {
            $translates = SourceMessage::find()->where(['category' => $category])->asArray()->all();
            \Yii::$app->cache->set('getAllTranslates', $translates, $duration);
        }

        $json = [];
        foreach ($translates as $translate) {
            $cacheKey = "messageByKeyId{$translate['id']}Lang{$lang}";
            $message = \Yii::$app->cache->get($cacheKey);

            if ($message === false) {
                $message = @Message::find()
                    ->where(['id' => $translate['id']])
                    ->andWhere(['ILIKE', 'language', $lang])
                    ->asArray()
                    ->one();

                \Yii::$app->cache->set($cacheKey, $message, $duration);
            }

            $translate['systemMessageTranslation'] = $message;
            if (strlen(trim($translate['systemMessageTranslation']['translation'])) == 0) {
                $json[$translate['message']] = @$translate['message'];
                continue;
            }
            $json[$translate['message']] = @$translate['systemMessageTranslation']['translation'];

        }

        return $json;
    }

    /**
     * @param null $lang
     * @param string $category
     * @return array|bool|\yii\console\Response|\yii\web\Response
     * @throws ServerErrorHttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionAddTranslation($lang = null, $category = 'react')
    {
        if ($lang == null) {
            $lang = \Yii::$app->language;
        }
        $requestParams = \Yii::$app->getRequest()->getBodyParams();
        if (empty($requestParams)) {
            $requestParams = \Yii::$app->getRequest()->getQueryParams();
        }
        if (count($requestParams) == 0) {
            throw new ServerErrorHttpException("Invalid data");
        }

        $sourceMessage = current($requestParams);
        $translateMessage = key($requestParams);

        $sm = SourceMessage::create($sourceMessage, $category);
        if (is_array($sm)) {
            \Yii::$app->getResponse()->setStatusCode(409);
            return $sm;
        } elseif ($sm === true) {
            return \Yii::$app->getResponse()->setStatusCode(201);
        } else {
            return \Yii::$app->getResponse()->setStatusCode(500);
        }

    }
}