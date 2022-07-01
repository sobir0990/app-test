<?php
namespace epa\v1\controllers\profile;
use api\components\ApiController;
use common\modules\profile\models\Unit;
use common\modules\profile\models\UnitSearch;
/**
 * UnitController implements the CRUD actions for Unit model.
 */
class UnitController extends \common\modules\profile\modules\api\controllers\UnitController
{
    public function actionFields()
    {
        $model = new Unit();
        return $model->attributes;
    }
}
