<h1>Orders</h1>
<?php
  use yii\widgets\ActiveForm;
  use yii\helpers\Html;
?>
<div class="newForm">
<form  class="OrdersForm">
<?php $form = ActiveForm::begin(['options' =>['id' =>'OrdersForm']]) ?>
<?= $form->field($model,'products')->label('Products')?>
<?= $form->field($model,'client')->label('Client')?>
<?= $form->field($model,'price')->label('Price')?>
<?= $form->field($model,'count')->label('Count')?>
<?= $form->field($model,'sum')->label('Sum')?>
<?= Html::button('+', ['class' =>'btn btn-success']) ?>
<?= Html::button('-', ['class' =>'btn btn-danger']) ?>
 <?php ActiveForm::end() ?>
</form>
</div>

