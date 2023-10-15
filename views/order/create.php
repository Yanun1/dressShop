<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\components\ProductWidget;


$this->registerJsFile('@web/js/xlsx.full.min.js', ['position'=>\yii\web\View::POS_END, 'depends' => [\yii\web\JqueryAsset::class, \yii\web\YiiAsset::class, \yii\bootstrap5\BootstrapAsset::class]]);
$this->registerJsFile('@web/js/main.js', ['position'=>\yii\web\View::POS_END, 'depends' => [\yii\web\JqueryAsset::class, \yii\web\YiiAsset::class, \yii\bootstrap5\BootstrapAsset::class]]);

$this->title = 'Create Orders';
$this->params['breadcrumbs'][] = ['label' => 'Orders', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
  ?>
<h1>Orders</h1>
<div class="newForm">
    <div class="to-excel-div">
        <button type="button" class="btn btn-success to-excel">Excel</button>
    </div>
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
                <?= $form->field($model,'id_product[]',)->label('Products')->input('text', ['placeholder' => "Select product", 'class' => 'productInput form-control', 'readOnly' => true])?>

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
                <div class="micro-image">
                    <img src="http://dress-shop/images/window_product_default.jpg" alt="photo">
                </div>
                <?= Html::button('+', ['class' =>'btn btn-success add-row']) ?>
                <?= Html::button('-', ['class' =>'btn btn-danger remove-row']) ?>
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

<?= ProductWidget::widget()  ;?>

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

    .micro-image {
        position: relative;
        display: flex;
        width: 38px;
        height: 38px;
        margin-bottom: 24px;
        border-radius: 0.375rem;
        overflow: hidden;
    }

    .productInput {
        cursor: pointer;
    }

    .micro-image img {
        width: 100%;
    }

    .to-excel-div {
        display: flex;
        justify-content: end;
    }

    .to-excel-div button {
        padding: 5px 25px;
    }
</style>