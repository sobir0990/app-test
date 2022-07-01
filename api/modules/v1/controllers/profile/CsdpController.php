<?php
namespace epa\v1\controllers\profile;
use api\components\ApiController;
use common\modules\profile\models\Csdp;
use common\modules\profile\models\CsdpSearch;

/**
 * CsdpController implements the CRUD actions for Csdp model.
 */
class CsdpController extends \common\modules\profile\modules\api\controllers\CsdpController
{
    public function actionFields()
    {
        $model = new Csdp();
        return $model->attributes;
    }
}
