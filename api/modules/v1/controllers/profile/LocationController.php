<?php

namespace epa\v1\controllers\profile;

use common\modules\profile\models\Bank;
use common\modules\profile\models\BankSearch;
use common\modules\profile\models\Location;
use yii\data\ActiveDataProvider;

/**
 * Class BankController
 * @package api\modules\v1\controllers\profile
 */
class LocationController extends \common\modules\profile\modules\api\controllers\LocationController
{
    public function actionRoot($id = null)
    {
        $models = Location::find()->andWhere(['parent_id' => $id]);

        return new ActiveDataProvider([
            'query' => $models
        ]);
    }

    public function actionFields()
    {
        $model = new Location();
        return $model->attributes;
    }
}
