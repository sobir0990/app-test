<?php
use yii\helpers\Html;
use yii\helpers\Url;

?>
<div class="header ">
    <!-- START MOBILE SIDEBAR TOGGLE -->
    <a href="#" class="btn-link toggle-sidebar d-lg-none pg pg-menu" data-toggle="sidebar">
    </a>
    <!-- END MOBILE SIDEBAR TOGGLE -->
    <div class="">
        <div class="brand inline">
        </div>
        <?= \backend\widgets\Breadcrumbs::widget() ?>

    </div>
    <div class="d-flex align-items-center">
        <div class="pull-left p-r-10 fs-14 font-heading d-lg-inline-block d-none m-l-20">
            <span class="semi-bold"><?=$user->username?></span>
        </div>
        <div class="dropdown pull-right d-lg-inline-block d-none">
            <button class="profile-dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <span class="thumbnail-wrapper d32 circular inline">
              <img src="<?=$this->getImageUrl('img/profiles/avatar.jpg')?>" alt="" data-src="<?=$this->getImageUrl('img/profiles/avatar.jpg')?>" data-src-retina="<?=$this->getImageUrl('img/profiles/avatar_small2x.jpg')?>" width="32" height="32">
              </span>
            </button>
            <div class="dropdown-menu dropdown-menu-right profile-dropdown" role="menu">
                <a href="<?= Url::to('/user/update/') . Yii::$app->user->id ?>" class="dropdown-item"><i class="pg-settings_small"></i> Settings</a>
                <?= Html::a('<span class="pull-left">Logout</span>
                    <span class="pull-right"><i class="pg-power"></i></span>', '/site/logout', [
                        'data' => [
                            'method' => 'post'
                        ],
                        'class' => 'clearfix bg-master-lighter dropdown-item'
                    ]
                ) ?>
            </div>
        </div>
        <!-- END User Info-->

    </div>
</div>