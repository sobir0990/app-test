<?php

namespace epa\v1\controllers;

use api\components\ApiController;
use common\models\RequestForms;
use common\models\Service;
use common\models\ServiceSearch;
use common\modules\profile\models\UserRequest;
use oks\langs\components\Lang;
use Yii;
use yii\data\ActiveDataProvider;

class ServiceController extends ApiController
{
    public $modelClass = Service::class;
    public $searchModelClass = ServiceSearch::class;

    public function actionGetRootServices()
    {
        $services = Service::find()->andWhere(['root_service' => null, 'lang' => Lang::getLangId()]);

        return new ActiveDataProvider([
            'query' => $services
        ]);
    }

    /**
     * @return array|string
     * @throws \yii\base\InvalidConfigException
     */
    public function actionPostRequest()
    {
        $requestParams = \Yii::$app->request->getBodyParams();
        if (empty($requestParams)) {
            $requestParams = \Yii::$app->request->getQueryParams();
        }

        $model = new RequestForms();
        if ($model->load($requestParams, '')){
            $model->status = RequestForms::STATUS_UNCONFIRMED;
            $model->save();
            if ($requestParams['user_id'] !== null) {
                $user_request = new UserRequest();
                $user_request->user_id = $requestParams['user_id'];
                $user_request->request_id = $model->id;
                $user_request->save();
            }
            return 'success';
        }
        return $model->getErrors();
    }
}
