<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Products $model */

$this->title = 'Create Products';
$this->params['breadcrumbs'][] = ['label' => 'Products', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="products-create">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php if (Yii::$app->session->hasFlash('errorOrder')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong></strong><?= Yii::$app->session->getFlash('errorOrder'); ?>! </strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
