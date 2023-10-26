<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\User $model */

$this->title = 'Create User';
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-create">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php if (Yii::$app->session->hasFlash('errorUser')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong></strong><?= Yii::$app->session->getFlash('errorUser'); ?>! </strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?= $this->render('_form', [
        'model' => $model, 'modelRole' => $modelRole
    ]) ?>

</div>
