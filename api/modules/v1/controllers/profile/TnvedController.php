<?php
namespace epa\v1\controllers\profile;
use api\components\ApiController;
use common\modules\profile\models\Tnved;
use common\modules\profile\models\TnvedSearch;

/**
 * TnvedController implements the CRUD actions for Tnved model.
 */
class TnvedController extends \common\modules\profile\modules\api\controllers\TnvedController
{
    public function actionFields()
    {
        $model = new Tnved();
        return $model->attributes;
    }
}
