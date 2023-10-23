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

    <?= $form->field($model, 'image')->fileInput(['accept' => 'image/*'])->label('Main photo') ?>

    <?= $form->field($modelImages, 'image[]')->fileInput(['multiple' => true, 'accept' => 'image/*'])->label('Additional images (optional)') ?>

    <?= $form->field($model, 'id_product')->label('Product Category')->input('text', ['placeholder' => "Select category", 'class' => 'productInput form-control', 'readOnly' => true])?>

    <?= Html::checkbox('category', false, ['label' => 'No category', 'class' => 'checkbox']); ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?= ProductWidget::widget()  ;?>


<style>
    .products-form {
        max-width: 400px;
    }

    .checkbox {
        margin-bottom: 30px;
    }

</style>