<?php
namespace epa\v1\controllers\profile;

use api\components\ApiController;
use common\models\Token;
use common\modules\profile\forms\LoginForm;
use common\models\User;
use common\models\UserSearch;
use common\modules\profile\forms\CreateUserForm;
use common\modules\profile\forms\UpdateUserForm;
use Yii;
use yii\data\ActiveDataFilter;
use yii\data\ActiveDataProvider;
use yii\filters\ContentNegotiator;
use yii\filters\RateLimiter;
use yii\helpers\ArrayHelper;
use yii\rest\Controller;
use yii\rest\OptionsAction;
use yii\web\Response;

/**
 * UserInfoController implements the CRUD actions for UserInfo model.
 */
class UserController extends Controller
{
    public $modelClass = User::class;
    public $searchModelClass = UserSearch::class;

    protected $requestParams;

    public $serializer = [
        'class' => '\yii\rest\Serializer',
        'collectionEnvelope' => 'data',
        'expandParam' => 'include'
    ];

    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'contentNegotiator' => [
                'class' => ContentNegotiator::class,
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                    'application/xml' => Response::FORMAT_XML,
                ],
                'languages' => array(
                    'en',
                    'uz',
                    'ru'
                ),
                'formatParam' => '_f',
                'languageParam' => '_l',
            ],
            'rateLimiter' => [
                'class' => RateLimiter::class,
            ],
        ]);
    }

    public function actions()
    {
        return [
            'index' => [
                'class' => 'yii\rest\IndexAction',
                'modelClass' => $this->modelClass,
                'dataFilter' => [
                    'class' => ActiveDataFilter::class,
                    'searchModel' => $this->searchModelClass,
                ]

            ],
            'view' => [
                'class' => 'yii\rest\ViewAction',
                'modelClass' => $this->modelClass,
            ],
            'options' => [
                'class' => OptionsAction::class,
            ]
        ];
    }

    /**
     * @return array
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     */
    public function actionLogin()
    {
//        $requestParams = \Yii::$app->request->getQueryParams();
        if (empty($requestParams)) {
            $requestParams = \Yii::$app->request->getBodyParams();
        }
        $model = new LoginForm();
        if ($model->load($requestParams, '') && $token = $model->login()) {
            return $token;
        } else {
            $model->addError('username', __('Incorrect login or password'));
            Yii::$app->response->statusCode = 401;
            return $model->getErrors();
        }
    }

    public function actionLogout()
    {
        $user = $this->findModel(Yii::$app->user->id);

        $token = Token::findOne(['user_id' => $user->id]);
        $token->updateAttributes(['expires' => time()]);
        return 'success';
    }

    /**
     * @return array|\common\models\Token|\yii\db\ActiveRecord|null
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     */
    public function actionSignUp()
    {
//        $requestParams = \Yii::$app->request->getQueryParams();
        if (empty($requestParams)) {
            $requestParams = \Yii::$app->request->getBodyParams();
        }

        $model = new CreateUserForm();
        $model->load($requestParams, '');
        if (!($user = $model->create())) {
            return $model->getErrors();
        }
        return $user;
    }

    /**
     * @param $id
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    public function actionUpdate($id)
    {
        $requestParams = \Yii::$app->request->getBodyParams();
        if (empty($requestParams)) {
            $requestParams = \Yii::$app->request->getQueryParams();
        }

        $userForm = new UpdateUserForm(['id' => $id]);
        $userForm->load($requestParams, '');
        if (!($user = $userForm->update())) {
            return $userForm->getErrors();
        }

        return $user;
    }

    public function actionApprove()
    {
        $id = Yii::$app->user->id;
        $user = $this->findModel($id);
        $user->updateAttributes(['status' => User::STATUS_CONFIRMATION]);
        return $user;
    }

    /**
     * @param $id
     * @return User|null
     */
    public function findModel($id)
    {
        $model = User::findOne($id);
        if ($model instanceof User) {
            return $model;
        }
        throw new \DomainException('User not found', 404);
    }

    public function actionGetMe()
    {
        return \Yii::$app->user->identity;
    }

}
