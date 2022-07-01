<?php

namespace backend\widgets;

use yii\base\Widget;

/**
 * Class TopBar
 * @package backend\widgets
 */
class Breadcrumbs extends Widget
{
    public function run()
    {
        return $this->render('breadcrumbs');
    }
}