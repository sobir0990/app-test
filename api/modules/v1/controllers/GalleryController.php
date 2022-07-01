<?php

namespace epa\v1\controllers;

use api\components\ApiController;
use common\models\Gallery;
use common\models\GallerySearch;

class GalleryController extends ApiController
{
    public $modelClass = Gallery::class;
    public $searchModelClass = GallerySearch::class;
}
