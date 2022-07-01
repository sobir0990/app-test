<?php

namespace epa\v1\controllers\profile;

use api\components\ApiController;
use common\modules\profile\models\Soogu;
use common\modules\profile\models\SooguSearch;

/**
 * SooguController implements the CRUD actions for Soogu model.
 */
class SooguController extends \common\modules\profile\modules\api\controllers\SooguController
{
    public function actionFields()
    {
        $model = new Soogu();
        return $model->attributes;
    }
}
