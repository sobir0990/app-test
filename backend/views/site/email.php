<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\web\View;

$this->title = 'Login';
?>

<div class="login-wrapper ">
    <!-- START Login Background Pic Wrapper-->
    <div class="bg-pic">
        <img src="<?= $this->getImageUrl('img/login_bg.jpg')?>" data-src="<?= $this->getImageUrl('img/login_bg.jpg')?>" alt="" class="lazy">
        <!-- START Background Caption-->
        <div class="bg-caption pull-bottom sm-pull-bottom text-white p-l-20 m-b-20">
        </div>
        <!-- END Background Caption-->
    </div>
    <!-- END Login Background Pic Wrapper-->
    <!-- START Login Right Container-->
    <div class="login-container bg-white">
        <div class="p-l-50 m-l-20 p-r-50 m-r-20 p-t-50 m-t-30 sm-p-l-15 sm-p-r-15 sm-p-t-40">
            <p class="p-t-35"> </p>
            <!-- START Login Form -->
            <p>Password has been sent to your email</p>
            <?php $form = ActiveForm::begin([
                'id' => 'check-form_id',
                'fieldConfig' => [
                    'options' => [
                        'class' => 'w-icon form-group'
                    ],

                ],
            ]); ?>
            <?= $form->field($model, 'code', ['template' => '{input} {error} {hint}'])
                ->textInput([
                    'autofocus' => true,
                    'class' => 'input-lg form-control',
                    'placeholder' => 'Code'
                ])->label(false) ?>

            <div class="form-actions">
                <?= Html::submitButton('Send', ['class' => 'btn btn-primary btn-cons m-t-10', 'name' => 'login-button']) ?>

            </div> <!-- / .form-actions -->

            <?php ActiveForm::end(); ?>
            <!--END Login Form-->
            <div class="pull-bottom sm-pull-bottom">
                <div class="m-b-30 p-r-80 sm-m-t-20 sm-p-r-15 sm-p-b-20 clearfix">
                    <div class="col-sm-3 col-md-2 no-padding">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END Login Right Container-->
</div>

<script type="text/javascript">
    {
        $('#form-login').validate()
    })
</script>
