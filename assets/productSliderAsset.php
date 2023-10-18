<?php

namespace app\assets;
use yii\web\AssetBundle;
class ProductSliderAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/productSlider.css',
        'css/owl.carousel.css',
        'css/owl.carousel.min.css',
    ];
    public $js = [
        'js/owl.carousel.min.js',
        'js/productSlider.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap5\BootstrapAsset'
    ];
}