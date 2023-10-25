<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use app\components\ProductWidget;

/** @var yii\web\View $this */
/** @var app\models\Products $model */
/** @var yii\widgets\ActiveForm $form */
$this->registerJsFile('@web/js/product-update.js', ['position'=>\yii\web\View::POS_END, 'depends' => [\yii\web\JqueryAsset::class, \yii\web\YiiAsset::class, \yii\bootstrap5\BootstrapAsset::class]]);
?>

<div class="products-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' =>"multipart/form-data"]]); ?>

    <?= $form->field($model, 'product')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'price')->textInput() ?>

    <?= $form->field($model, 'count')->textInput() ?>

    <?= $form->field($modelProductsImage, 'image')->fileInput(['accept' => 'image/*'])->label('Main photo') ?>
    <?= Html::label('Current photo - '.$model->image, 'oldImage', ['class' => 'current-photo']) ?>

    <?= $form->field($modelProductsImage, 'images[]')->fileInput(['multiple' => true, 'accept' => 'image/*'])->label('Additional images (optional)') ?>
    <span class="images-error">You can have at most 5 files.</span>

    <?php
    $images = '';
        if(!empty($modelImages)) {
            foreach ($modelImages as $item) {
                $images .=
                    "<div class='images-span'>"
                        .$form->field($modelProductsImage,'remaining[]')->input('text', ['class' => 'images-input', 'value' => $item['image'], 'readonly' => 'readonly'])
                        ."<div style='display: flex; gap:5px'><img src='/images/$item[image]' class='mini-images' alt='P'> <div class='btn btn-danger remove-image'>-</div></div>"
                    ."</div>";
            }
        }
        else
            $images = 'None';
    ?>

    <?= Html::label('Current photos: '.$images, 'oldImage', ['class' => 'current-photo']) ?>

    <?= $form->field($model, 'product')->label('Product Category')->input('text', ['placeholder' => "Select category", 'class' => 'productInput form-control', 'readOnly' => true])?>

    <?= Html::checkbox('category', false, ['label' => 'No category', 'class' => 'checkbox']); ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success push-update']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?= ProductWidget::widget()  ;?>


<style>

    .mini-images {
        border-radius: 5px;
        height: 30px;
    }

    .no-valid {
        border-color: var(--bs-form-invalid-border-color) !important;
        padding-right: calc(1.5em + 0.75rem) !important;
        background-image: none !important;
        background-repeat: no-repeat !important;
        background-position: right calc(0.375em + 0.1875rem) center !important;
        background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem) !important;
    }

    .images-error {
        display: none;
        width: 100%;
        margin-top: 0.25rem;
        font-size: 0.875em;
        color: var(--bs-form-invalid-color);
        margin-bottom: 1rem;
    }

    .no-margin {
        margin-bottom: 0 !important;
    }

    .field-imagesform-remaining {
        width: 100%;
        margin-bottom: 0 !important;
    }

    .field-imagesform-remaining > label {
        display: none;
    }

    .custom-file-label {
        display: block;
    }

    .images-span {
        display: flex;
        justify-content: space-between ;
        align-items: center;
        font-size: 15px;
        font-weight: 600;
    }

    .images-input {
        outline: none;
        border: none;
        width: 100% !important;
    }

    .images-span div {
        padding: 2px 10px;
    }

    .current-photo {
        font-weight: bold;
        margin-bottom: 15px;
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .products-form {
        max-width: 400px;
    }

    .checkbox {
        margin-bottom: 30px;
    }

    .field-imagesform-image {
        margin-bottom: 0 !important;
    }

    .current-photo  pre {
        margin-bottom: 0;
    }

</style>