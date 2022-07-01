<?php
/**
 * Created by PhpStorm.
 * User: OKS
 * Date: 26.04.2019
 * Time: 22:48
 */

namespace backend\controllers;

use backend\forms\CreateUserForm;
use backend\forms\UpdateUserForm;
use common\models\User;
use common\models\UserSearch;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;

/**
 * Class UserController
 * @package backend\controllers
 */
class UserController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
//                    [
//                        'allow' => true,
//                        'actions' => ['create', 'update', 'delete'],
//                        'roles' => ['admin'],
//                    ],
//                    [
//                        'allow' => true,
//                        'actions' => ['index'],
//                        'roles' => ['user'],
//                    ],
                    [
                        'allow' => false,
                        'roles' => ['?'],
                        'denyCallback' => function ($rule, $action) {
                            return $this->redirect(Url::toRoute(['/site/login']));
                        }
                    ],
                    [
                        'actions' => ['create', 'update', 'delete'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            /** @var User $user */
                            $user = Yii::$app->user->getIdentity();
                            if ($user->isUser()) {
                                throw new HttpException(403, Yii::t('app', 'You are not allowed to perform this action.'));
                            }
                            return true;
                        },
                    ],
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }


    /**
     * Lists all UserFaq models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate()
    {
        $model = new CreateUserForm();
        if ($model->load(Yii::$app->request->post()) && $model->create()) {
            Yii::$app->session->setFlash('success', 'Успешно создан');
            return $this->redirect(['index']);
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * @param $id
     * @return array|string
     * @throws NotFoundHttpException
     * @throws \yii\base\Exception
     */
    public function actionUpdate($id)
    {
        if (\Yii::$app->request->post()) {
            $model = new UpdateUserForm(['id' => $id]);
            $model->load(\Yii::$app->request->post());

            if (!($model->update())) {
                return $model->getErrors();
            }

            Yii::$app->session->setFlash('success', 'Успешно редактирован');

            return $this->render('update', [
                'model' => $model
            ]);
        }

        $user = $this->findModel($id);
        return $this->render('update', [
            'model' => $user
        ]);
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if ($model) {
            $model->delete();
        }
        return $this->redirect(['index']);

    }


    /**
     * @param $id
     * @return User|null
     * @throws NotFoundHttpException
     */
    public function findModel($id)
    {
        $model = User::findOne($id);
        if ($model instanceof User) {
            return $model;
        }
        throw new NotFoundHttpException(__('User is not founded'));
    }

}