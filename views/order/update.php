<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Orders $model */

$this->title = 'Update Orders: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Orders', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="orders-update">

    <h1><?= Html::encode($this->title) ?> (<?= $model['product'] ?>)</h1>

    <?= $this->render('_form', [
        'model' => $model
    ]) ?>

</div>
