<?php

namespace backend\forms;

use common\models\SendEmail;
use common\models\User;
use common\modules\repository\UserRepository;
use Yii;
use yii\base\Model;

/**
 * Class UserUpdateForm
 * @package backend\forms
 */
class CheckEmail extends Model
{
    public $id;
    public $code;
    public $user;

    public function rules()
    {
        return [
            [['code'], 'required'],
            ['code', 'validateCode'],
        ];
    }

    public function validateCode($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $model = SendEmail::find()
                ->andWhere(['user_id' => $this->id])
                ->andWhere(['status' => 1])
                ->orderBy(['id' => SORT_DESC])
                ->one();
            if ($model) {
//                if ($model->created_at < (time() - 60)) {
//                    $this->addError($attribute, 'Approval has expired');
//                }

                if ($model->code != $this->code) {
                    $this->addError($attribute, 'code is incorrect');
                }
            }
        }
    }

    /**
     * @return array|\yii\web\Response
     */
    public function checkEmail()
    {
        if (!$this->validate()) {
            return $this->errors;
        }

        $model = SendEmail::find()
            ->andWhere(['user_id' => $this->id])
            ->andWhere(['status' => 1])
            ->orderBy(['id' => SORT_DESC])
            ->one();
        $user = User::findOne($model->user_id);
        if ($this->code == $model->code) {
            $model->updateAttributes(['status' => 2]);
            $user->updateAttributes(['status' => User::STATUS_ACTIVE]);
            Yii::$app->user->login($user, 3600 * 24 * 30);
            return Yii::$app->controller->goHome();
        }
        return Yii::$app->controller->goBack();
    }

}
