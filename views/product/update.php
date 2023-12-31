<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Products $model */

$this->title = 'Update Products: ' . $model->product.' ('.$model->id.')';
$this->params['breadcrumbs'][] = ['label' => 'Products', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="products-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_updateForm', [
        'model' => $model,
        'modelImages' => $modelImages,
        'modelProductsImage' => $modelProductsImage
    ]) ?>

</div>
