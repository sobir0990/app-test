<?php

namespace epa\v1\controllers;

use api\components\ApiController;
use common\models\Partner;
use common\models\PartnerSearch;
use common\models\PostSearch;
use Yii;
use yii\data\ActiveDataFilter;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\rest\IndexAction;

class PartnerController extends ApiController
{
    public $modelClass = Partner::class;
    public $searchModelClass = PostSearch::class;

    public function actions()
    {
        $actions = parent::actions();

        unset($actions['index']);
        return ArrayHelper::merge($actions, [
            'index' => [
                'class' => IndexAction::class,
                'modelClass' => Partner::class,
                'prepareDataProvider' => [$this, 'prepareCompanyDataProvider']
            ],
        ]);
    }

    /**
     * @return ActiveDataProvider
     * @throws \yii\base\InvalidConfigException
     */
    public function prepareCompanyDataProvider()
    {
        $requestParams = \Yii::$app->getRequest()->getBodyParams();
        if (empty($requestParams)) {
            $requestParams = \Yii::$app->getRequest()->getQueryParams();
        }

        $query = Partner::find();

        $filter = null;
        $dataFilter = new ActiveDataFilter();
        $dataFilter->searchModel = PartnerSearch::class;

        if (($type = Yii::$app->request->getQueryParam('filter')['type']) !== null) {
            $query->andWhere(['type' => $type]);
        }

        $query->andWhere(['lang' => \oks\langs\components\Lang::getLangId()]);

        return new ActiveDataProvider([
            'query' => $query
        ]);
    }
}
