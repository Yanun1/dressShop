<?php

use app\models\Orders;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\bootstrap5\Alert;
use app\components\SearchWidget;
use app\components\productSliderWidget;

if(Yii::$app->user->isGuest) {
    Yii::$app->response->redirect(['/site/login']);
    return;
}

$access = false;

//$role = Yii::$app->authManager->getRolesByUser(Yii::$app->user->id);
//foreach ($role as $rol) {
//    if($rol->name == 'admin'|| $rol->name == 'employee')
//        $access = true;
//}

/** @var yii\web\View $this */
/** @var app\models\OrdersSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->registerJsFile('@web/js/order-index.js', ['position'=>\yii\web\View::POS_END, 'depends' => [\yii\web\JqueryAsset::class, \yii\web\YiiAsset::class, \yii\bootstrap5\BootstrapAsset::class]]);

$this->title = 'Orders';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="orders-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="create-order">
        <?= Html::a('Create Orders', ['create'], ['class' => 'btn btn-success']) ?>
    </div>
    <div class="create-order filet-button">
        <button type="button" class="btn btn-secondary search-button" data-bs-toggle="modal" data-bs-target="#staticBackdrop">Search<img src="http://dress-shop/images/filter_icon.png" alt="Y"></button>
    </div>

    <?php

    if (Yii::$app->session->hasFlash('failed')) {
            echo Alert::widget([
                    'options' => [
                    'class' => 'alert-danger',
                    ],
                    'body' => Yii::$app->session->getFlash('failed'),
                ]);
            }

    if (Yii::$app->session->hasFlash('success deleting')) {
            echo Alert::widget([
                    'options' => [
                    'class' => 'alert-success',
                    ],
                    'body' => Yii::$app->session->getFlash('success deleting'),
                ]);
            }
    ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'label' => 'ID Check',
                'value' => function ($model) {
                    return $model['orderCheck']['id_order'];
                },
                'attribute' => 'orderCheck.id_order',
            ],
            [
                'label' => 'Products',
                'value' => function ($model) {
                    return $model['product']['product'];
                },
                'attribute' => 'products.product',
                'contentOptions' => ['class' => 'product-column'],
            ],
            [
                'label' => 'Price',
                'value' => function ($model) {
                    return $model['price'];
                },
                'attribute' => 'products.price'
            ],
            'count',
            [
                'label' => 'Status',
                'value' => function ($model) {
                    return $model['status'];
                },
                'contentOptions' => ['class' => 'status-column'],
                'attribute' => 'status'
            ],
            [
                'label' => 'Image',
                'format' => 'raw',
                'value' => function ($model) {
                    return  Html::img('http://dress-shop/images/'.$model['product']['image'], ['class' => 'mini-photo', 'alt' => 'photo']);
                },
                'contentOptions' => ['class' => 'image-column'],
                'headerOptions' => ['class' => 'image-header']
            ],
            [
                'label' => 'Total',
                'value' => function ($model) {
                    return $model['product']['price']*$model['count'];
                },
                'attribute' => '`products`.`price` * `Orders`.`count`'
            ],
            [
                'label' => 'Date',
                'value' => function ($model) {
                    $temp = new DateTime($model['data']);
                    return $temp->format('Y-m-d');
                },
                'attribute' => 'DATE(`data`)'
            ],
            [
                'class' => ActionColumn::class,
                'urlCreator' => function ($action, Orders $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 },
                'visibleButtons' => [
                    'view' => false,
                    'update' => Yii::$app->user->can('Update elements'),
                ],
            ],
        ],
    ]); ?>
</div>

<?= SearchWidget::widget([
        'model' => $searchModel,
        'fields' => ['product', 'count', 'id_check'],
        'lists' => [
                [
                        'input' => 'status',
                        'options' => ['waiting' => 'Waiting', 'on the way' => 'On the way', 'delivered' => 'Delivered', 'received' => 'Received']
                ]
        ],
        'ranges' => [
                [
                    'title' => 'Price',
                    'names' => ['minPrice', 'maxPrice']
                ],
                [
                    'title' => 'Total',
                    'names' => ['minTotal', 'maxTotal']
                ]
        ]
]) ?>


<?= productSliderWidget::widget() ?>

<style>
    thead a {
        color: black;
    }

    .create-order {
        margin: 12px 0;
        display: flex;
        justify-content: end;
    }

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

</style>