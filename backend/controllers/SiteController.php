<?php

namespace backend\controllers;

use backend\forms\CheckEmail;
use common\models\City;
use common\models\Country;
use common\models\Region;
use common\models\User;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use yii\web\NotFoundHttpException;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error', 'email'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        Yii::$app->layout = 'login';
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            $model->password = '';

            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * @param $id
     * @return array|string
     * @throws \yii\base\Exception
     */
    public function actionEmail($id)
    {
        Yii::$app->layout = 'login';

        $user = User::findOne($id);

        if (!is_object($user)) {
            throw new NotFoundHttpException('User is not founded');
        }

        if ($user->status == User::STATUS_ACTIVE) {
            Yii::$app->session->setFlash('success', 'This account is verified');
            return $this->redirect(['index']);
        }


        $model = new CheckEmail(['id' => $id]);
        $model->load(\Yii::$app->request->post());
        if (!($model->checkEmail())) {
            return $model->getErrors();
        }

        Yii::$app->session->setFlash('success', 'Your account has been verified');

        return $this->render('email', [
            'model' => $model,
        ]);
//        }
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
