<h1>Orders</h1>
<?php
  use yii\widgets\ActiveForm;
  use yii\helpers\Html;
?>
<div class="newForm">
    <?php $form = ActiveForm::begin(['options' =>['class' =>'ordersMain']]) ?>
        <div class="OrdersForm">
            <?= $form->field($model,'products')->label('Products')?>
            <?= $form->field($model,'client')->label('Client')?>
            <?= $form->field($model,'price')->label('Price')?>
            <?= $form->field($model,'count')->label('Count')?>
            <?= $form->field($model,'sum')->label('Sum')?>
            <?= Html::button('+', ['class' =>'btn btn-success']) ?>
            <?= Html::button('-', ['class' =>'btn btn-danger']) ?>
        </div>
<!--        <?php //= //Html::submitButton('Order')  ?>-->
    <?php ActiveForm::end() ?>
</div>

<style>
    .OrdersForm {
        display: flex;
        gap: 2px;
        align-items: end;
    }

    .OrdersForm > .btn {
        height: 38px;
        width: 50px;
    }
</style>