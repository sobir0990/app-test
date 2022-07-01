<?php

namespace epa\v1\controllers;

use api\components\ApiController;
use common\models\Event;
use common\models\EventSearch;
use common\models\RequestForms;
use common\modules\profile\models\UserRequest;
use oks\langs\components\Lang;
use Yii;
use yii\data\ActiveDataFilter;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\rest\IndexAction;

class EventController extends ApiController
{
    public $modelClass = Event::class;
    public $searchModelClass = EventSearch::class;

    public function actions()
    {
        $actions = parent::actions();

        unset($actions['index']);
        return ArrayHelper::merge($actions, [
            'index' => [
                'class' => IndexAction::class,
                'modelClass' => Event::class,
                'prepareDataProvider' => [$this, 'prepareEventDataProvider']
            ],
        ]);
    }

    /**
     * @return ActiveDataProvider
     * @throws \yii\base\InvalidConfigException
     */
    public function prepareEventDataProvider()
    {
        $requestParams = \Yii::$app->getRequest()->getQueryParams();
        if (empty($requestParams)) {
            $requestParams = \Yii::$app->getRequest()->getBodyParams();
        }

        $query = Event::find();

        if (!empty($title = Yii::$app->request->getQueryParam('title'))) {
            $query->andWhere(['ILIKE', 'title', $title]);
        }

        if (!empty($category = Yii::$app->request->getQueryParam('filter')['category'])) {
            $query->andWhere(['category' => $category]);
        }

        if (!empty($type = Yii::$app->request->getQueryParam('filter')['type'])) {
            $query->andWhere(['type' => $type]);
        }

        if (!empty($date = Yii::$app->request->getQueryParam('filter')['date'])) {
            $query->andWhere(['<', 'start_date', $date])->andWhere(['>', 'end_date', $date]);
        }
        if (($country = Yii::$app->request->getQueryParam('filter')['country_id']) !== null) {
            $query->andWhere(['country_id' => $country]);
        }
        if (($city = Yii::$app->request->getQueryParam('filter')['city_id']) !== null) {
            $query->andWhere(['city_id' => $city]);
        }
        if (($slider = $requestParams['filter']['slider']) !== null) {
            $query->andWhere(['slider' => $slider]);
        }
        $query->andWhere(['lang' => Lang::getLangId()]);

        return new ActiveDataProvider([
            'query' => $query
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
        if ($model->load($requestParams, '')) {
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
