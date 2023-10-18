<?php

namespace ejfrias\yii2_xdan_datetimepicker;

use Yii;
use yii\web\JqueryAsset;
use yii\web\AssetBundle;

class DateTimePickerAsset extends AssetBundle
{
    public $sourcePath = __DIR__;

    public $js = [
        'jquery.datetimepicker.full.min.js',
    ];

    public $css = [
        'jquery.datetimepicker.min.css',
    ];

    public function init()
    {
        parent::init();

        $this->depends[] = JqueryAsset::className();
    }


}
