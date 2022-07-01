<?php

namespace epa\v1\controllers\profile;

use common\modules\profile\models\Bank;
use common\modules\profile\models\BankSearch;

/**
 * Class BankController
 * @package api\modules\v1\controllers\profile
 */
class BankController extends \common\modules\profile\modules\api\controllers\BankController
{
    public function actionFields()
    {
        $model = new Bank();
        return $model->attributes;
    }
}
