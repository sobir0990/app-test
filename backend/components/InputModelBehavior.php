<?php
/**
 * Created by PhpStorm.
 * User: OKS
 * Date: 17.10.2018
 * Time: 18:41
 */

namespace backend\components;


use common\modules\settings\models\Settings;
use oks\filemanager\models\Files;
use yii\behaviors\AttributeBehavior;

class InputModelBehavior extends AttributeBehavior {
    /**
     * @var string
     */
    public $delimitr = ",";

    /**
     * @param $attribute
     * @return array|bool|Files[]
     */
    public function allFiles($attribute, $returnActiveQuery = false)
    {
        $data = $this->owner->{$attribute};
        print_r($data);exit();
        if (strlen($data) == 0) {
            return Settings::value('default_photo');
        }
        if ($data{0} == $this->delimitr) {
            $data = substr($data, 1);
        }
        if (strlen($data) == 0) {
            return Settings::value('default_photo');
        }
        $data = explode($this->delimitr, $data);
        if (!is_array($data)) {
            return Settings::value('default_photo');
        }
        if (!count($data)) {
            return Settings::value('default_photo');
        }

        $elements = Files::find()->where(['in', Files::primaryKey()[0], $data]);
        if ($returnActiveQuery) {
            return $elements;
        }

        return $elements->all();
    }

}