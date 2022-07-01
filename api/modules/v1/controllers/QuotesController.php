<?php

namespace epa\v1\controllers;

use common\models\Quotes;
use common\models\QuotesSearch;

class QuotesController extends \api\components\ApiController
{
    public $modelClass = Quotes::class;
    public $searchModelClass = QuotesSearch::class;

}
