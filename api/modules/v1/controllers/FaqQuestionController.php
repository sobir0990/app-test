<?php

namespace epa\v1\controllers;

use api\components\ApiController;
use common\models\FaqAnswer;
use common\models\FaqAnswerSearch;
use common\models\FaqQuestion;
use common\models\FaqQuestionSearch;
use oks\langs\components\Lang;
use Yii;
use yii\data\ActiveDataFilter;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\rest\IndexAction;
use yii\web\NotFoundHttpException;

class FaqQuestionController extends ApiController
{
    public $modelClass = FaqQuestion::class;
    public $searchModelClass = FaqQuestionSearch::class;

    public function actionAnswers($id)
    {
        return $this->getFilteredData(FaqAnswer::find()->where(['question_id' => $id]), FaqAnswerSearch::class);
    }

    public function actions()
    {
        $actions = parent::actions();

        unset($actions['index'], $actions['view']);
        return ArrayHelper::merge($actions, [
            'index' => [
                'class' => IndexAction::class,
                'modelClass' => FaqQuestion::class,
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

        $query = FaqQuestion::find();

        $filter = null;
        $dataFilter = new ActiveDataFilter();
        $dataFilter->searchModel = FaqQuestionSearch::class;

        if (!empty($title = Yii::$app->request->getQueryParam('filter')['title'])) {
            $query->andWhere(['ILIKE', 'title', $title]);
        }

        if (($category = Yii::$app->request->getQueryParam('filter')['category']) !== null) {
            $query->andWhere(['category' => $category]);
        }

        $query->andWhere(['lang' => Lang::getLangId()]);

        return new ActiveDataProvider([
            'query' => $query
        ]);
    }

    public function actionView($id)
    {
        $faq = FaqQuestion::findOne($id);
        $faq->updateCounters(['view' => 1]);
        return $faq;
    }
    /**
     * @param $id
     * @return FaqQuestion|null
     * @throws NotFoundHttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionUseful($id)
    {
        $requestParams = Yii::$app->request->getBodyParams();
        if (empty($requestParams)) {
            $requestParams = Yii::$app->request->getQueryParams();
        }

        if ($id == null) {
            throw new NotFoundHttpException('Unknown id');
        }
        $model = FaqQuestion::findOne($id);
        $model->updateCounters(['useful' => 1]);

        return $model;
    }

    /**
     * @return array|FaqQuestion
     * @throws \yii\base\InvalidConfigException
     */
    public function actionCreateFaq()
    {
        $requestParams = Yii::$app->request->getBodyParams();
        if (empty($requestParams)) {
            $requestParams = Yii::$app->request->getQueryParams();
        }

        $model = new FaqQuestion();

        if ($model->load($requestParams, '') && $model->save()) {
            return $model;
        }

        return $model->getErrors();
    }
}
