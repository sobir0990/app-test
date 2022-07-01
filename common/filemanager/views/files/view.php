<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model jakharbek\filemanager\models\Files */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Files', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="files-view">



    <p>
        <?= Html::a('Update', ['update', 'id' => $model->file_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->file_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'file_id',
            'title',
            'description:ntext',
            'type',
            'file:ntext',
            'date_create',
            'user_id',
        ],
    ]) ?>

</div>
