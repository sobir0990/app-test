<?php

namespace epa\v1\controllers;

use api\components\ApiController;
use common\models\GalleryItem;
use common\models\GalleryItemSearch;

class GalleryItemController extends ApiController
{
    public $modelClass = GalleryItem::class;
    public $searchModelClass = GalleryItemSearch::class;
}
