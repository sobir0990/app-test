<?php

namespace backend\widgets;

use yii\base\Widget;

/**
 * Class TopBar
 * @package backend\widgets
 */
class Sidebar extends Widget
{
    public function run()
    {
        return $this->render('sidebar');
    }
}