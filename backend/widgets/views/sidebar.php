<?php

use yii\helpers\Html;
use yii\helpers\Url;

$menus = [
    'user' => [
        'label' => 'User',
        'icon' => 'folder',
        'url' => '/user/index',
    ],
];
?>
<nav class="page-sidebar" data-pages="sidebar">
    <div class="sidebar-header">
<!--        <img src="" alt="logo" class="brand"-->
<!--             data-src=""-->
<!--             data-src-retina="" width="180" height="50">-->
    </div>
    <div class="sidebar-menu">
        <ul class="menu-items">
            <li class="m-t-30 ">
                <a href="<?= Url::home() ?>" class="detailed">
                    <span class="title">Dashboard</span>
                </a>
                <span class="icon-thumbnail"><i data-feather="shield"></i></span>
            </li>
            <?php foreach ($menus as $k => $menu): ?>
                <li>
                    <a href="<?= isset($menu['items']) && count($menu['items']) ? 'javascript:;' : Url::to($menu['url']) ?>" <?= isset($menu['items']) && count($menu['items']) ? ' class="detailed"' : '' ?>>
                        <span class="title"><?= $menu['label'] ?></span>
                        <?php if (isset($menu['items']) && count($menu['items'])): ?>
                            <span class=" arrow"></span>
                        <?php endif; ?>
                    </a>
                    <span class="icon-thumbnail"><i data-feather="<?= $menu['icon'] ?>"></i></span>
                    <?php if (isset($menu['items']) && count($menu['items'])): ?>
                        <ul class="sub-menu">
                            <?php foreach ($menu['items'] as $item): ?>
                                <li class="">
                                    <a href="<?= Url::to($item['url']) ?>"><?= $item['label'] ?></a>
                                    <span class="icon-thumbnail"></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
        <div class="clearfix"></div>
    </div>
    <!-- END SIDEBAR MENU -->
</nav>
