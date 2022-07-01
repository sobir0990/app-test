<?php

namespace epa\v1\controllers\profile;

use api\components\ApiController;
use common\filemanager\models\Files;
use common\filemanager\models\FilesSearch;
use yii\web\UploadedFile;

/**
 * Class TypeController
 * @package api\modules\v1\controllers
 */
class FilemanagerController extends ApiController
{
    public $modelClass = Files::class;
    public $searchModel = FilesSearch::class;

    /**
     * @return array|bool|Files
     */
    public function actionUpload()
    {
        $keys = array_keys($_FILES);
        if(!count($keys)){return false;}
        $response = [];
        foreach ($keys as $key):
            $files = UploadedFile::getInstancesByName( $key);
            if(count($files))
            {
                foreach ($files as $file):
                    $model = new Files();
                    $model->file_data = $file;
                    $model->save();
                    if($model->hasErrors()):
                        return $model->getErrors();
                    endif;

                    return $model;
                endforeach;
            }
        endforeach;
        return $response;
    }
}