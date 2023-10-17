<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\components\rangeWidget;

/** @var yii\web\View $this */
/** @var app\models\OrdersSearch $model */
/** @var yii\widgets\ActiveForm $form */

\app\assets\RangeAsset::register($this);
?>

<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Search settings</h5>
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
                            <?=  rangeWidget::widget(['title' => 'Price', 'model' => $model, 'form' => $form, 'inputs' => ['minPrice', 'maxPrice']]) ?>
                            <?=  rangeWidget::widget(['title' => 'Total', 'model' => $model, 'form' => $form, 'inputs' => ['minTotal', 'maxTotal']]) ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="bottom-buttons">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <div class="left-buttons">
                        <?= Html::submitButton('Search', ['class' => 'btn btn-secondary']) ?>
                        <?= Html::submitButton('Reset', ['class' => 'btn btn-outline-secondary reset-button']) ?>
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
</style>
