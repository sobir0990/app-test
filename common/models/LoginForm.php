<?php

namespace common\models;

use Yii;
use yii\base\Model;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $email;
    public $password;
    public $rememberMe;

//info@admin.com
    private $_user;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['email', 'password'], 'required'],
            ['rememberMe', 'boolean'],
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     * @throws \yii\base\Exception
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !Yii::$app->security->validatePassword($this->password, $user->password_hash)) {
                $this->addError($attribute, 'Incorrect email or password.');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            $user = $this->getUser();
            if ($user->status == User::STATUS_ACTIVE) {
                return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
            } else {
                $this->sendEmail($user);
                Yii::$app->controller->redirect(['site/email', 'id' => $user->id]);
            }
        }

        return false;
    }

    public function sendEmail($user)
    {
        $model = new SendEmail();
        $code = rand(1000, 9999);
        $model->user_id = $user->id;
        $model->code = $code;
        $model->created_at = time() + 60;
        $model->updated_at = time();
        $model->status = 1;
        if ($model->save()) {
            Yii::$app->mailer->compose()
                ->setFrom('admin@mail.ru')
                ->setTo($user->email)
                ->setSubject('Уведемление с сайта')// тема письма
                ->setTextBody('Текстовая версия письма (без HTML)')
                ->setHtmlBody('<p>code : ' . $model->code . '</p>')
                ->send();
        }

    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findByEmail($this->email);
        }

        return $this->_user;
    }
}
