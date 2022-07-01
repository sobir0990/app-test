<?php

namespace backend\forms;

use common\models\User;
use common\modules\repository\UserRepository;
use Yii;
use yii\base\Model;

/**
 * Class UserUpdateForm
 * @package backend\forms
 */
class CreateUserForm extends Model
{
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
            [['email', 'password_hash', 'role'], 'required'],
            [['status'], 'default', 'value' => null],
            [['status'], 'integer'],
            [['username', 'password_hash', 'email'], 'string', 'max' => 255],
            [['username'], 'validateUsername'],
            ['email', 'validateEmail'],

        ];
    }

    public function init()
    {
        /**
         * @var $userRepository UserRepository
         */
        $this->_repository = Yii::$container->get(UserRepository::class);
    }

    public function validateEmail($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $model = $this->_repository->findByEmail($this->email);
            if ($model) {
                $this->addError($attribute, 'This email has already been taken');
            }
        }
    }

    public function validateUsername($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $model = $this->_repository->findByUsername($this->username);
            if ($model) {
                $this->addError($attribute, 'This username has already been taken');
            }
        }
    }


    /**
     * @return array|User|null
     * @throws \yii\base\Exception
     */
    public function create()
    {
        if ($this->validate()) {
            $user = new User();
            $user->username = $this->username;
            $user->generateAuthKey();
            $user->email = $this->email;
            $user->status = User::STATUS_INACTIVE;
            $user->role = $this->role;
            $user->setPassword($this->password_hash);

            if (!$user->save()) {
                return $user->getErrors();
            }
            $this->_repository->createRole($user, $this->role);
            return $user;
        }
        return false;
    }

}
