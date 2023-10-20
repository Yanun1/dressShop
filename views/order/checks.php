<?php

use app\models\CheckSearch;
use app\models\Orders;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\bootstrap5\Alert;
use app\components\SearchWidget;
use app\components\productSliderWidget;
use app\models\OrderCheck;

if(Yii::$app->user->isGuest) {
    Yii::$app->response->redirect(['/site/login']);
    return;
}


/** @var yii\web\View $this */
/** @var app\models\OrdersSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->registerJsFile('@web/js/order-index.js', ['position'=>\yii\web\View::POS_END, 'depends' => [\yii\web\JqueryAsset::class, \yii\web\YiiAsset::class, \yii\bootstrap5\BootstrapAsset::class]]);

$this->title = 'Orders';
$this->params['breadcrumbs'][] = $this->title;
$defaultValue = 'option2';
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
            'id',
            [
                'label' => 'ID Check',
                'value' => function ($model) {
                    return $model['id_order'];
                },
                'attribute' => 'orderCheck.id_order',
            ],
            [
                'label' => 'Total Price',
                'value' => function ($model) {
                    return $model['Total_Price'];
                },
                'attribute' => 'Total_Price'
            ],
            [
                'label' => 'Total Count',
                'value' => function ($model) {
                    return $model['Total_Count'];
                },
                'attribute' => 'Total_Count'
            ],
            [
                'label' => 'Date',
                'value' => function ($model) {
                    $temp = new DateTime($model['orders'][0]['data']);
                    return $temp->format('Y-m-d');
                },
                'attribute' => 'DATE(data)'
            ],
            [
                'class' => ActionColumn::class,
                'urlCreator' => function ($action, $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model['id']]);
                },
                'buttons' => [
                    'view' => function ($url, $model, $key) {
                        $checkID = $model['id_order'];
                        $customUrl = "index?OrdersSearch[id_check]=$checkID";
                        return "<a class='to-view' href='$customUrl'><img src='/images/view_icon.png' alt='View'></a>";
                        //return \yii\helpers\Html::a('View', $customUrl, ['class' => 'btn btn-primary']);
                    },
                ],
                'visibleButtons' => [
                    'update' => false,
                    'delete' => false,
                ],
            ],
        ],
    ]); ?>
</div>

<?= SearchWidget::widget([
    'model' => $searchModel,
    'fields' => ['id', 'id_order'],
//    'calendars' => [
//        [
//            'inputs' => ['minDate', 'maxDate']
//        ]
//    ],
    'ranges' => [
        [
            'title' => 'Price',
            'names' => ['minPrice', 'maxPrice']
        ],
        [
            'title' => 'Count',
            'names' => ['minCount', 'maxCount']
        ]
    ]
]) ?>


<?= productSliderWidget::widget() ?>

<style>

    .to-view img {
        height: 15px;
        width: 23px;
    }

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