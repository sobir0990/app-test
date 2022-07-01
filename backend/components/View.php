<?php

namespace backend\components;

class View extends \yii\web\View
{
    /**
     * @param $name
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function getImageUrl($name)
    {
        return $this->getAssetManager()->getBundle('\backend\assets\AppAsset')->baseUrl . '/' . $name;
    }
}