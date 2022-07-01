<?php
namespace epa\v1\controllers\profile;
use api\components\ApiController;
use common\modules\profile\models\Certificates;
use common\modules\profile\models\CertificatesSearch;

/**
 * CertificatesController implements the CRUD actions for Certificates model.
 */
class CertificatesController extends \common\modules\profile\modules\api\controllers\CertificatesController
{
    public function actionFields()
    {
        $model = new Certificates();
        return $model->attributes;
    }
}
