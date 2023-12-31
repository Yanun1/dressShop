<?php

namespace app\assets;
use yii\web\AssetBundle;
class ProductWidgetAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/owl.carousel.css',
        'css/owl.carousel.min.css',
        'css/productWidget.css',
    ];
    public $js = [
        'js/owl.carousel.min.js',
        'js/jquery.accordion.js',
        'js/jquery.cookie.js',
        'js/productWidget.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap5\BootstrapAsset'
    ];
}