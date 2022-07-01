<?php

namespace backend\forms;

use common\models\User;
use Yii;
use yii\base\Model;
use yii\web\NotFoundHttpException;
use common\modules\repository\UserRepository;

/**
 * Class UserUpdateForm
 * @package backend\forms
 */
class UpdateUserForm extends Model
{
    public $id;
    public $username;
    public $email;
    public $role;
    public $status;
    public $password_hash;

    public $user;

    public $_repository;

    public function rules()
    {
        return [
            [['email', 'role'], 'required'],
            [['status'], 'default', 'value' => null],
            [['status', 'role'], 'integer'],
            [['username', 'password_hash', 'email'], 'string', 'max' => 255],
//            [['email'], 'unique'],
            [['username'], 'validateUsername'],
            ['email', 'validateEmail'],

        ];
    }

    /**
     * @throws NotFoundHttpException
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    public function init()
    {
        $this->user = $user = User::findOne($this->id);
        $this->setAttributes($this->user->attributes);
        if (!($this->user instanceof User)) {
            throw new NotFoundHttpException('User is not founded');
        }

        /**
         * @var $userRepository UserRepository
         */
        $this->_repository = Yii::$container->get(UserRepository::class);
    }

    /**
     * @param $attribute
     * @param $params
     */
    public function validateEmail($attribute, $params)
    {

        if (!$this->hasErrors()) {

            $model = User::find()
                ->andWhere(['not', ['id' => $this->id]])
                ->andWhere(['email' => $this->email])
                ->one();
            if ($model) {
                $this->addError($attribute, 'This email has already been taken');
            }
        }
    }

    /**
     * @param $attribute
     * @param $params
     */
    public function validateUsername($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $model = User::find()
                ->andWhere(['not', ['id' => $this->id]])
                ->andWhere(['username' => $this->username])
                ->one();
            if ($model) {
                $this->addError($attribute, 'This username has already been taken');
            }
        }
    }


    /**
     * @return array|User|null
     * @throws \yii\base\Exception
     */
    public function update()
    {
        if (!$this->validate()) {
            return $this->errors;
        }

        $user = User::findOne($this->id);
        $user->username = $this->username;
        $user->email = $this->email;
        $user->status = $this->status;
        $user->role = $this->role;
        if (!empty($this->password_hash)) {
            $user->password_hash = \Yii::$app->getSecurity()->generatePasswordHash($this->password_hash);
        }
        if (!$user->save()) {
            return $user->getErrors();
        }

        $this->_repository->updateRole($user, $this->role);

        return $user;
    }
}
