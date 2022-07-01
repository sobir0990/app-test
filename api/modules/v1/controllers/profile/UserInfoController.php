<?php
namespace epa\v1\controllers\profile;
use api\components\ApiController;
use common\modules\profile\models\UserInfo;
use common\modules\profile\models\UserInfoSearch;
/**
 * UserInfoController implements the CRUD actions for UserInfo model.
 */
class UserInfoController extends \common\modules\profile\modules\api\controllers\UserInfoController
{
    public function actionFields()
    {
        $model = new UserInfo();
        return $model->attributes;
    }
}
