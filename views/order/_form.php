<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Orders $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="orders-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_product')->textInput() ?>

    <?= $form->field($model, 'count')->textInput() ?>

    <?= $form->field($model, 'status')->dropDownList(
        ['waiting' => 'Waiting', 'on the way' => 'On the way', 'delivered' => 'Delivered', 'received' => 'Received'],
        ['options' => ['selected' => $model['status']]]
    ) ?>
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
