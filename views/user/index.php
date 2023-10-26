<?php

use app\models\User;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use app\components\SearchWidget;

/** @var yii\web\View $this */
/** @var app\models\UserSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>


    <p class="create-buttons">
        <?= Html::a('Create Account', ['create'], ['class' => 'btn btn-success']) ?></p>
    <div class="create-order filet-button">
        <button type="button" class="btn btn-secondary search-button" data-bs-toggle="modal" data-bs-target="#staticBackdrop">Search<img src="http://dress-shop/images/filter_icon.png" alt="Y"></button>
    </div>

    <?php if (Yii::$app->session->hasFlash('successAdd')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong><?= Yii::$app->session->getFlash('successAdd'); ?>! </strong>
            You can check add Account in current section
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (Yii::$app->session->hasFlash('ErrorDelete')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong><?= Yii::$app->session->getFlash('ErrorDelete'); ?>! </strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            'login',
            'date',
            [
                'label' => 'Type',
                'value' => function ($model) {
                    return $model['assignment'][0]['item_name'];
                },
                'attribute' => 'item_name',
            ],
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, User $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 },
                'visibleButtons' => [
                    'view' => false,
                ],
            ],
        ],
    ]); ?>
</div>
<?= SearchWidget::widget([
    'model' => $searchModel,
    'fields' => ['id', 'login', 'item_name'],
    'calendars' => [
        [
            'inputs' => ['minDate', 'maxDate']
        ]
    ]
]) ?>

<style>
    .filters {
        display: none;
    }

    .create-buttons {
        display: flex;
        justify-content: end;
        gap: 5px;
    }

    thead a {
        color: black;
    }

    .filters {
        display: none;
    }

    .filet-button img {
        height: 18px;
        margin-left: 10px;
    }

    .create-order {
        margin: 12px 0;
        display: flex;
        justify-content: end;
    }
</style>