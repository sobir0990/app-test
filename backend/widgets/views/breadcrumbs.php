<?php
/**
 * Created by PhpStorm.
 * User: OKS
 * Date: 30.04.2019
 * Time: 15:56
 */
?>
<div>
    <div class="container-fluid p-l-25 p-r-25 sm-p-l-0 sm-p-r-0">
        <div class="inner" style="transform: translateY(0px); opacity: 1;">
            <?= \yii\widgets\Breadcrumbs::widget([
                'itemTemplate' => '<li class="breadcrumb-item"><a href="{url}">{link}</a></li>',
                'activeItemTemplate' => '<li class="breadcrumb-item active">{link}</li>',
                'tag' => 'ol',
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
        </div>
    </div>
</div>
