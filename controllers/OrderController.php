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
//        echo '<pre>';
//        var_dump($dataProvider);die;

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

        if(Yii::$app->request->isPost) {
            $postValues = Yii::$app->request->post()['Orders'];

            $checkNubmers = time();

            $check = new OrderCheck();
            $check['id_order'] = $checkNubmers;
            $userId = Yii::$app->user->getId();
            $check['customer'] = User::find()->where("id=$userId")->asArray()->one()['login'];
            $check['price'] = \Yii::$app->request->post('totalCost');

            $countAll = 0;
            foreach ($postValues['count'] as $item)
                $countAll += $item;

            $check['count'] = $countAll;
            $check['status'] = 'waiting';

            if(!$check->save()) {
                Yii::$app->session->setFlash('errorOrder', 'Something gone wrong in '."$i row!");
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

        return $this->render('create',compact('model'));
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

            $model->load(Yii::$app->request->post());

            $model->save();



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
        if (($model = Orders::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    // public  function actionChart(){
    //     return $this->render('chart');
    // }
}
