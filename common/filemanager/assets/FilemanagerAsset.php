<?php
/**
 * @author O`tkir   <https://gitlab.com/utkir24>
 * @package prokuratura.uz
 *
 */

namespace common\filemanager\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class FilemanagerAsset extends AssetBundle
{
    public $sourcePath = '@common/filemanager/assets';

    public $js = [
        "plugin.js"
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
        'dosamigos\ckeditor\CKEditorAsset',
        'dosamigos\ckeditor\CKEditorWidgetAsset'
    ];
}


