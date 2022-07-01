<?php

namespace epa\v1\controllers;

use api\components\ApiController;
use common\models\Lang;
use common\models\Post;
use common\models\PostSearch;
use Yii;
use yii\data\ActiveDataFilter;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\rest\IndexAction;
use yii\web\NotFoundHttpException;

class PostController extends ApiController
{
    public $modelClass = Post::class;
    public $searchModelClass = PostSearch::class;

    public function actions()
    {
        $actions = parent::actions();

        unset($actions['index']);
        unset($actions['view']);
        return ArrayHelper::merge($actions, [
            'index' => [
                'class' => IndexAction::class,
                'modelClass' => Post::class,
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

        $query = Post::find()->andWhere(['status' => Post::STATUS_ACTIVE]);

        $filter = null;
        $dataFilter = new ActiveDataFilter();
        $dataFilter->searchModel = PostSearch::class;

        if (($category = Yii::$app->request->getQueryParam('filter')['category']) !== null) {
            $query->andWhere(['category' => $category]);
        }

        if (($type = Yii::$app->request->getQueryParam('filter')['type']) !== null) {
            $query->andWhere(['type' => $type]);
        }

        $query->andWhere(['lang' => \oks\langs\components\Lang::getLangId()]);

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
     * @param $slug
     * @return array|null|\yii\db\ActiveRecord
     * @throws NotFoundHttpException
     */
    public function findModelBySlug($slug)
    {
        $model = Post::find()->andWhere(['slug' => $slug, 'status' => 1])->andWhere(['lang' => \oks\langs\components\Lang::getLangId()])->one();
        if ($model instanceof Post) {
            return $model;
        }
        throw new NotFoundHttpException('Page is not founded');
    }

    public function actionSlider()
    {
        $post = Post::find()->andWhere(['type' => Post::TYPE_SLIDER])->andWhere(['status' => Post::STATUS_ACTIVE, 'lang' => \oks\langs\components\Lang::getLangId()])->limit(2)->all();
    }
}
