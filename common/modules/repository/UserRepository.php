<?php

namespace common\modules\repository;

use common\models\User;
use Yii;
use yii\web\NotFoundHttpException;

class UserRepository
{

    public function findByEmail($email)
    {
        return User::find()->andWhere(['email' => $email])->one();
    }

    public function findByUsername($username)
    {
        return User::find()->andWhere(['username' => $username])->one();
    }

    public function createRole($model, $role)
    {
        $auth = Yii::$app->authManager;
        if ($role == User::ROLE_ADMIN) {
            $userRole = $auth->getRole('admin');
            $auth->assign($userRole, $model->id);
        } elseif ($role == User::ROLE_USER) {
            $userRole = $auth->getRole('user');
            $auth->assign($userRole, $model->id);
        }
    }

    public function updateRole($model, $role)
    {
        $auth = Yii::$app->authManager;
        if ($role == User::ROLE_ADMIN) {
            $userRole = $auth->getRole('admin');
            $auth->revokeAll($model->id);
            $auth->assign($userRole, $model->id);
        } elseif ($role == User::ROLE_USER) {
            $userRole = $auth->getRole('user');
            $auth->revokeAll($model->id);
            $auth->assign($userRole, $model->id);
        }
    }


    /**
     * @param $id
     * @return User
     * @throws NotFoundHttpException
     */
    public function getByID($id): User
    {
        /**
         * @var $user User
         */
        $user = User::findOne($id);
        if (!($user instanceof User)) {
            throw new NotFoundHttpException("User not founded");
        }
        return $user;
    }

}