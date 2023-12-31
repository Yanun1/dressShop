<?php

namespace app\controllers;

use app\models\CheckSearch;
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
    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

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

    public function actionList()
    {
        $searchModel = new OrdersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);

    }

    public function actionIndex()
    {
        $searchModel = new CheckSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if ($this->request->isPost) {
            $newName = \Yii::$app->request->post('orderEmployee');
            $checkID = \Yii::$app->request->post('order-check');

            if (empty(User::find()->where("login='$newName'")->one())) {
                \Yii::$app->session->setFlash('errorUser', 'Аn account with that login is does\'t exists');
                return $this->render('checks', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                ]);
            }

            $check = CheckSearch::find()->where("id_order=$checkID")->one();
            $check->customer = $newName;

            if(!$check->save()) {
                \Yii::$app->session->setFlash('errorUser', 'Something gone wrong!');
                return $this->render('checks', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                ]);
            }
            \Yii::$app->session->setFlash('successAdd', 'Added');
        }

        return $this->render('checks', [
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
        $error = '';

        if(Yii::$app->request->isPost) {
            $postValues = Yii::$app->request->post()['Orders'];

            $checkNubmers = time();



            $check = new OrderCheck();
            $check['id_order'] = $checkNubmers;


            if(isset(Yii::$app->request->post()['userInput']) && Yii::$app->request->post()['userInput'] != null) {
                $userChose = Yii::$app->request->post()['userInput'];

                if(!empty(User::find()->where("login='$userChose'")->one())) {
                    $check['customer'] = $userChose;
                }
                else {
                    $error = "The user with this login does not exist!";
                    return $this->render('create',compact('model', 'error'));
                }
            }
            else {
                $userId = Yii::$app->user->getId();
                $check['customer'] = User::find()->where("id=$userId")->asArray()->one()['login'];
            }


            $check['price'] = \Yii::$app->request->post('totalCost');

            $countAll = 0;
            foreach ($postValues['count'] as $item)
                $countAll += $item;

            $check['count'] = $countAll;
            $check['status'] = 'waiting';

            if(!$check->save()) {
                Yii::$app->session->setFlash('errorOrder', 'Something gone wrong in '."row!");
                return $this->refresh();
            }

            for($i = 0; $i < count($postValues['product']); $i++){
                $model = new Orders();

                $product = Products::find()->with('user')->where("id={$postValues['product'][$i]}")->asArray()->one();

                $model['product'] = $product['product'];
                $model['employee'] = $product['user']['login'];
                $model['image'] = $product['image'];
                $model['count'] = $postValues['count'][$i];
                $model['price'] = $postValues['price'][$i];
                $model['status'] = 'waiting';
                $model['id_check'] = $check['id'];

                if(!$model->save()) {
                    Yii::$app->session->setFlash('errorOrder', 'Something gone wrong in '."$i row!");
                    return $this->refresh();
                }
            }
            Yii::$app->session->setFlash('successOrder', 'Ordered');
            return $this->refresh();
        }

        return $this->render('create',compact('model', 'error'));
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

        if (Yii::$app->request->isPost) {
            $oldModel = clone $model;

            $model->load(Yii::$app->request->post());

            $model->save();

            if($oldModel['price'] != $model['price'] or $oldModel['count'] != $model['count'] or $oldModel['product'] != $model['product'] or $oldModel['status'] != $model['status']) {
                $check = OrderCheck::find()->where("id=$model[id_check]")->one();
                $check['price'] += $model['price']*$model['count'] - $oldModel['price']*$oldModel['count'];
                $check['count'] += $model['count'] - $oldModel['count'];

                $orders = Orders::find()->where("id_check=$check[id]")->asArray()->all();

                $waiting = 0;
                $on_the_way = 0;
                $delivered = 0;
                $received = 0;

                foreach ($orders as $order) {
                    switch($order['status']) {
                        case "waiting":
                            $waiting++;
                            break;
                        case "on the way":
                            $on_the_way++;
                            break;
                        case "delivered":
                            $delivered++;
                            break;
                        case "received":
                            $received++;
                            break;
                    }
                }

                if($waiting != 0) {
                    $check['status'] = 'waiting';
                }elseif($on_the_way != 0)  {
                    $check['status'] = 'on the way';
                }elseif ($delivered != 0) {
                    $check['status'] = 'delivered';
                }else
                    $check['status'] = 'received';

                $check->save();
            }

            return $this->redirect(['index', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model
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

        $role = Yii::$app->authManager->getRolesByUser(Yii::$app->user->id);

        if ($row['status'] == 'waiting' or isset($role['admin'])) {
            $check = OrderCheck::find()->where("id=$row[id_check]")->one();
            $check['price'] -= $row['price']*$row['count'];
            $check['count'] -= $row['count'];

            $row->delete();

            $orders = Orders::find()->where("id_check=$check[id]")->asArray()->all();

            $waiting = 0;
            $on_the_way = 0;
            $delivered = 0;
            $received = 0;

            foreach ($orders as $order) {
                switch($order['status']) {
                    case "waiting":
                        $waiting++;
                        break;
                    case "on the way":
                        $on_the_way++;
                        break;
                    case "delivered":
                        $delivered++;
                        break;
                    case "received":
                        $received++;
                        break;
                }
            }

            if($waiting != 0) {
                $check['status'] = 'waiting';
            }elseif($on_the_way != 0)  {
                $check['status'] = 'on the way';
            }elseif ($delivered != 0) {
                $check['status'] = 'delivered';
            }else
                $check['status'] = 'received';

            $check->save();

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
        if (($model = Orders::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    // public  function actionChart(){
    //     return $this->render('chart');
    // }
}
