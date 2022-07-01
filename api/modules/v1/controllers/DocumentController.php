<?php

namespace epa\v1\controllers;

use api\components\ApiController;
use common\models\Document;
use common\models\DocumentSearch;
use oks\langs\components\Lang;
use Yii;
use yii\data\ActiveDataFilter;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\rest\IndexAction;

class DocumentController extends ApiController
{
    public $modelClass = Document::class;
    public $searchModelClass = DocumentSearch::class;

    public function actions()
    {
        $actions = parent::actions();

        unset($actions['index']);
        return ArrayHelper::merge($actions, [
            'index' => [
                'class' => IndexAction::class,
                'modelClass' => Document::class,
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
        $requestParams = Yii::$app->getRequest()->getBodyParams();
        if (empty($requestParams)) {
            $requestParams = Yii::$app->getRequest()->getQueryParams();
        }

        $query = Document::find();

        $filter = null;
        $dataFilter = new ActiveDataFilter();
        $dataFilter->searchModel = DocumentSearch::class;

        if (!empty($category = Yii::$app->request->getQueryParam('filter')['category'])) {
            $query->andWhere(['category' => $category]);
        }

        $query->andWhere(['lang' => Lang::getLangId()]);

        return new ActiveDataProvider([
            'query' => $query
        ]);
    }
}
