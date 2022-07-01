<?php
/**
 * @var $user \common\models\User
 * Created by PhpStorm.
 * User: OKS
 * Date: 26.04.2019
 * Time: 23:38
 */

use common\models\User;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$this->title = 'Update User';
$this->params['breadcrumbs'][] = $this->title;
$model = new \backend\forms\UpdateUserForm(['id' => $user->id]);
?>
<div class="content">

    <div class="container-fluid   container-fixed-lg">
        <div class="row">
            <h3 class="col-lg-12"><?= $this->title ?></h3>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card card-default">
                    <div class="card-body">
                        <?php $form = ActiveForm::begin([
                            'fieldConfig' => [
                                'options' => [
                                    'tag' => false,
                                ],
                            ],
                        ]); ?>

                        <?= $form->field($model, 'username')->textInput(['class' => 'form-control', 'placeholder' => 'Enter your username', 'value' => $user->username, 'required' => 'required'])->label(false) ?>

                        <?= $form->field($model, 'email')->textInput(['class' => 'form-control', 'placeholder' => 'Enter your email', 'value' => $user->email])->label(false) ?>

                        <?= $form->field($model, 'password_hash')->passwordInput(['class' => 'form-control', 'placeholder' => __('Enter your new password')])->label(false) ?>

                        <div class="form-group">
                            <?= Html::submitButton( 'Save', ['class' => 'btn btn-lg btn-rounded btn-primary m-r-20 m-b-10']) ?>
                        </div>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>

