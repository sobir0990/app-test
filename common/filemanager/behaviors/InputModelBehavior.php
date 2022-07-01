<?php
/**
 * @author O`tkir   <https://gitlab.com/utkir24>
 * @package prokuratura.uz
 *
 */

namespace common\filemanager\behaviors;

/**
 *
 * @author Jakhar <javhar_work@mail.ru>
 *
 */

use Yii;
use yii\behaviors\AttributeBehavior;
use yii\db\ActiveRecord;
use \common\filemanager\models\Files;


class InputModelBehavior extends AttributeBehavior
{
    /**
     * @var string
     */
    public $delimitr = ",";

    /**
     * @param $attribute
     * @return array|bool|Files[]|\common\filemanager\models\FilesQuery
     */
    public function allFiles($attribute,$returnActiveQuery = false){
        $data = $this->owner->{$attribute};
        if(strlen($data) == 0){return false;}
        if($data{0} == $this->delimitr)
        {
            $data = substr($data,1);
        }
        if(strlen($data) == 0){return false;}
        $data = explode($this->delimitr,$data);
        if(!is_array($data)){return false;}
        if(!count($data)){return false;}

        $elements = Files::find()->where(['in', Files::primaryKey()[0], $data]);
        if($returnActiveQuery){return $elements;}
        return $elements->all();
    }

}