<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\OrdersSearch $model */
/** @var yii\widgets\ActiveForm $form */

\app\assets\RangeAsset::register($this);
?>

<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modal title</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="orders-search">

                    <?php $form = ActiveForm::begin([
                        'action' => ['index'],
                        'method' => 'get',
                    ]); ?>

                    <div class="all-inputs">
                        <div class="search-inputs">
                            <?= $form->field($model, 'product') ?>

                            <?= $form->field($model, 'count') ?>

                            <?= $form->field($model, 'status') ?>
                        </div>
                        <div class="range-inputs">
                            <div class="price-range">
                                <span>Price:</span>
                                <?= $form->field($model, 'minPrice')->label('min') ?>
                                <?= $form->field($model, 'maxPrice')->label('max') ?>
                            </div>
                            <div class="total-range">
                                <span>Total:</span>
                                <?= $form->field($model, 'minTotal')->input('number', ['min' => 0, 'max' => 100000, 'oninput' => 'validity.valid||(value="1");','id' => 'min_price', 'class' => 'form-control range-areas'])->label('min') ?>
                                <div id="slider-range" class="price-filter-range" name="rangeInput"></div>
                                <?= $form->field($model, 'maxTotal')->input('number', ['min' => 0, 'max' => 100000,'oninput' => 'validity.valid||(value="100000");', 'id' => 'max_price', 'class' => 'form-control range-areas'])->label('max') ?>
                            </div>
                        </div>
                        <div class="price-range-block">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="bottom-buttons">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <div class="left-buttons">
                        <?= Html::submitButton('Search', ['class' => 'btn btn-secondary']) ?>
                        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
                    </div>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>


<style>
    .search-inputs {
        display: flex;
        gap: 7px;
    }

    input[type="number"]::-webkit-inner-spin-button,
    input[type="number"]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }


    .bottom-buttons {
        display: flex;
        justify-content: space-between;
    }

    .modal-footer {
        display: block !important;
    }

    .price-range, .total-range {
        display: flex;
        gap: 7px;
        align-items: center;
    }

    .price-range span, .total-range span{
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        margin-right: 10px;
    }
</style>
