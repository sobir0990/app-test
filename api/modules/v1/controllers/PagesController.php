<?php

namespace epa\v1\controllers;

use api\components\ApiController;
use common\models\Category;
use common\models\Lang;
use common\models\Pages;
use common\models\PagesSearch;
use common\modules\menu\models\Menu;
use common\modules\menu\models\MenuSearch;
use yii\data\ActiveDataFilter;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\rest\IndexAction;
use yii\web\NotFoundHttpException;

class PagesController extends ApiController
{
    public $modelClass = Pages::class;
    public $searchModelClass = PagesSearch::class;

    public function actions()
    {
        $actions = parent::actions();

        unset($actions['index']);
        unset($actions['view']);
        return ArrayHelper::merge($actions, [
            'index' => [
                'class' => IndexAction::class,
                'modelClass' => Pages::class,
                'prepareDataProvider' => [$this, 'preparePagesDataProvider']
            ],
        ]);
    }

    /**
     * @return ActiveDataProvider
     * @throws \yii\base\InvalidConfigException
     */
    public function preparePagesDataProvider()
    {
        $requestParams = \Yii::$app->getRequest()->getBodyParams();
        if (empty($requestParams)) {
            $requestParams = \Yii::$app->getRequest()->getQueryParams();
        }

        $query = Pages::find();

        $filter = null;
        $dataFilter = new ActiveDataFilter();
        $dataFilter->searchModel = PagesSearch::class;
        if (($category = \Yii::$app->request->getQueryParam('filter')['category']) !== null) {
            $query->andWhere(['category' => $category]);
        }

        return new ActiveDataProvider([
            'query' => $query
        ]);
    }

    /**
     * @return array|null|\yii\db\ActiveRecord
     * @throws NotFoundHttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionView()
    {
        $slug = \Yii::$app->getRequest()->getQueryParams()['slug'];
        if (empty($slug)) {
            $slug = \Yii::$app->getRequest()->getBodyParams('slug');
        }

        return $this->findModelBySlug($slug);
    }

    /**
     * @param $category
     * @return ActiveDataProvider
     * @throws \yii\base\InvalidConfigException
     */
    public function actionList($category)
    {
        $categoryId = Category::findOne(['slug' => $category])->id;

        $requestParams = \Yii::$app->getRequest()->getBodyParams();
        if (empty($requestParams)) {
            $requestParams = \Yii::$app->getRequest()->getQueryParams();
        }
        $query = Pages::find();

        $filter = null;
        $dataFilter = new ActiveDataFilter();
        $dataFilter->searchModel = PagesSearch::class;
        $query->andWhere(['category' => $categoryId]);
        $query->andWhere(['lang' => \oks\langs\components\Lang::getLangId()]);

        return new ActiveDataProvider([
            'query' => $query
        ]);
    }

    /**
     * @param $slug
     * @return array|null|\yii\db\ActiveRecord
     * @throws NotFoundHttpException
     */
    public function findModelBySlug($slug)
    {
        $model = Pages::find()->andWhere(['slug' => $slug, 'status' => 1])->andWhere(['lang' => \oks\langs\components\Lang::getLangId()])->one();
        if ($model instanceof Pages) {
            return $model;
        }
        throw new NotFoundHttpException('Page is not founded');
    }
}
