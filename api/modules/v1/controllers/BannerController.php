<?php

namespace epa\v1\controllers;

use common\models\Banner;
use common\models\BannerSearch;

class BannerController extends \api\components\ApiController
{
    public $modelClass = Banner::class;
    public $searchModelClass = BannerSearch::class;

}
