<?php


use api\components\ApiController;
use common\modules\translations\models\Message;
use common\modules\translations\models\SourceMessage;
use yii\web\ServerErrorHttpException;

/**
 * Class MainController
 * @package api\modules\v1\controllers
 */
class TranslationsController extends ApiController
{
    public $modelClass = SourceMessage::class;

    /**
     * @param null $lang
     * @param string $category
     * @return array
     */
    public function actionTranslations($lang = null, $category = "react")
    {
        if ($lang == null) {
            $lang = \Yii::$app->language;
        }
        $translates = \Yii::$app->cache->get('getAllTranslates');

        $translates = false;

        if ($translates === false) {
            $translates = SourceMessage::find()->where(['category' => $category])->asArray()->all();
            \Yii::$app->cache->set('getAllTranslates', $translates, 7200);
        }

        $json = [];
        foreach ($translates as $translate) {
            $cacheKey = "messageByKeyId{$translate['id']}Lang{$lang}";
            $message = \Yii::$app->cache->get($cacheKey);
            $message = false;
            if ($message === false) {
                $message = @Message::find()
                    ->where(['id' => $translate['id']])
                    ->andWhere(['ILIKE', 'language', $lang])
                    ->asArray()
                    ->one();

                \Yii::$app->cache->set($cacheKey, $message, 7200);
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