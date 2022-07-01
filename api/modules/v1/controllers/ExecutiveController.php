<?php

namespace epa\v1\controllers;

use api\components\ApiController;
use common\models\Executive;
use common\models\ExecutiveSearch;

class ExecutiveController extends ApiController
{
    public $modelClass = Executive::class;
    public $searchModelClass = ExecutiveSearch::class;
}
