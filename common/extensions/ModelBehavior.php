<?php
/**
 * Created by PhpStorm.
 * User: OKS
 * Date: 04.09.2019
 * Time: 10:35
 */

namespace common\extensions;


use yii\base\Request;

class ModelBehavior extends \oks\langs\components\ModelBehavior
{
    public function initial()
    {
        if(!\Yii::$app->request instanceof \yii\web\Request){
            return;
        }
        return parent::initial(); // TODO: Change the autogenerated stub
    }
}