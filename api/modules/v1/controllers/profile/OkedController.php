<?php

namespace epa\v1\controllers\profile;
use api\components\ApiController;
use common\modules\profile\models\Oked;
use common\modules\profile\models\OkedSearch;

/**
 * OkedController implements the CRUD actions for Oked model.
 */
class OkedController extends \common\modules\profile\modules\api\controllers\OkedController
{
    public function actionFields()
    {
        $model = new Oked();
        return $model->attributes;
    }
}
