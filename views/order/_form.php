<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use app\components\ProductWidget;

/** @var yii\web\View $this */
/** @var app\models\Orders $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="orders-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($orderProduct, 'id_product')->label('Products')->input('text', ['placeholder' => "Select product", 'class' => 'productInput form-control', 'readOnly' => true]) ?>

    <?= $form->field($orderProduct, 'count')->textInput() ?>

    <?= $form->field($model, 'status')->dropDownList(
        ['waiting' => 'Waiting', 'on the way' => 'On the way', 'delivered' => 'Delivered', 'received' => 'Received'],
        ['options' => ['selected' => $model['status']]]
    ) ?>
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <?= ProductWidget::widget()  ;?>
</div>

<style>
    .orders-form {
        max-width: 400px;
    }
</style>