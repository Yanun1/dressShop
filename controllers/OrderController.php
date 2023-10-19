<?php

namespace app\controllers;

use app\models\OrderProduct;
use app\models\Orders;
use app\models\OrdersSearch;
use app\models\Products;
use app\models\User;
use app\widgets\Alert;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Yii;
use yii\data\ArrayDataProvider;
use yii\web\ForbiddenHttpException;
use app\models\OrderCheck;
use yii\helpers\Url;


/**
 * OrderController implements the CRUD actions for Orders model.
 */
class OrderController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['admin', 'employee'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['index', 'delete'],
                        'roles' => ['user'],
                    ],
                    [
                        'allow' => false,
                        'roles' => ['?'],
                    ],
                ],
            ],
        ];
    }

    public function init()
    {
        parent::init();
        if(Yii::$app->user->isGuest) {
            $this->redirect('/site/login');
        }
    }

    public function actionIndex()
    {
        //$userId = Yii::$app->user->id;
        //$dataProvider = new ArrayDataProvider(['allModels' => Orders::find()->where("id_user=$userId")->with('user')->with('product')->all()]);

        $searchModel = new OrdersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Orders model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate(){
        $model = new Orders();
        $orderProduct = new OrderProduct();

        $userId = Yii::$app->user->id;

        if(Yii::$app->request->isPost) {
            $postValues = Yii::$app->request->post()['Orders'];
            $orderProductValues = Yii::$app->request->post()['OrderProduct'];

            $checkNubmers = time();

            $check = new OrderCheck();
            $check['id_order'] = $checkNubmers;
            $check->save();
            $checkId = $check['id'];

            for($i = 0; $i < count($postValues['id_product']); $i++){
                $model = new Orders();
                $orderProduct = new OrderProduct();

                $product = Products::find()->with('user')->where("id={$postValues['id_product'][$i]}")->asArray()->one();
                $name = User::find()->where("id=$userId")->asArray()->one();

                $orderProduct['product'] = $product['product'];
                $orderProduct['employee'] = $product['user']['login'];
                $orderProduct['image'] = $product['image'];
                $orderProduct['count'] = $orderProductValues['count'][$i];
                $orderProduct['id_product'] = $postValues['id_product'][$i];
                $orderProduct['user'] = $name['login'];
                $orderProduct['price'] = $orderProductValues['price'][$i];

                if($orderProduct->validate()) {
                    $orderProduct->save();
                }
                else {
                    Yii::$app->session->setFlash('errorOrder', 'Something gone wrong in '."$i row!");
                    return $this->refresh();
                }

                $model['id_product'] = $orderProduct['id'];
                $model['status'] = 'waiting';
                $model['id_check'] = $checkId;

                if($model->validate()) {
                    $model->save();}
                else {
                    Yii::$app->session->setFlash('errorOrder', 'Something gone wrong in '."$i row!");
                    return $this->refresh();
                }
            }
            Yii::$app->session->setFlash('successOrder', 'Ordered');
            return $this->refresh();
        }

        return $this->render('create',compact('model','orderProduct'));
    }

    /**
     * Updates an existing Orders model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $orderProduct = OrderProduct::find()->where("id={$model['id_product']}")->one();

        if (Yii::$app->request->isPost) {

            $order = Yii::$app->request->post()['OrdersSearch'];
            $orderProductPost = Yii::$app->request->post()['OrderProduct'];

            //$model['id'] = $_GET['id'];
            $model['status'] = $order['status'];

            $model->save();

            //$orderProduct['id'] = $model['id_product'];
            $product = Products::find()->where("id={$orderProductPost['id_product']}")->with('user')->one();

            $orderProduct['id_product'] = $orderProductPost['id_product'];
            $orderProduct['price'] = $product['price'];
            $orderProduct['product'] = $product['product'];
            $orderProduct['employee'] = $product['user']['login'];
            $orderProduct['image'] = $product['image'];
            $orderProduct['count'] = $orderProductPost['count'];

            $orderProduct->save();

            return $this->redirect(['index', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model, 'orderProduct' => $orderProduct
        ]);
    }

    /**
     * Deletes an existing Orders model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $row =  $this->findModel($id);
        if ($row['status'] == 'waiting') {
            $row->delete();
            Yii::$app->getSession()->setFlash('success deleting',"The order has been deleted successfully.");
        }
        else
            Yii::$app->getSession()->setFlash('failed',"You can't delete order! He is not in waiting.");
        return $this->redirect(['index']);
    }

    /**
     * Finds the Orders model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Orders the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = OrdersSearch::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
