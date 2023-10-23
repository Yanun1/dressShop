<?php

use app\assets\RangeAsset;

RangeAsset::register($this);

?>

<div class="total-range">
    <span><?= $title ?></span>
    <?= $form->field($model, $inputs[0])->input('number', ['min' => 0, 'max' => 100000, 'oninput' => 'validity.valid||(value="1");', 'class' => 'form-control range-areas min_price'])->label('min') ?>
    <div class="price-filter-range slider-range" name="rangeInput[]"></div>
    <?= $form->field($model, $inputs[1])->input('number', ['min' => 0, 'max' => 100000,'oninput' => 'validity.valid||(value="100000");', 'class' => 'form-control range-areas max_price'])->label('max') ?>
</div>
