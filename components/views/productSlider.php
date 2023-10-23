<?php

use app\assets\ProductSliderAsset;

ProductSliderAsset::register($this);

?>

<div class="select-product-widget">
        <div class="black-background"></div>
        <div class="close-block">
                <img src="http://dress-shop/images/close-button.png" alt="X">
            </div>
        <div class="select-window">
                <div class="window-content owl-carousel">
                    </div>
            </div>
        <div class="slider-buttons">
            <img src="/images/left_button.png" alt="<" class="left-button">
            <img src="/images/left_button.png" alt=">" class="right-button">
        </div>
    </div>
