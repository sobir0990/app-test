<?php
namespace epa\v1\controllers\profile;
use api\components\ApiController;
use common\modules\profile\models\UserFaq;
use common\modules\profile\models\UserInfo;
use common\modules\profile\models\UserInfoSearch;
use yii\data\ActiveDataFilter;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/**
 * UserFaqController implements the CRUD actions for UserInfo model.
 */
class UserFaqController extends \common\modules\profile\modules\api\controllers\UserFaqController
{
    public function actions()
    {
        $actions = parent::actions();
        unset($actions['index']);
        return $actions;
//        return ArrayHelper::merge($actions, [
//            'index' => [
//                'class' => 'yii\rest\IndexAction',
//                'modelClass' => $this->modelClass,
//                'dataFilter' => [
//                    'class' => ActiveDataFilter::class,
//                    'searchModel' => $this->searchModelClass,
//                    'attributes' => [
//                        'filter' => [
//                            'status' => UserFaq::STATUS_ACTIVE,
////                            'user_id' => \Yii::$app->user->id
//                        ],
//                    ],
//                ]
//            ],
//        ]);
    }

    public function actionIndex()
    {
        $models = UserFaq::find()->andWhere(['user_id' => \Yii::$app->user->id, 'status' => UserFaq::STATUS_ACTIVE]);

        return new ActiveDataProvider([
            'query' => $models
        ]);
    }

    public function actionFields()
    {
        $model = new UserFaq();
        return $model->attributes;
    }

    /**
     * @return array|UserFaq
     * @throws \yii\base\InvalidConfigException
     */
    public function actionDraft()
    {
        $requestParams = \Yii::$app->request->getBodyParams();

        $model = new UserFaq();
        $model->load($requestParams, '');
        $model->status = UserFaq::STATUS_DRAFT;
        if ($model->save()) {
            return $model;
        }
        return $model->errors;
    }

    public function actionGetDrafts($user_id = null)
    {
        if ($user_id == null) {
            $user_id = \Yii::$app->user->id;
        }

        $models = UserFaq::find()
            ->andWhere(['status' => UserFaq::STATUS_DRAFT, 'user_id' => $user_id]);

        return new ActiveDataProvider([
            'query' => $models
        ]);
    }
}
