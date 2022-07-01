<?php

namespace backend\widgets;

use common\models\User;
use yii\base\Widget;
use yii\web\NotFoundHttpException;

/**
 * Class TopBar
 * @package backend\widgets
 */
class TopBar extends Widget {
    /**
     * @return string
     * @throws NotFoundHttpException
     */
    public function run()
    {
        $user_id = \Yii::$app->user->id;

        $user = User::findOne($user_id);


        return $this->render('topBar', [
            'user' => $user
        ]);
    }

}