<?php

namespace epa\v1\controllers;

use api\components\ApiController;
use common\models\Company;
use common\models\CompanySearch;
use common\models\EventSearch;
use common\models\RequestForms;
use common\modules\profile\models\UserRequest;
use oks\langs\components\Lang;
use Yii;
use yii\data\ActiveDataFilter;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\rest\IndexAction;

class CompanyController extends ApiController
{
    public $modelClass = Company::class;
    public $searchModelClass = CompanySearch::class;

    public function actions()
    {
        $actions = parent::actions();

        unset($actions['index']);
            return ArrayHelper::merge($actions, [
            'index' => [
                'class' => IndexAction::class,
                'modelClass' => Company::class,
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

        $query = Company::find();

        $filter = null;
        $dataFilter = new ActiveDataFilter();
        $dataFilter->searchModel = CompanySearch::class;

        if (($type = Yii::$app->request->getQueryParam('filter')['type']) !== null) {
            $query->andWhere(['type' => $type]);
        }

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
