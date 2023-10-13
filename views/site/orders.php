<?php
  use yii\widgets\ActiveForm;
  use yii\helpers\Html;
  use app\components\ProductWidget;

  $this->registerJsFile('@web/js/main.js', ['position'=>\yii\web\View::POS_END, 'depends' => [\yii\web\JqueryAsset::class, \yii\web\YiiAsset::class, \yii\bootstrap5\BootstrapAsset::class]]);
?>
<h1>Orders</h1>
<div class="newForm">
    <?php $form = ActiveForm::begin(['options' =>['class' =>'ordersMain', 'enctype' => 'multipart/form-data']]) ?>
    <?php if (Yii::$app->session->hasFlash('successOrder')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong><?= Yii::$app->session->getFlash('successOrder'); ?>! </strong>
            You can check your orders in "My Order" section
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php endif; ?>

    <?php if (Yii::$app->session->hasFlash('errorOrder')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong></strong><?= Yii::$app->session->getFlash('errorOrder'); ?>! </strong>
            all orders after this line were not ordered
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php endif; ?>
        <div class="ordersMain_container">
            <div class="OrdersForm">
                <?= $form->field($model,'id_product[]')->label('Products')->dropDownList([], ['prompt' => 'Not Selected'])?>

                <div class="form-group field-orders-id_product noneInput">
                    <?= Html::label('Saler', 'SalerLabel') ?>
                    <?= Html::input('text','saler', 'None', ['class' => 'form-control field-ordersform-count', 'readOnly' => true])?>
                </div>
                <div class="form-group field-orders-id_product noneInput">
                    <?= Html::label('Price', 'priceLabel') ?>
                    <?= Html::input('Number','price', '', ['class' => 'form-control field-ordersform-count', 'readOnly' => true])?>
                </div>
                <?= $form->field($model,'count[]')->label('Count')->input('number', ['value' => 1, 'min' => 1])?>
                <div class="form-group field-orders-id_product noneInput">
                    <?= Html::label('Total', 'costLabel') ?>
                    <?= Html::input('Number','sumName', 0, ['class' => 'form-control field-ordersform-count costInput', 'readOnly' => true])?>
                </div>
<!--                --><?php //= //Html::input('file', 'fileName', 'input file') ?>
                <?= Html::button('+', ['class' =>'btn btn-success']) ?>
                <?= Html::button('-', ['class' =>'btn btn-danger']) ?>
            </div>
        </div>
        <div class="buyCost">
            <?= Html::submitButton('Order', ['class' => "btn btn-secondary"])  ?>
            <div class="form-group field-orders-id_product noneInput">
                <?= Html::label('Total cost', 'TotalCostLabel') ?>
                <?= Html::input('Number','totalCost', 0, ['class' => 'form-control field-ordersform-count', 'readOnly' => true])?>
            </div>
        </div>
    <?php ActiveForm::end() ?>
</div>

<?= ProductWidget::widget() ?>

<style>
    .newForm {
        margin-top: 50px;
    }

    .OrdersForm {
        display: flex;
        gap: 5px;
        align-items: end;
        margin: 0 auto;
        margin-bottom: 20px;
    }

    .noneInput > input {
        margin-bottom: 24px;
    }

    .form-group {
        margin-bottom: 0;
    }

    .OrdersForm > .btn {
        height: 38px;
        width: 38px;
        margin-bottom: 24px;
    }

    .help-block {
        height: 24px;
        color: red;
    }

    input[type='Number'] {
        max-width: 110px;
    }

    .form-control {
        max-width: 120px;
    }

    .buyCost {
        display: flex;
        gap: 20px;
        align-items: center;
    }
</style>