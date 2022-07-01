<?php
namespace epa\v1\controllers\profile;
use api\components\ApiController;
use common\modules\profile\models\StorageBalance;
use common\modules\profile\models\StorageBalanceSearch;

/**
 * StorageBalanceController implements the CRUD actions for StorageBalance model.
 */
class StorageBalanceController extends \common\modules\profile\modules\api\controllers\StorageBalanceController
{
    public function actionFields()
    {
        $model = new StorageBalance();
        return $model->attributes;
    }
}
