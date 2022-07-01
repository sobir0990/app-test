<?php
namespace epa\v1\controllers\profile;
use api\components\ApiController;
use common\modules\profile\models\Enterprise;
use common\models\epa\EnterpriseSearch;
/**
 * EnterpriseController implements the CRUD actions for Enterprise model.
 */
class EnterpriseController extends \common\modules\profile\modules\api\controllers\EnterpriseController
{
    public function actions()
    {
        $actions = parent::actions();
        unset($actions['index']);
        unset($actions['create']);
        return $actions;
    }

    /**
     * @return array|Enterprise
     * @throws \yii\base\InvalidConfigException
     */
    public function actionCreate()
    {
        $requestParams = \Yii::$app->request->getBodyParams();
        if (empty($requestParams)) {
            $requestParams = \Yii::$app->request->getQueryParams();
        }
        $model = new Enterprise();
        $model->load($requestParams, '');
        if ($model->save()) {
            return $model;
        }
        \Yii::$app->response->statusCode = 400;
        return $model;

    }
    public function actionIndex($user_id = null)
    {
        if ($user_id == null) {
            $user_id = \Yii::$app->user->id;
        }

        $model = Enterprise::findOne(['user_id' => $user_id]);
        if ($model instanceof Enterprise) {
            return $model;
        }
    }

    public function actionFields()
    {
        $model = new Enterprise();
        return $model->attributes;
    }
}
