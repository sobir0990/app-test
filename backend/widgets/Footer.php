<?php

namespace backend\widgets;

use common\models\User;
use yii\base\Widget;
use yii\web\NotFoundHttpException;

/**
 * Class Footer
 * @package backend\widgets
 */
class Footer extends Widget {
    /**
     * @return string
     * @throws NotFoundHttpException
     */
    public function run()
    {
        return $this->render('footer');
    }

}