<?php

namespace backend\forms;

use backend\helpers\ImportStatisticsHelpers;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use yii\base\Model;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * Class UserUpdateForm
 * @package backend\forms
 */
class ImportFileForm extends Model
{
    public $file;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['file'], 'file', 'extensions' => 'xlsx']
        ];
    }

    public function init()
    {
        parent::init();
    }

    /**
     * @return array|bool
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     * @throws \yii\web\NotFoundHttpException
     */
    public function data()
    {
        if (!$this->validate()) {
            return false;
        }

        return $this->import();
    }

    /**
     * @return array
     * @throws NotFoundHttpException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    public function import()
    {
        $reader = new Xlsx();

        if (!array_keys($_FILES)) {
            throw new NotFoundHttpException('Request dosn\'t have file');
        }

        $keys = array_keys($_FILES);
        $spreadSheet = null;
        foreach ($keys as $key) {
            $files = UploadedFile::getInstancesByName($key);
            if (count($files)) {
                foreach ($files as $file):
                    $spreadSheet = $reader->load($file->tempName);
                    break;
                endforeach;
            }
        }

        return $spreadSheet->getActiveSheet()->toArray();
    }
}
