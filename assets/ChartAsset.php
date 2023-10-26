<?php

namespace app\assets;

use yii\web\AssetBundle;

class ChartAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
        'css/chart.css'
    ];
    public $js = [
        "js/canvasjs.min.js",
        "js/d3.v7.min.js",
        "js/plotly-latest.min.js",
        "js/d3.v6.min.js",
        "js/d3.min.js"
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap5\BootstrapAsset',
        '\rmrevin\yii\fontawesome\AssetBundle'
    ];
}