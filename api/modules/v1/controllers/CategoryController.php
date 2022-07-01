<?php

namespace epa\v1\controllers;

use common\models\Category;
use common\models\CategorySearch;
use oks\langs\components\Lang;
use yii\web\NotFoundHttpException;

class CategoryController extends \api\components\ApiController
{
    public $modelClass = Category::class;
    public $searchModelClass = CategorySearch::class;

    public function actionChilds($id)
    {
        return Category::findAll(['parent_id' => $id]);
    }

    public function actionList($type)
    {
        return Category::getList($type);
    }

    /**
     * @param $slug
     * @return Category|mixed|null
     * @throws NotFoundHttpException
     */
    public function actionBySlug($slug)
    {
        $model = $this->findModel($slug);

        return $model;
    }

    /**
     * @param $slug
     * @return Category|mixed|null
     * @throws NotFoundHttpException
     */
    public function findModel($slug)
    {
        $model = Category::findOne(['slug' => $slug, 'lang' => Lang::getLangId(), 'status' => 1]);
        if ($model instanceof Category) {
            return $model;
        }
        throw new NotFoundHttpException('Category not found');
    }

}
