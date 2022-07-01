<?php
namespace epa\v1\controllers\profile;

use api\components\ApiController;
use common\modules\profile\models\Materials;
use common\modules\profile\models\MaterialsSearch;

/**
 * MaterialsController implements the CRUD actions for Materials model.
 */
class MaterialsController extends \common\modules\profile\modules\api\controllers\MaterialsController
{
    public function actionFields()
    {
        $model = new Materials();
        return $model->attributes;
    }
}
