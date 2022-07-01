<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\City */

$this->title = 'Создать пользователь';
$this->params['breadcrumbs'][] = ['label' => 'пользователь', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="city-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
