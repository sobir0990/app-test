<?php

use oks\langs\widgets\LangsWidgets;
use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="content">
    <div class="container-fluid container-fixed-lg bg-white">
        <div class="card card-transparent">
            <div class="card-body">
                <div class="table-responsive">
                    <div class="dataTables_wrapper no-footer" id="basicTable_wrapper">
                        <p>
                            <?= Html::a('Create User', ['create'], ['class' => 'btn btn-success']) ?>
                        </p>
                        <div>
                            <?= GridView::widget([
                                'dataProvider' => $dataProvider,
                                'tableOptions' => [
                                    'class' => 'table table-hover dataTable no-footer',
                                    'id' => 'basicTable'
                                ],
                                'options' => [
                                    'tag' => false
                                ],
                                'columns' => [
                                    ['class' => 'yii\grid\SerialColumn'],

                                    'id',
                                    'username',
                                    'email',
                                    [
                                        'class' => 'yii\grid\ActionColumn',
                                        'template' => '{update} {delete}',
                                        'buttons' => [
                                            'update' => function ($model) {
                                                return '<a href="' . $model . '"> <div class="btn btn-success">' .
                                                    '<i class="fa fa-pencil" style="color: #ffffff;"></i>' .
                                                    '</div></a>';
                                            },
                                            'delete' => function ($model) {
                                                return '<a href="'.$model.'" data-method="post"> <div class="btn btn-success">' .
                                                    '<i class="fa fa-trash-o" style="color: #ffffff;"></i>' .
                                                    '</div></a>';
                                            },
                                        ],
                                    ],
                                ],
                            ]); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
