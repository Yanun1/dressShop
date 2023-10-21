<?php

use app\models\Products;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use app\components\SearchWidget;
use app\components\productSliderWidget;

/** @var yii\web\View $this */
/** @var app\models\ProductsSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Products';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="products-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php if (Yii::$app->session->hasFlash('successAdd')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong><?= Yii::$app->session->getFlash('successAdd'); ?>! </strong>
            You can check your new Product in current section
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if(Yii::$app->user->can('Add product')): ?>
        <div class="create-product">
            <?= Html::a('Create Products', ['create'], ['class' => 'btn btn-success']) ?>
        </div>
    <?php endif; ?>
    <div class="create-product filet-button">
        <button type="button" class="btn btn-secondary search-button" data-bs-toggle="modal" data-bs-target="#staticBackdrop">Search<img src="http://dress-shop/images/filter_icon.png" alt="Y"></button>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            [
                'label' => 'Product',
                'attribute' => 'product',
                'contentOptions' => ['class' => 'product-column'],
            ],
            'price',
            'count',
            [
                'label' => 'Image',
                'format' => 'raw',
                'value' => function ($model) {
                    return  Html::img('http://dress-shop/images/'.$model['image'], ['class' => 'mini-photo', 'alt' => 'photo']);
                },
                'contentOptions' => ['class' => 'image-column'],
                'headerOptions' => ['class' => 'image-header']
            ],
            [
                'label' => 'Employee',
                'value' => function ($model) {
                    return $model['user']['login'];
                },
                'attribute' => 'users.id'
            ],
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Products $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 },
                 'visibleButtons' => [
                    'view' => false,
                    'update' => Yii::$app->user->can('Update elements'),
                    'delete' => Yii::$app->user->can('Update elements'),
                ],
            ],
        ],
    ]); ?>

</div>

<?= SearchWidget::widget([
    'model' => $searchModel,
    'fields' => ['id', 'product'],
    'ranges' => [
        [
            'title' => 'Price',
            'names' => ['minPrice', 'maxPrice']
        ],
        [
            'title' => 'Count',
            'names' => ['minCount', 'maxCount']
        ],
    ]
]) ?>

<?= productSliderWidget::widget() ?>

<style>
    .mini-photo {
        width: 100%;
        position: absolute;
    }

    .image-column {
        display: flex;
        justify-content: center;
        height: 100%;
        min-height: 41px;
        align-items: center;
        position: relative;
        padding: 0 !important;
        overflow: hidden;
        cursor: pointer;
    }

    .image-header {
        width: 25px;
    }

    .filters {
        display: none;
    }

    .filet-button img {
        height: 18px;
        margin-left: 10px;
    }

    .create-product {
        margin: 12px 0;
        display: flex;
        justify-content: end;
    }

    thead a {
        color: black;
    }
</style>

