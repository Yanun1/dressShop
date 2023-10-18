<?php

namespace app\components;

use yii\base\Widget;

class productSliderWidget extends Widget
{
    public function init()
    {
        parent::init();
    }

    public function run()
    {
        return $this->render('productSlider');
    }
}