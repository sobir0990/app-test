<?php

namespace epa\v1\controllers;

use api\components\ApiController;
use common\models\Review;
use common\models\ReviewSearch;

class ReviewController extends ApiController
{
    public $modelClass = Review::class;
    public $searchModelClass = ReviewSearch::class;
}
