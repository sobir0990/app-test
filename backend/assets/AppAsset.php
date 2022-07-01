<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $sourcePath = '@backend/assets/app/admin/';

    public $css = [
        'css/sweetalert2.min.css',
        'plugins/bootstrap/css/bootstrap.min.css',
        'plugins/font-awesome/css/font-awesome.css',
        'plugins/jquery-scrollbar/jquery.scrollbar.css',
        'plugins/select2/css/select2.min.css',
        'plugins/switchery/css/switchery.min.css',
        'plugins/bootstrap-datepicker/css/datepicker3.css',
        'plugins/jquery-nestable/jquery.nestable.css',
        'css/dashboard.widgets.css',
        'css/pages-icons.css',
        'css/themes/light.css',
        'css/main.css',
        'css/site.css',
    ];
    public $js = [
        'js/sweetalert2.min.js',
        'plugins/feather-icons/feather.min.js',
        'plugins/modernizr.custom.js',
        'plugins/jquery-ui/jquery-ui.min.js',
        "plugins/jquery-scrollbar/jquery.scrollbar.min.js",
        "plugins/select2/js/select2.full.min.js",
        "plugins/switchery/js/switchery.min.js",
        'pages/pages.js',
        'js/scripts.js',
        'js/form_elements.js',
        'plugins/bootstrap-datepicker/js/bootstrap-datepicker.js',
        'js/jquery.nestable.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];
}

