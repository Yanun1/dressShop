<?php
use app\assets\ProductWidgetAsset;
use app\models\Products;

ProductWidgetAsset::register($this);

$arrayProduct = json_encode(Products::find()->with('user')->asArray()->all());

?>

<div class="select-product-widget">
    <div class="black-background"></div>
    <div class="select-window">
        <div class="window-content">

        </div>
    </div>
</div>