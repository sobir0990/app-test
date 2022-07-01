<?php

namespace epa\v1\controllers;

use api\components\ApiController;
use common\models\DocumentGroup;
use common\models\DocumentGroupSearch;

class DocumentGroupController extends ApiController
{
    public $modelClass = DocumentGroup::class;
    public $searchModelClass = DocumentGroupSearch::class;
}
