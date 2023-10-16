<?php

use app\models\Orders;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\bootstrap5\Alert;


$access = false;
$role = Yii::$app->authManager->getRolesByUser(Yii::$app->user->id);
foreach ($role as $rol) {
    if($rol->name == 'admin'|| $rol->name == 'employee')
        $access = true;
}

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

    <?php echo $this->render('_search', ['model' => $searchModel]); ?>
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
            ['class' => 'yii\grid\SerialColumn'],
                [
                    'label' => 'Products',
                    'value' => function ($model) {
                        return $model['product']['product'];
                    },
                    'attribute' => 'products.product'
                ],
            [
                'label' => 'Price',
                'value' => function ($model) {
                    return $model['product']['price'];
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
                'class' => ActionColumn::class,
                'urlCreator' => function ($action, Orders $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 },
                'visibleButtons' => [
                    'view' => false,
                    'update' => $access
                ],
            ],
        ],
    ]); ?>
</div>

<div class="select-product-widget">
    <div class="black-background"></div>
    <div class="close-block">
        <img src="http://dress-shop/images/close-button.png" alt="X">
    </div>
    <div class="select-window">
        <div class="window-content">
            <img src="" alt="photo">
        </div>
    </div>
</div>


<style>

    thead a {
        color: black;
    }

    .close-block {
        position: absolute;
        right: 30px;
        top: 30px;
        z-index: 1070;
    }

    .close-block img {
        cursor: pointer;
        width: 40px;
    }

    .select-product-widget {
        position: fixed;
        z-index: 1040;
        left: 0;
        right: 0;
        top: 0;
        bottom: 0;
        display: none;
        align-items: center;
        justify-content: center;
    }

    .black-background {
        position: absolute;
        z-index: 1050;
        left: 0;
        right: 0;
        top: 0;
        bottom: 0;
        background-color: rgba(0,0,0,.6);
    }

    .select-window {
        border-radius: 7px;
        border: 1px solid rgba(0,0,0,.9);
        background-color: #ffffff;
        width: 60%;
        z-index: 1060;
        max-height: 80%;
        overflow: hidden;
    }

    .window-content {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .window-content img {
        height: 100%;
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