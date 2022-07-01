<?php

namespace epa\v1\controllers;

use api\components\ApiController;
use common\models\Company;
use common\models\CompanySearch;
use common\models\EventSearch;
use common\models\RequestForms;
use common\models\TradingPlatforms;
use common\models\TradingPlatformsSearch;
use oks\langs\components\Lang;
use Yii;
use yii\data\ActiveDataFilter;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\rest\IndexAction;

class TradingPlatformsController extends ApiController
{
    public $modelClass = TradingPlatforms::class;
    public $searchModelClass = TradingPlatformsSearch::class;

    public function actions()
    {
        $actions = parent::actions();

        unset($actions['index']);
            return ArrayHelper::merge($actions, [
            'index' => [
                'class' => IndexAction::class,
                'modelClass' => TradingPlatforms::class,
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

        $query = TradingPlatforms::find();

        $filter = null;
        $dataFilter = new ActiveDataFilter();
        $dataFilter->searchModel = TradingPlatformsSearch::class;

        if (($name = Yii::$app->request->getQueryParam('filter')['name']) !== null) {
            $query->andWhere(['ILIKE', 'name', $name]);
        }

        if (($category = Yii::$app->request->getQueryParam('filter')['category']) !== null) {
            $query->andWhere(['category' => $category]);
        }

        if (($country = Yii::$app->request->getQueryParam('filter')['country_id']) !== null) {
            $query->andWhere(['country_id' => $country]);
        }
        if (($city = Yii::$app->request->getQueryParam('filter')['city_id']) !== null) {
            $query->andWhere(['city_id' => $city]);
        }

        $query->andWhere(['lang' => Lang::getLangId()]);

        return new ActiveDataProvider([
            'query' => $query
        ]);
    }

    public function actionCategoryList()
    {
        return $this->render('category-list');
    }

    public function actionIndustry()
    {
        return $this->render('industry');
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
        if ($model->load($requestParams, '') && $model->save()){
            return 'success';
        }
        return $model->getErrors();
    }

}
