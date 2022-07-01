<?php
namespace epa\v1\controllers\profile;
use api\components\ApiController;
use common\modules\profile\models\Products;
use common\models\epa\ProductsSearch;
use yii\data\ActiveDataProvider;

/**
 * ProductsController implements the CRUD actions for Products model.
 */
class ProductsController extends \common\modules\profile\modules\api\controllers\ProductsController
{
    public function actions()
    {
        $actions = parent::actions();
        unset($actions['index']);
        return $actions;
    }

    public function actionIndex()
    {
        $models = Products::find()
            ->andWhere(['user_id' => \Yii::$app->user->id]);

        return new ActiveDataProvider([
            'query' => $models
        ]);
    }
}
