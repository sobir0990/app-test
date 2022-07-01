<?php
/**
 * Created by PhpStorm.
 * User: OKS
 * Date: 03.09.2019
 * Time: 12:33
 */

namespace backend\components;

use backend\models\SubscribeHelper;
use common\models\User;
use common\modules\profile\models\UserSubscribeModels;
use Yii;

class Subscribe extends \yii\base\BaseObject implements \yii\queue\Job
{
    const TYPE_NEWS = 1;
    const TYPE_QUOTES = 2;
    const TYPE_DIGEST = 3;

    public $title;
    public $content;
    public $url;
    public $modelType;
    public $modelId;

    public $data = [];

    public function execute($queue)
    {
        $this->data = SubscribeHelper::getModel($this->modelId, $this->modelType);
        echo 'data';
        $users = UserSubscribeModels::find()
            ->andWhere(['model_type' => $this->modelType])->all();
        echo 'users';
        $i = 0;
        foreach ($users as $user) {
            $_user = User::findOne($user->user_id);
            $this->title = $this->data['title'];
            $this->content = $this->data['content'];
            $this->url = $this->data['url'];
echo "user";
            Yii::$app->mailer->compose()
                ->setFrom([getenv('EMAIL_NAME') => 'TEXT'])
                ->setTo($_user->email)
                ->setSubject('Тема сообщения')
                ->setTextBody($this->title)
                ->setHtmlBody($this->content . '<br/> Go to message - ' . $this->url)
                ->send();
            echo 'send';
            $i++;
        }
        echo 'success - ' . $i;
    }
}