<?php

namespace app\assets;
use yii\web\AssetBundle;
class ProductWidgetAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/productWidget.css',
    ];
    public $js = [
        'js/productWidget.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap5\BootstrapAsset'
    ];
}