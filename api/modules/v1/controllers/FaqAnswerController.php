<?php

namespace epa\v1\controllers;

use api\components\ApiController;
use common\models\FaqAnswer;
use common\models\FaqAnswerSearch;

class FaqAnswerController extends ApiController
{
    public $modelClass = FaqAnswer::class;
    public $searchModelClass = FaqAnswerSearch::class;
}
