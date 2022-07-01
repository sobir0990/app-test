<?php

use common\models\User;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */

$role = [
    User::ROLE_ADMIN => 'Admin',
    User::ROLE_USER => 'User',
];

?>

<div class="content">
    <div class="container-fluid   container-fixed-lg">
        <h1><?= Html::encode($this->title) ?></h1>
        <?php $form = ActiveForm::begin(); ?>
        <div class="row">
            <div class="col-lg-7">
                <div class="card card-default">
                    <div class="card-body">

                        <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

                        <?= $form->field($model, 'password_hash')->passwordInput(['value' => false]) ?>

                        <?= $form->field($model, 'email')->textInput() ?>

                        <?= $form->field($model, 'role')->widget(Select2::classname(), [
                            'data' => $role,
                            'options' => [
                                'placeholder' => 'Rolni tanlang',
                                'value' => $model->role
                            ],
                            'pluginOptions' => [
                                'allowClear' => true,
                            ],
                        ])->label('Rol');
                        ?>

                        <div class="form-group">
                            <!--                            <h6>Status</h6>-->
                            <!--                            --><? //= $form->field($model, 'status')->checkbox(['data-init-plugin' => 'switchery', 'label' => false, 'data-size' => 'small', 'value' => 10]) ?>

                            <div class="form-group">
                                <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>