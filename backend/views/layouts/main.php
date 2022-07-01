<?php


use backend\assets\AppAsset;
use yii\helpers\Html;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="fixed-header dashboard menu-pin">
<?php $this->beginBody() ?>

<?= \backend\widgets\Sidebar::widget() ?>

<div class="page-container">
    <div class="page-content-wrapper">
        <?= \backend\widgets\TopBar::widget() ?>

        <?= $content ?>

        <?=\backend\widgets\Footer::widget()?>
    </div>
</div>
<?php
$flash = Yii::$app->session->getAllFlashes();
if (!empty($flash)) :
    foreach ($flash as $type => $message) :
        $js = <<<JS
        Swal.fire({
          type: "{$type}",
          title: "{$message}",
        })
JS;
        $this->registerJs($js, \yii\web\View::POS_LOAD);
    endforeach;
endif;
?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
