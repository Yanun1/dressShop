<?php

namespace app\assets;

use yii\web\AssetBundle;

class RangeAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/price_range_style.css',
        'css/jquery-ui.css',
    ];
    public $js = [
        'js/price_range_script.js',
        'js/jquery-ui.min.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap5\BootstrapAsset',
        '\rmrevin\yii\fontawesome\AssetBundle'
    ];
}
