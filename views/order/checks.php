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
$role = Yii::$app->authManager->getRolesByUser(Yii::$app->user->id);

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

    if (Yii::$app->session->hasFlash('errorUser')) {
        echo Alert::widget([
            'options' => [
                'class' => 'alert-danger',
            ],
            'body' => Yii::$app->session->getFlash('errorUser'),
        ]);
    }
    ?>
    <?php if (Yii::$app->session->hasFlash('successAdd')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong><?= Yii::$app->session->getFlash('successAdd'); ?>! </strong>
            You can check add Account in current section
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
        'id_order',
        'price',
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
            'label' => 'Customer',
            'content' => function ($model, $key, $index, $column) use($role) {
                if(isset($role['admin'])) {
                    $htmlContent = "<form action='' method='post'>
                                        <input name='orderEmployee' readonly='readonly' type='text' class='employee-name input-no' value='$model[customer]'>
                                        <input type='hidden' name='order-check' value='$model[id_order]'>
                                        <button class='btn btn-success showed-buttons' type='submit' style='margin-right: 2px'>ok</button>
                                        <button data-oldValue='$model[customer]' class='btn btn-danger showed-buttons'>X</button>
                                        <svg class='update-employee' aria-hidden='true' style='display:inline-block;font-size:inherit;height:1em;overflow:visible;vertical-align:-.125em;width:1em' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 512 512'><path fill='currentColor' d='M498 142l-46 46c-5 5-13 5-17 0L324 77c-5-5-5-12 0-17l46-46c19-19 49-19 68 0l60 60c19 19 19 49 0 68zm-214-42L22 362 0 484c-3 16 12 30 28 28l122-22 262-262c5-5 5-13 0-17L301 100c-4-5-12-5-17 0zM124 340c-5-6-5-14 0-20l154-154c6-5 14-5 20 0s5 14 0 20L144 340c-6 5-14 5-20 0zm-36 84h48v36l-64 12-32-31 12-65h36v48z'></path></svg>
                                    </form>";
                    return $htmlContent;
                }
                else return $model['customer'];
            },
            'attribute' => 'customer',
            'contentOptions' => ['class' => 'employee-column'],
        ],
        'date',
        [
            'class' => ActionColumn::class,
            'urlCreator' => function ($action, $model, $key, $index, $column) {
                return Url::toRoute([$action, 'id' => $model['id']]);
            },
            'buttons' => [
                'view' => function ($url, $model, $key) {
                    $checkID = $model['id'];
                    $customUrl = "list?OrdersSearch[id_check]=$checkID";
                    return "<a class='to-view' href='$customUrl'><img src='/images/view_icon.png' alt='View'></a>";
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
    'fields' => ['id_order', 'status'],
    'calendars' => [
        [
            'inputs' => ['minDate', 'maxDate']
        ]
    ],
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

<style>
    .showed-buttons {
        padding: 2px 10px;
    }

    .update-employee {
        cursor: pointer;
    }

    .employee-column {
        width: 150px;
    }

    .employee-name {
        max-width: 100px;
        overflow-x: scroll;
    }

    .input-no {
        border: none;
        outline: none;
        background-color: unset;
    }

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


