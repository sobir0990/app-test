<?php
namespace epa\v1\controllers\profile;
use api\components\ApiController;
use common\modules\profile\models\Subscribe;
use common\modules\profile\models\SubscribeSearch;

/**
 * SubscribeController implements the CRUD actions for Subscribe model.
 */
class SubscribeController extends \common\modules\profile\modules\api\controllers\SubscribeController
{
    public function actionFields()
    {
        $model = new Subscribe();
        return $model->attributes;
    }
}
