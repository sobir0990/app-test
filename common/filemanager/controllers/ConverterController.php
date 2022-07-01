<?php
/**
 * @author O`tkir   <https://gitlab.com/utkir24>
 * @package prokuratura.uz
 *
 */

namespace common\filemanager\controllers;

use common\filemanager\models\Files;
use Yii;
use yii\console\Controller;

/**
 * Class ConverterController
 * @package common\filemanager\controllers
 *
 * console
 * controller map
 * common\filemanager\controllers
 */
class ConverterController extends Controller
{
    /**
     * @var string
     */
    public $defaultAction = "file";

    /**
     * @return int
     */
    public function actionFile(){
        Files::cron();
        return 0;
    }
}