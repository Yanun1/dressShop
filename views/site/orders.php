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
            <?= $form->field($model,'price')->label('Price')->input('number', ['value' => 0])?>
            <?= $form->field($model,'count')->label('Count')->input('number', ['value' => 0])?>
            <?= Html::input('Sum','sumName', 0, ['class' => 'form-control field-ordersform-count', 'readOnly' => true])?>
            <?= Html::button('+', ['class' =>'btn btn-success']) ?>
            <?= Html::button('-', ['class' =>'btn btn-danger']) ?>
        </div>
        <?= Html::submitButton('Order', ['class' => "btn btn-secondary"])  ?>
    <?php ActiveForm::end() ?>
</div>

<style>
    .OrdersForm {
        display: flex;
        gap: 2px;
        align-items: end;
    }

    .OrdersForm > input {
        margin-bottom: 24px;
    }

    .form-group {
        margin-bottom: 0;
    }

    .OrdersForm > .btn {
        height: 38px;
        width: 50px;
        margin-bottom: 24px;
    }

    .help-block {
        height: 24px;
        color: red;
    }
</style>